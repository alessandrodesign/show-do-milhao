import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "@/lib/api";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { Loader2, SkipForward, Phone, Users, HelpCircle, Pause } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { SuspenseModal } from "@/components/game/SuspenseModal";
import { ResultModal } from "@/components/game/ResultModal";
import {
    ConfirmLifeline,
    PhoneResult,
    AudienceResult,
    CardsResult,
    CardHint,
} from "@/components/game/LifelineModals";
import { Confetti } from "@/components/game/Confetti";

export default function GamePlay() {
    const navigate = useNavigate();
    const { toast } = useToast();

    const [game, setGame] = useState<any>(null);
    const [question, setQuestion] = useState<any>(null);
    const [selectedAnswer, setSelectedAnswer] = useState<number | null>(null);
    const [loading, setLoading] = useState(false);

    // Suspense & resultado
    const [showSuspense, setShowSuspense] = useState(false);
    const [showResult, setShowResult] = useState(false);
    const [isCorrect, setIsCorrect] = useState<boolean | null>(null);
    const [nextPrize, setNextPrize] = useState<number>(0);

    // Lifelines
    const [lifelineConfirm, setLifelineConfirm] = useState<null | string>(null);
    const [phoneResult, setPhoneResult] = useState<any>(null);
    const [audienceResult, setAudienceResult] = useState<any>(null);
    const [cardsActive, setCardsActive] = useState(false);
    const [cardHint, setCardHint] = useState<string | null>(null);

    // Confete
    const [showConfetti, setShowConfetti] = useState(false);

    /**
     * Busca a pergunta atual.
     */
    async function fetchQuestion(id: number) {
        setLoading(true);
        try {
            const { data } = await api.get(`/game/${id}/current`);
            setQuestion(data.question);
        } catch {
            toast({ title: "Erro ao buscar pergunta", variant: "destructive" });
        } finally {
            setLoading(false);
        }
    }

    /**
     * Carrega o jogo armazenado e busca a primeira pergunta.
     */
    useEffect(() => {
        const stored = localStorage.getItem("currentGame");
        if (!stored) {
            navigate("/");
            return;
        }

        const data = JSON.parse(stored);
        setGame(data);

        const loadQuestion = async () => {
            try {
                await fetchQuestion(data.session_id);
            } catch (err) {
                console.error("Erro ao buscar pergunta:", err);
            }
        };

        loadQuestion();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    async function selectAnswer(answerId: number) {
        if (!question) return;
        setSelectedAnswer(answerId);
        setShowSuspense(true);

        await api.post(`/game/${game.session_id}/select-answer`, {
            question_id: question.id,
            answer_id: answerId,
        });
    }

    async function revealAnswer() {
        setShowSuspense(false);
        setLoading(true);
        try {
            const { data } = await api.post(`/game/${game.session_id}/reveal`);
            setIsCorrect(data.correct);
            setNextPrize(data.newPrize || 0);
            setShowResult(true);

            if (data.status === "won") {
                setShowConfetti(true);
                setTimeout(() => navigate("/victory"), 3000);
            } else if (data.status === "lost") {
                setTimeout(() => navigate("/game/over"), 2500);
            }
        } catch {
            toast({ title: "Erro ao revelar resultado", variant: "destructive" });
        } finally {
            setLoading(false);
        }
    }

    async function continueGame() {
        setShowResult(false);
        setSelectedAnswer(null);
        setIsCorrect(null);
        await api.post(`/game/${game.session_id}/continue`);
        await fetchQuestion(game.session_id);
    }

    async function stopGame() {
        const { data } = await api.post(`/game/${game.session_id}/stop`);
        localStorage.removeItem("currentGame");
        navigate("/victory", { state: { finalPrize: data.finalPrize } });
    }

    // Lifelines
    async function confirmLifeline(type: string) {
        setLifelineConfirm(null);
        try {
            const { data } = await api.post(`/game/${game.session_id}/lifeline/${type}/confirm`);
            if (type === "phone") {
                setPhoneResult({
                    open: true,
                    opinions: [
                        { name: "Lucas", answer: "A", confidence: "80% de certeza" },
                        { name: "Marina", answer: "C", confidence: "60% de certeza" },
                        { name: "João", answer: "A", confidence: "90% de certeza" },
                    ],
                });
            }
            if (type === "audience") {
                setAudienceResult({
                    open: true,
                    results: [
                        { answer: "A", percentage: 45 },
                        { answer: "B", percentage: 25 },
                        { answer: "C", percentage: 20 },
                        { answer: "D", percentage: 10 },
                    ],
                });
            }
            if (type === "cards") setCardsActive(true);
            if (type === "skip") fetchQuestion(game.session_id);
        } catch {
            toast({ title: "Erro ao usar ajuda", variant: "destructive" });
        }
    }

    function handleCardSelect(card: string) {
        setCardsActive(false);
        setCardHint(`A carta ${card} indica uma alternativa promissora...`);
    }

    if (loading && !question)
        return (
            <div className="flex items-center justify-center min-h-screen">
                <Loader2 className="w-12 h-12 animate-spin text-primary" />
            </div>
        );

    if (!question)
        return (
            <div className="flex items-center justify-center min-h-screen">
                <p className="text-xl text-muted-foreground">Nenhuma pergunta disponível</p>
            </div>
        );

    return (
        <div className="min-h-screen bg-gradient-stage text-center flex flex-col items-center justify-center py-10 px-4">
            <Confetti active={showConfetti} />

            <Card className="max-w-3xl w-full border-2 border-primary/30 shadow-glow">
                <CardHeader>
                    <CardTitle className="flex justify-between text-xl md:text-2xl text-primary font-bold">
                        Pergunta {question.round_number ?? "-"}
                        <span className="text-base text-muted-foreground">
              💰 {(nextPrize || 0).toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL",
                        })}
            </span>
                    </CardTitle>
                </CardHeader>

                <CardContent className="space-y-6">
                    <Progress value={((question.round_number || 1) / 15) * 100} />
                    <h2 className="text-lg md:text-xl font-semibold leading-relaxed">
                        {question.question}
                    </h2>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {question.answers?.map((ans: any, idx: number) => (
                            <Button
                                key={ans.id}
                                onClick={() => selectAnswer(ans.id)}
                                variant={selectedAnswer === ans.id ? "default" : "outline"}
                                disabled={!!selectedAnswer}
                                className={`h-16 text-base md:text-lg font-bold transition-all duration-200 ${
                                    selectedAnswer === ans.id
                                        ? "bg-gradient-gold text-primary-foreground shadow-gold"
                                        : "hover:bg-primary/10"
                                }`}
                            >
                                {String.fromCharCode(65 + idx)}) {ans.text}
                            </Button>
                        ))}
                    </div>

                    <div className="flex flex-wrap gap-3 justify-center mt-6">
                        <Button onClick={() => setLifelineConfirm("skip")} variant="secondary">
                            <SkipForward className="w-4 h-4 mr-1" /> Pular
                        </Button>
                        <Button onClick={() => setLifelineConfirm("phone")} variant="secondary">
                            <Phone className="w-4 h-4 mr-1" /> Universitário
                        </Button>
                        <Button onClick={() => setLifelineConfirm("audience")} variant="secondary">
                            <Users className="w-4 h-4 mr-1" /> Plateia
                        </Button>
                        <Button onClick={() => setLifelineConfirm("cards")} variant="secondary">
                            <HelpCircle className="w-4 h-4 mr-1" /> Cartas
                        </Button>
                        <Button onClick={stopGame} variant="destructive" className="ml-auto">
                            <Pause className="w-4 h-4 mr-1" /> Parar
                        </Button>
                    </div>
                </CardContent>
            </Card>

            {/* Suspense */}
            <SuspenseModal open={showSuspense} onReveal={revealAnswer} />

            {/* Resultado */}
            <ResultModal
                open={showResult}
                isCorrect={!!isCorrect}
                onContinue={continueGame}
                onStop={stopGame}
                nextPrize={nextPrize}
                currentPrize={nextPrize}
                isFinalQuestion={nextPrize >= 1000000}
            />

            {/* Ajudas */}
            <ConfirmLifeline
                open={!!lifelineConfirm}
                onConfirm={() => confirmLifeline(lifelineConfirm!)}
                onCancel={() => setLifelineConfirm(null)}
                type={(lifelineConfirm as any) || "skip"}
            />
            {phoneResult && (
                <PhoneResult
                    open={phoneResult.open}
                    onClose={() => setPhoneResult(null)}
                    opinions={phoneResult.opinions}
                />
            )}
            {audienceResult && (
                <AudienceResult
                    open={audienceResult.open}
                    onClose={() => setAudienceResult(null)}
                    results={audienceResult.results}
                />
            )}
            <CardsResult open={cardsActive} onSelectCard={handleCardSelect} />
            {cardHint && <CardHint open={!!cardHint} onClose={() => setCardHint(null)} hint={cardHint} />}
        </div>
    );
}
