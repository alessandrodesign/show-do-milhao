import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface Difficulty {
    id: number;
    name: string;
    level: number;
}

export default function DifficulitiesList() {
    const [data, setData] = useState<Difficulty[]>([]);
    const [name, setName] = useState('');
    const [level, setLevel] = useState(1);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        load();
    }, []);

    async function load() {
        const r = await api.get('/difficulties');
        setData(r.data);
        setLoading(false);
    }

    async function create() {
        await api.post('/difficulties', {name, level});
        setName('');
        load();
    }

    if (loading) return <Loader/>;

    return (
        <div>
            <h2 className="text-2xl font-bold mb-3">🎯 Dificuldades</h2>
            <div className="flex gap-2 mb-3">
                <input className="border p-1 flex-1" placeholder="Nome" value={name}
                       onChange={e => setName(e.target.value)}/>
                <input type="number" className="border p-1 w-24" min={1} max={5} value={level}
                       onChange={e => setLevel(Number(e.target.value))}/>
                <button onClick={create} className="bg-green-600 text-white px-3 py-1 rounded">Adicionar</button>
            </div>
            <ul className="bg-white rounded shadow divide-y">
                {data.map(d => <li key={d.id} className="p-2">{d.level} - {d.name}</li>)}
            </ul>
        </div>
    );
}
