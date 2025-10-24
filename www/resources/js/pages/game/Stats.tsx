import React, {useEffect, useState} from 'react';
import {useParams, Link} from 'react-router-dom';
import api from '../../api/api';
import Loader from '../../components/Loader';

interface Stats {
    game_id: number;
    player_name: string;
    score: number;
    ended_at: string;
    total_questions_answered: number;
    correct: number;
    wrong: number;
    accuracy: number;
    avg_response_ms: number;
    best_response_ms: number;
    worst_response_ms: number;
}

export default function Stats() {
    const {id} = useParams();
    const [data, setData] = useState<Stats | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get(`/game/${id}/stats`).then(res => setData(res.data)).finally(() => setLoading(false));
    }, [id]);

    if (loading) return <Loader message="Calculando estatísticas..."/>;
    if (!data) return <div className="p-4">Estatísticas indisponíveis.</div>;

    return (
        <div className="max-w-2xl mx-auto space-y-4">
            <h2 className="text-2xl font-bold">📊 Estatísticas da Partida</h2>
            <div className="bg-white rounded shadow p-4 grid grid-cols-2 gap-3">
                <div><strong>Jogador:</strong> {data.player_name}</div>
                <div><strong>Pontuação:</strong> R$ {data.score.toLocaleString('pt-BR')}</div>
                <div><strong>Acertos:</strong> {data.correct}</div>
                <div><strong>Erros:</strong> {data.wrong}</div>
                <div><strong>Precisão:</strong> {data.accuracy}%</div>
                <div><strong>Tempo médio:</strong> {(data.avg_response_ms / 1000).toFixed(2)}s</div>
                <div><strong>Melhor tempo:</strong> {(data.best_response_ms / 1000).toFixed(2)}s</div>
                <div><strong>Pior tempo:</strong> {(data.worst_response_ms / 1000).toFixed(2)}s</div>
            </div>

            <div className="flex gap-3">
                <Link to="/game/ranking" className="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                    Ver Ranking
                </Link>
                <Link to="/game/start" className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Jogar Novamente
                </Link>
            </div>
        </div>
    );
}
