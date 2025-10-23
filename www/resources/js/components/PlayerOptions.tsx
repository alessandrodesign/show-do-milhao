import React, {useState} from 'react';
import {useGame} from '../hooks/useGame';

interface Props {
    onUseFifty: () => void;
    onSkip: () => void;
    onAskAudience: () => void;
    disabled?: boolean;
}

export default function PlayerOptions({
                                          onUseFifty,
                                          onSkip,
                                          onAskAudience,
                                          disabled
                                      }: Props) {
    const {game} = useGame();
    const [used, setUsed] = useState({
        fifty: false,
        skip: false,
        audience: false
    });

    function handle(type: keyof typeof used, fn: () => void) {
        if (used[type] || disabled) return;
        setUsed({...used, [type]: true});
        fn();
    }

    return (
        <div className="flex flex-wrap justify-center gap-3 my-4">
            <button
                onClick={() => handle('fifty', onUseFifty)}
                disabled={used.fifty || disabled}
                className="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                50 / 50
            </button>
            <button
                onClick={() => handle('skip', onSkip)}
                disabled={used.skip || disabled}
                className="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                Pular
            </button>
            <button
                onClick={() => handle('audience', onAskAudience)}
                disabled={used.audience || disabled}
                className="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                Universitários
            </button>
        </div>
    );
}
