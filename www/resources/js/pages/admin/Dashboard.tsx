import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

export default function Dashboard() {
    const [ranking, setRanking] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get('/game/ranking').then(r => setRanking(r.data)).finally(() => setLoading(false));
    }, []);

    if (loading) return <Loader message="Carregando dashboard..."/>;

    return (
        <div>
            <h1 className="text-2xl font-bold mb-4">📊 Estatísticas Gerais</h1>
            <div className="bg-white rounded shadow p-4 mb-6">
                <h2 className="text-xl font-semibold mb-2">Top 5 Jogadores</h2>
                <table className="w-full border">
                    <thead>
                    <tr>
                        <th>Jogador</th>
                        <th>Pontuação</th>
                        <th>Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    {ranking.slice(0, 5).map((r, i) => (
                        <tr key={i} className="border-t">
                            <td className="p-2">{r.player_name}</td>
                            <td className="p-2">R$ {r.score.toLocaleString('pt-BR')}</td>
                            <td className="p-2">{new Date(r.ended_at).toLocaleDateString('pt-BR')}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
