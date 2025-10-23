import React, { useEffect, useState } from 'react';
import api from '../../api/api';
import { useNavigate } from 'react-router-dom';
import { useGame } from '../../hooks/useGame';

interface Category { id:number; name:string; }

export default function GameStart() {
    const [playerName, setPlayerName] = useState('');
    const [categories, setCategories] = useState<Category[]>([]);
    const [selected, setSelected] = useState<number[]>([]);
    const [mode, setMode] = useState<'fixed' | 'progressive'>('progressive');
    const [fixedLevel, setFixedLevel] = useState<number>(1);

    const navigate = useNavigate();
    const { startGame } = useGame();

    useEffect(() => {
        api.get('/categories').then(res => setCategories(res.data));
    }, []);

    const toggleCategory = (id:number) => {
        setSelected(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]);
    };

    const onStart = async () => {
        if (!playerName.trim()) return alert('Informe seu nome');
        await startGame({
            player_name: playerName.trim(),
            category_ids: selected, // vazio = todas
            mode,
            fixed_level: mode === 'fixed' ? fixedLevel : null
        });
        navigate('/game/play');
    };

    return (
        <div className="max-w-2xl mx-auto space-y-6">
            <h1 className="text-3xl font-bold">Começar Jogo</h1>

            <div className="space-y-2">
                <label className="font-semibold">Seu nome</label>
                <input
                    className="border rounded p-2 w-full"
                    value={playerName}
                    onChange={e => setPlayerName(e.target.value)}
                    placeholder="Ex.: Alessandro"
                />
            </div>

            <div className="space-y-2">
                <label className="font-semibold">Temas (opcional)</label>
                <div className="flex flex-wrap gap-2">
                    {categories.map(c => (
                        <button
                            key={c.id}
                            onClick={() => toggleCategory(c.id)}
                            className={`px-3 py-1 rounded border ${selected.includes(c.id) ? 'bg-blue-600 text-white' : 'bg-white'}`}
                        >
                            {c.name}
                        </button>
                    ))}
                </div>
                <small className="text-gray-500">Se nenhum tema for selecionado, será usado "Todos".</small>
            </div>

            <div className="space-y-2">
                <label className="font-semibold">Dificuldade</label>
                <div className="flex items-center gap-4">
                    <label className="flex items-center gap-2">
                        <input type="radio" checked={mode==='progressive'} onChange={() => setMode('progressive')} />
                        Gradual (padrão)
                    </label>
                    <label className="flex items-center gap-2">
                        <input type="radio" checked={mode==='fixed'} onChange={() => setMode('fixed')} />
                        Fixa
                    </label>
                    {mode === 'fixed' && (
                        <select value={fixedLevel} onChange={e => setFixedLevel(Number(e.target.value))} className="border rounded p-1">
                            <option value={1}>Fácil</option>
                            <option value={2}>Médio</option>
                            <option value={3}>Difícil</option>
                            <option value={4}>Muito Difícil</option>
                            <option value={5}>Extremo</option>
                        </select>
                    )}
                </div>
            </div>

            <button onClick={onStart} className="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded">
                Iniciar
            </button>
        </div>
    );
}
