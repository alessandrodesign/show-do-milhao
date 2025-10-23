import React, {useEffect, useState} from 'react';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface RankItem {
    player_name: string;
    score: number;
    ended_at: string;
}

export default function Ranking() {
    const [data, setData] = useState<RankItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get('/game/ranking').then(r => setData(r.data)).finally(() => setLoading(false));
    }, []);

    if (loading) return <Loader message="Carregando ranking..."/>;

    return (
        <div className="max-w-2xl mx-auto">
            <h2 className="text-2xl font-bold mb-4">🏆 Ranking</h2>
            <table className="w-full border text-left">
                <thead className="bg-gray-100">
                <tr>
                    <th className="p-2">#</th>
                    <th className="p-2">Jogador</th>
                    <th className="p-2">Pontuação</th>
                    <th className="p-2">Data</th>
                </tr>
                </thead>
                <tbody>
                {data.map((r, i) => (
                    <tr key={i} className="border-t">
                        <td className="p-2">{i + 1}</td>
                        <td className="p-2">{r.player_name}</td>
                        <td className="p-2">R$ {r.score.toLocaleString('pt-BR')}</td>
                        <td className="p-2 text-sm text-gray-500">{new Date(r.ended_at).toLocaleString('pt-BR')}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
