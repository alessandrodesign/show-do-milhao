import React, { createContext, useContext, useState, ReactNode } from 'react';

export type Mode = 'fixed' | 'progressive';

export interface GameState {
    gameId?: number | null;
    playerName: string;
    categoryIds: number[];
    mode: Mode;
    fixedLevel?: number | null;
    prizes: number[];
    totalQuestions: number;
    currentIndex: number;           // 0-based
    score: number;                  // acumulado
    askedQuestions: number[];       // ids já perguntados (evitar repetição)
    helps: {
        fifty: boolean; // já usou 50/50?
        skip: boolean;
        uni: boolean;
    };
}

const defaultState: GameState = {
    gameId: null,
    playerName: '',
    categoryIds: [],
    mode: 'progressive',
    fixedLevel: null,
    prizes: [],
    totalQuestions: 0,
    currentIndex: 0,
    score: 0,
    askedQuestions: [],
    helps: { fifty: false, skip: false, uni: false },
};

interface GameContextType {
    game: GameState;
    setGame: React.Dispatch<React.SetStateAction<GameState>>;
    resetGame: () => void;
}

const GameContext = createContext<GameContextType | undefined>(undefined);

export function GameProvider({ children }: { children: ReactNode }) {
    const [game, setGame] = useState<GameState>(defaultState);

    const resetGame = () => setGame(defaultState);

    return (
        <GameContext.Provider value={{ game, setGame, resetGame }}>
            {children}
        </GameContext.Provider>
    );
}

export function useGameContext() {
    const ctx = useContext(GameContext);
    if (!ctx) throw new Error('useGameContext deve ser usado dentro de GameProvider');
    return ctx;
}
