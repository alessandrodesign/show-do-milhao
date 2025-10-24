import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface User {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
}

export default function UsersList() {
    const [users, setUsers] = useState<User[]>([]);
    const [loading, setLoading] = useState(true);
    const [q, setQ] = useState('');

    async function load() {
        const r = await api.get('/admin/users', {params: {q}});
        setUsers(r.data.data);
        setLoading(false);
    }

    useEffect(() => {
        load();
    }, []);

    if (loading) return <Loader/>;

    return (
        <div>
            <h2 className="text-2xl font-bold mb-3">👥 Usuários</h2>
            <div className="flex gap-2 mb-3">
                <input className="border p-1 flex-1" placeholder="Buscar" value={q}
                       onChange={e => setQ(e.target.value)}/>
                <button onClick={load} className="bg-blue-600 text-white px-3 rounded">Buscar</button>
            </div>
            <table className="w-full border">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Admin</th>
                </tr>
                </thead>
                <tbody>
                {users.map(u => (
                    <tr key={u.id} className="border-t">
                        <td className="p-2">{u.name}</td>
                        <td className="p-2">{u.email}</td>
                        <td className="p-2 text-center">{u.is_admin ? '✅' : '❌'}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
