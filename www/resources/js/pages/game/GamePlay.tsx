import React, {useEffect, useState} from 'react';
import {Howl} from 'howler';
import {useGame} from '../../hooks/useGame';
import {useNavigate} from 'react-router-dom';
import HUD from '../../components/HUD';
import ModalSuspense from '../../components/ModalSuspense';
import PlayerOptions from '../../components/PlayerOptions';
import ModalAudience from '../../components/ModalAudience';
import Loader from '../../components/Loader';
import PlayerHelps from '../../components/PlayerHelps';

const sndSuspense = new Howl({src: ['/sounds/suspense.mp3']});
const sndCorrect = new Howl({src: ['/sounds/correct.mp3']});
const sndWrong = new Howl({src: ['/sounds/wrong.mp3']});

interface Alt {
    id: number;
    letter: string;
    text: string
}

interface LoadedQuestion {
    id: number;
    statement: string;
    alternatives: Alt[]
}

export default function GamePlay() {
    const {game, fetchQuestion, checkAnswer, advance, registerAsked, resetGame} = useGame();
    const [q, setQ] = useState<LoadedQuestion | null>(null);
    const [pickedAlt, setPickedAlt] = useState<number | null>(null);
    const [showAskBtn, setShowAskBtn] = useState(false);
    const [modal, setModal] = useState(false);
    const [revealResult, setRevealResult] = useState<null | boolean>(null);
    const [audienceOpen, setAudienceOpen] = useState(false);
    const navigate = useNavigate();

    const isGameOver = game.currentIndex >= game.totalQuestions;

    useEffect(() => {
        if (!game.prizes.length) {
            // Acesso direto sem start
            navigate('/game/start');
            return;
        }
        if (isGameOver) return;
        load();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [game.currentIndex]);

    async function load() {
        setPickedAlt(null);
        setShowAskBtn(false);
        setRevealResult(null);
        const data = await fetchQuestion();
        setQ(data.question);
        registerAsked(data.question.id);
    }

    async function onAsk() {
        if (!q || pickedAlt === null) return;
        // abre suspense primeiro
        setModal(true);
        sndSuspense.play();
    }

    async function onReveal() {
        setModal(false);
        if (!q || pickedAlt === null) return;

        const res = await checkAnswer(q.id, pickedAlt);
        setRevealResult(res.correct);

        if (res.correct) {
            sndCorrect.play();
            // avança para próxima após pequeno delay
            setTimeout(() => {
                advance(true);
            }, 1000);
        } else {
            sndWrong.play();
            // fim de jogo após pequeno delay
            setTimeout(() => {
                alert(`Errou! Você terminou com R$ ${game.score.toLocaleString('pt-BR')}`);
                resetGame();
                navigate('/game/start');
            }, 1200);
        }
    }

    if (isGameOver) {
        return (
            <div className="max-w-2xl mx-auto">
                <HUD/>
                <PlayerHelps
                    onSkip={() => advance(true)}           // pula como se tivesse acertado
                    onFifty={(letters) => {
                        // remove visuais de 2 alternativas erradas
                        const reduced = q?.alternatives.filter(a => !letters.includes(a.letter)) ?? [];
                        setQ(q => q ? { ...q, alternatives: reduced } : q);
                    }}
                />
                <div className="bg-green-50 border border-green-200 p-6 rounded">
                    <h2 className="text-2xl font-bold mb-2">Parabéns! 🏆</h2>
                    <p>Você venceu o jogo e acumulou <strong>R$ {game.score.toLocaleString('pt-BR')}</strong>.</p>
                    <button onClick={() => {
                        resetGame();
                        navigate('/game/start');
                    }} className="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
                        Jogar novamente
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-3xl mx-auto">
            <HUD/>

            {!q ? (
                <Loader message="Carregando pergunta..."/>
            ) : (
                <>
                    <div className="bg-white rounded-xl shadow p-6 space-y-4">
                        <h3 className="text-xl font-semibold">{q.statement}</h3>

                        {/* opções do jogador */}
                        <PlayerOptions
                            onUseFifty={() => {
                                // oculta 2 incorretas
                                const buttons = document.querySelectorAll('button[data-letter]');
                                let removed = 0;
                                buttons.forEach((btn) => {
                                    if (removed >= 2) return;
                                    if (!btn.classList.contains('is-correct')) {
                                        (btn as HTMLButtonElement).disabled = true;
                                        (btn as HTMLButtonElement).style.opacity = '0.5';
                                        removed++;
                                    }
                                });
                            }}
                            onSkip={() => {
                                advance(true);
                            }}
                            onAskAudience={() => setAudienceOpen(true)}
                            disabled={revealResult !== null}
                        />

                        <div className="grid gap-3">
                            {q.alternatives.map((a) => {
                                const isPicked = pickedAlt === a.id;
                                let cls =
                                    'border rounded p-3 text-left cursor-pointer flex justify-between items-center';
                                if (isPicked) cls += ' border-blue-600 ring-2 ring-blue-300';
                                return (
                                    <button
                                        key={a.id}
                                        data-letter={a.letter}
                                        onClick={() => {
                                            setPickedAlt(a.id);
                                            setShowAskBtn(true);
                                            setRevealResult(null);
                                        }}
                                        className={cls}
                                    >
                  <span>
                    <span className="font-bold mr-2">{a.letter})</span> {a.text}
                  </span>
                                    </button>
                                );
                            })}
                        </div>

                        {showAskBtn && (
                            <div className="pt-2">
                                <button
                                    onClick={onAsk}
                                    className="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded"
                                >
                                    Posso perguntar?
                                </button>
                            </div>
                        )}

                        {revealResult !== null && (
                            <div
                                className={`mt-3 p-3 rounded ${
                                    revealResult
                                        ? 'bg-green-50 text-green-700 border border-green-200'
                                        : 'bg-red-50 text-red-700 border border-red-200'
                                }`}
                            >
                                {revealResult ? 'Resposta correta! 🎉' : 'Resposta incorreta 😢'}
                            </div>
                        )}
                    </div>

                    <ModalSuspense open={modal} onReveal={onReveal}/>
                    <ModalAudience
                        open={audienceOpen}
                        alternatives={q.alternatives}
                        onClose={() => setAudienceOpen(false)}
                    />
                </>
            )}
        </div>
    );
}
