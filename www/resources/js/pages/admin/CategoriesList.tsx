import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface Category {
    id: number;
    name: string;
    slug: string;
}

export default function CategoriesList() {
    const [data, setData] = useState<Category[]>([]);
    const [name, setName] = useState('');
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        load();
    }, []);

    async function load() {
        const r = await api.get('/categories');
        setData(r.data);
        setLoading(false);
    }

    async function create() {
        await api.post('/categories', {name});
        setName('');
        load();
    }

    if (loading) return <Loader/>;

    return (
        <div>
            <h2 className="text-2xl font-bold mb-3">📚 Categorias</h2>
            <div className="flex gap-2 mb-3">
                <input className="border p-1 flex-1" placeholder="Nova categoria" value={name}
                       onChange={e => setName(e.target.value)}/>
                <button onClick={create} className="bg-green-600 text-white px-3 py-1 rounded">Adicionar</button>
            </div>
            <ul className="bg-white rounded shadow divide-y">
                {data.map(c => <li key={c.id} className="p-2">{c.name}</li>)}
            </ul>
        </div>
    );
}
