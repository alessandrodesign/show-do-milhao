import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { useNavigate } from "react-router-dom";
import { Trophy, CheckCircle, XCircle, LifeBuoy } from "lucide-react";

export default function GameOver() {
  const navigate = useNavigate();

  // Mock data - será substituído por props/context
  const gameStats = {
    playerName: "Jogador",
    totalQuestions: 10,
    correctAnswers: 9,
    wrongAnswers: 1,
    finalPrize: 100000,
    lifelinesUsed: 2,
    duration: "15:42",
  };

  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
      minimumFractionDigits: 0,
    }).format(value);
  };

  return (
    <div className="min-h-screen bg-gradient-stage flex items-center justify-center p-4">
      <Card className="max-w-2xl w-full p-8 border-2 border-primary/30 bg-card/95 backdrop-blur shadow-glow">
        <div className="space-y-8">
          {/* Header */}
          <div className="text-center space-y-4">
            <Trophy className="w-24 h-24 mx-auto text-primary animate-pulse-gold" />
            <h1 className="text-4xl font-bold text-primary">Fim de Jogo</h1>
            <p className="text-2xl font-semibold">{gameStats.playerName}</p>
          </div>

          {/* Prêmio Final */}
          <div className="text-center p-6 rounded-xl bg-gradient-gold">
            <p className="text-lg text-primary-foreground/80 mb-2">Você levou</p>
            <p className="text-5xl font-bold text-primary-foreground">
              {formatCurrency(gameStats.finalPrize)}
            </p>
          </div>

          {/* Estatísticas */}
          <div className="grid grid-cols-2 gap-4">
            <Card className="p-4 border-primary/20">
              <div className="flex items-center gap-3">
                <CheckCircle className="w-8 h-8 text-green-500" />
                <div>
                  <p className="text-sm text-muted-foreground">Acertos</p>
                  <p className="text-2xl font-bold text-green-500">
                    {gameStats.correctAnswers}
                  </p>
                </div>
              </div>
            </Card>

            <Card className="p-4 border-primary/20">
              <div className="flex items-center gap-3">
                <XCircle className="w-8 h-8 text-destructive" />
                <div>
                  <p className="text-sm text-muted-foreground">Erros</p>
                  <p className="text-2xl font-bold text-destructive">
                    {gameStats.wrongAnswers}
                  </p>
                </div>
              </div>
            </Card>

            <Card className="p-4 border-primary/20">
              <div className="flex items-center gap-3">
                <LifeBuoy className="w-8 h-8 text-primary" />
                <div>
                  <p className="text-sm text-muted-foreground">Ajudas Usadas</p>
                  <p className="text-2xl font-bold text-primary">
                    {gameStats.lifelinesUsed}
                  </p>
                </div>
              </div>
            </Card>

            <Card className="p-4 border-primary/20">
              <div className="flex items-center gap-3">
                <Trophy className="w-8 h-8 text-primary" />
                <div>
                  <p className="text-sm text-muted-foreground">Tempo</p>
                  <p className="text-2xl font-bold text-primary">
                    {gameStats.duration}
                  </p>
                </div>
              </div>
            </Card>
          </div>

          {/* Ações */}
          <div className="flex flex-col sm:flex-row gap-4">
            <Button
              onClick={() => navigate("/ranking")}
              variant="outline"
              size="lg"
              className="flex-1 h-14 text-lg font-semibold"
            >
              Ver Ranking
            </Button>
            <Button
              onClick={() => navigate("/")}
              size="lg"
              className="flex-1 h-14 text-lg font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
            >
              Novo Jogo
            </Button>
          </div>
        </div>
      </Card>
    </div>
  );
}
