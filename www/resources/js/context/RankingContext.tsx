import React, {createContext, useContext, useState, ReactNode, useEffect} from 'react';

export interface ScoreEntry {
    name: string;
    score: number;
    date: string;
}

interface RankingContextType {
    ranking: ScoreEntry[];
    addScore: (entry: ScoreEntry) => void;
    clearRanking: () => void;
}

const RankingContext = createContext<RankingContextType | undefined>(undefined);

export function RankingProvider({children}: { children: ReactNode }) {
    const [ranking, setRanking] = useState<ScoreEntry[]>([]);

    useEffect(() => {
        const stored = localStorage.getItem('ranking');
        if (stored) setRanking(JSON.parse(stored));
    }, []);

    function save(newList: ScoreEntry[]) {
        localStorage.setItem('ranking', JSON.stringify(newList));
    }

    const addScore = (entry: ScoreEntry) => {
        const newList = [...ranking, entry].sort((a, b) => b.score - a.score).slice(0, 20);
        setRanking(newList);
        save(newList);
    };

    const clearRanking = () => {
        setRanking([]);
        localStorage.removeItem('ranking');
    };

    return (
        <RankingContext.Provider value={{ranking, addScore, clearRanking}}>
            {children}
        </RankingContext.Provider>
    );
}

export function useRanking() {
    const ctx = useContext(RankingContext);
    if (!ctx) throw new Error('useRanking deve ser usado dentro de RankingProvider');
    return ctx;
}
