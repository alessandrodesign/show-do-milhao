import api from '../api/api';
import {useGameContext} from '../context/GameContext';

export function useGame() {
    const {game, setGame, resetGame} = useGameContext();

    async function startGame(payload: {
        player_name: string;
        category_ids: number[];
        mode: 'fixed' | 'progressive';
        fixed_level?: number | null;
    }) {
        const {data} = await api.post('/game/start', payload);

        setGame((g) => ({
            ...g,
            gameId: data.game_id,
            playerName: data.player_name,
            categoryIds: data.category_ids,
            mode: data.mode,
            fixedLevel: data.fixed_level,
            prizes: data.prizes,
            totalQuestions: data.total_questions,
            currentIndex: 0,
            score: 0,
            askedQuestions: [],
        }));
    }

    async function fetchQuestion() {
        const {data} = await api.post('/game/question', {
            current_index: game.currentIndex,
            category_ids: game.categoryIds,
            mode: game.mode,
            fixed_level: game.fixedLevel,
            exclude_question_ids: game.askedQuestions,
        });
        return data as {
            question: { id: number; statement: string; alternatives: { id: number; letter: string; text: string }[] };
            level: number;
        };
    }

    async function checkAnswer(questionId: number, alternativeId: number, index: number, responseMs: number) {
        const {data} = await api.post('/game/answer', {
            game_id: game.gameId,
            question_id: questionId,
            alternative_id: alternativeId,
            index,
            response_ms: responseMs,
            total_questions: game.totalQuestions,
        });
        return data as { correct: boolean };
    }

    function advance(correct: boolean) {
        setGame((g) => {
            const prize = g.prizes[g.currentIndex] ?? 0;
            return {
                ...g,
                currentIndex: correct ? g.currentIndex + 1 : g.currentIndex,
                score: correct ? g.score + prize : g.score,
            };
        });
    }

    function registerAsked(questionId: number) {
        setGame((g) => ({
            ...g,
            askedQuestions: [...g.askedQuestions, questionId],
        }));
    }

    return {game, setGame, startGame, fetchQuestion, checkAnswer, advance, registerAsked, resetGame};
}
