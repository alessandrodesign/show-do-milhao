import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface Log {
    id: number;
    action: string;
    entity_type: string;
    entity_id: number;
    ip: string;
    created_at: string;
}

export default function LogsList() {
    const [logs, setLogs] = useState<Log[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get('/admin/logs').then(r => setLogs(r.data.data)).finally(() => setLoading(false));
    }, []);

    if (loading) return <Loader/>;

    return (
        <div>
            <h2 className="text-2xl font-bold mb-3">📝 Auditoria</h2>
            <table className="w-full border text-sm">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ação</th>
                    <th>Entidade</th>
                    <th>IP</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                {logs.map(l => (
                    <tr key={l.id} className="border-t">
                        <td className="p-2">{l.id}</td>
                        <td className="p-2">{l.action}</td>
                        <td className="p-2">{l.entity_type}#{l.entity_id}</td>
                        <td className="p-2">{l.ip}</td>
                        <td className="p-2">{new Date(l.created_at).toLocaleString('pt-BR')}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
