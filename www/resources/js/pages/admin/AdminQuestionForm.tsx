import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import {useNavigate, useParams} from 'react-router-dom';
import Loader from '../../components/Loader';

interface Category {
    id: number;
    name: string;
}

interface Difficulty {
    id: number;
    name: string;
    level: number;
}

export default function AdminQuestionForm() {
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState<Category[]>([]);
    const [difficulties, setDifficulties] = useState<Difficulty[]>([]);
    const [statement, setStatement] = useState('');
    const [category, setCategory] = useState<number>();
    const [difficulty, setDifficulty] = useState<number>();
    const [alternatives, setAlternatives] = useState<{ letter: string; text: string }[]>([
        {letter: 'A', text: ''}, {letter: 'B', text: ''}, {letter: 'C', text: ''}, {letter: 'D', text: ''}
    ]);
    const [correct, setCorrect] = useState('A');
    const navigate = useNavigate();
    const {id} = useParams();

    useEffect(() => {
        Promise.all([
            api.get('/categories'),
            api.get('/difficulties'),
            id ? api.get(`/questions/${id}`) : Promise.resolve({data: null})
        ]).then(([c, d, q]) => {
            setCategories(c.data);
            setDifficulties(d.data);
            if (q.data) {
                setStatement(q.data.statement);
                setCategory(q.data.category_id);
                setDifficulty(q.data.difficulty_id);
                setAlternatives(q.data.alternatives.map((a: any) => ({letter: a.letter, text: a.text})));
                const correctAlt = q.data.alternatives.find((a: any) => a.is_correct);
                if (correctAlt) setCorrect(correctAlt.letter);
            }
        }).finally(() => setLoading(false));
    }, [id]);

    if (loading) return <Loader/>;

    const save = async () => {
        const payload = {
            category_id: category,
            difficulty_id: difficulty,
            statement,
            alternatives: Object.fromEntries(alternatives.map(a => [a.letter, a.text])),
            correct
        };
        if (id) await api.put(`/questions/${id}`, payload);
        else await api.post('/questions', payload);
        alert('Salvo com sucesso!');
        navigate('/admin/questions');
    };

    return (
        <div className="max-w-3xl mx-auto space-y-4">
            <h2 className="text-2xl font-bold">{id ? 'Editar' : 'Nova'} Pergunta</h2>
            <div>
                <label className="block mb-1">Enunciado</label>
                <textarea className="border rounded w-full p-2" rows={3} value={statement}
                          onChange={e => setStatement(e.target.value)}/>
            </div>

            <div className="flex gap-4">
                <div className="flex-1">
                    <label>Categoria</label>
                    <select className="border rounded w-full p-2" value={category}
                            onChange={e => setCategory(Number(e.target.value))}>
                        <option>Selecione</option>
                        {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                </div>
                <div className="flex-1">
                    <label>Dificuldade</label>
                    <select className="border rounded w-full p-2" value={difficulty}
                            onChange={e => setDifficulty(Number(e.target.value))}>
                        <option>Selecione</option>
                        {difficulties.map(d => <option key={d.id} value={d.id}>{d.name}</option>)}
                    </select>
                </div>
            </div>

            <div>
                <label>Alternativas</label>
                {alternatives.map((a, i) => (
                    <div key={a.letter} className="flex items-center gap-2 mt-1">
                        <span className="font-bold">{a.letter})</span>
                        <input className="flex-1 border rounded p-1"
                               value={a.text} onChange={e => {
                            const arr = [...alternatives];
                            arr[i].text = e.target.value;
                            setAlternatives(arr);
                        }}
                        />
                        <input type="radio" name="correct" checked={correct === a.letter}
                               onChange={() => setCorrect(a.letter)}/> correta
                    </div>
                ))}
            </div>

            <button onClick={save} className="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Salvar
            </button>
        </div>
    );
}
