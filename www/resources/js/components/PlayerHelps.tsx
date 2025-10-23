import React from 'react';
import { useGame } from '../hooks/useGame';
import api from '../api/api';

interface Props {
    onSkip: () => void;
    onFifty: (letters: string[]) => void;
}

export default function PlayerHelps({ onSkip, onFifty }: Props) {
    const { game, setGame } = useGame();

    async function handleUni(questionId: number) {
        if (game.helps.uni) return;
        setGame((g: any) => ({ ...g, helps: { ...g.helps, uni: true } }));
        const { data } = await api.post('/game/help/universitarios', { question_id: questionId });
        alert(
            'Resultado dos universitários:\n' +
            Object.entries(data.votes)
                .map(([k, v]) => `${k}: ${v}%`)
                .join('\n')
        );
    }

    function handleFifty() {
        if (game.helps.fifty) return;
        const letters = ['A', 'B', 'C', 'D'];
        const toHide = letters.sort(() => 0.5 - Math.random()).slice(0, 2);
        onFifty(toHide);
        setGame((g: any) => ({ ...g, helps: { ...g.helps, fifty: true } }));
    }

    function handleSkip() {
        if (game.helps.skip) return;
        setGame((g: any) => ({ ...g, helps: { ...g.helps, skip: true } }));
        onSkip();
    }

    return (
        <div className="flex justify-center gap-3 my-4">
            <button
                onClick={handleFifty}
                disabled={game.helps.fifty}
                className="bg-yellow-500 hover:bg-yellow-600 disabled:opacity-50 text-white px-3 py-1 rounded"
            >
                50/50
            </button>
            <button
                onClick={handleSkip}
                disabled={game.helps.skip}
                className="bg-blue-500 hover:bg-blue-600 disabled:opacity-50 text-white px-3 py-1 rounded"
            >
                Pular
            </button>
            <button
                onClick={() => handleUni(game.askedQuestions.slice(-1)[0])}
                disabled={game.helps.uni}
                className="bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white px-3 py-1 rounded"
            >
                Universitários
            </button>
        </div>
    );
}
