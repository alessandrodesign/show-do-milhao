import React from 'react';
import { useGame } from '../hooks/useGame';

export default function HUD() {
    const { game } = useGame();
    const prize = game.prizes[game.currentIndex] ?? 0;

    return (
        <div className="flex items-center justify-between bg-indigo-700 text-white rounded px-4 py-2 mb-4">
            <div>👤 {game.playerName}</div>
            <div>Pergunta #{game.currentIndex + 1} de {game.totalQuestions}</div>
            <div>Vale: R$ {prize.toLocaleString('pt-BR')}</div>
            <div>Acumulado: <strong>R$ {game.score.toLocaleString('pt-BR')}</strong></div>
        </div>
    );
}
