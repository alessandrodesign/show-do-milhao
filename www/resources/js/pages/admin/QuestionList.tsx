import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';
import {Link} from 'react-router-dom';

interface Question {
    id: number;
    statement: string;
    category: { name: string };
    difficulty: { name: string };
}

export default function QuestionList() {
    const [questions, setQuestions] = useState<Question[]>([]);
    const [q, setQ] = useState('');
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [pages, setPages] = useState(1);

    async function load(p = 1) {
        setLoading(true);
        const {data} = await api.get('/questions', {params: {page: p, q}});
        setQuestions(data.data);
        setPages(data.last_page);
        setPage(data.current_page);
        setLoading(false);
    }

    useEffect(() => {
        load();
    }, []);

    if (loading) return <Loader/>;

    return (
        <div className="max-w-4xl mx-auto space-y-4">
            <div className="flex justify-between items-center">
                <h2 className="text-2xl font-bold">Perguntas</h2>
                <Link to="/admin/questions/new" className="bg-green-600 text-white px-3 py-1 rounded">Nova</Link>
            </div>

            <div className="flex gap-2">
                <input className="border rounded p-1 flex-1" placeholder="Buscar..." value={q}
                       onChange={e => setQ(e.target.value)}/>
                <button onClick={() => load()} className="bg-blue-600 text-white px-3 rounded">Buscar</button>
            </div>

            <table className="w-full border text-left">
                <thead className="bg-gray-100">
                <tr>
                    <th>ID</th>
                    <th>Enunciado</th>
                    <th>Categoria</th>
                    <th>Dificuldade</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {questions.map(item => (
                    <tr key={item.id} className="border-t">
                        <td className="p-2">{item.id}</td>
                        <td className="p-2">{item.statement}</td>
                        <td className="p-2">{item.category?.name}</td>
                        <td className="p-2">{item.difficulty?.name}</td>
                        <td className="p-2 text-right">
                            <Link to={`/admin/questions/${item.id}`} className="text-blue-600">✏️</Link>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            <div className="flex justify-center gap-2 mt-2">
                {[...Array(pages)].map((_, i) => (
                    <button key={i} onClick={() => load(i + 1)}
                            className={`px-2 ${page === i + 1 ? 'bg-blue-600 text-white' : 'bg-gray-200'}`}>{i + 1}</button>
                ))}
            </div>
        </div>
    );
}
