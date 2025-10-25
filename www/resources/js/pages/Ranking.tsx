import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { useNavigate } from "react-router-dom";
import { Trophy, Medal, Award, Crown } from "lucide-react";

export default function Ranking() {
  const navigate = useNavigate();

  // Mock data - será substituído por dados da API
  const rankings = [
    { position: 1, name: "Ana Silva", prize: 1000000, correctAnswers: 16, date: "23/10/2025" },
    { position: 2, name: "Carlos Santos", prize: 750000, correctAnswers: 15, date: "22/10/2025" },
    { position: 3, name: "Maria Oliveira", prize: 500000, correctAnswers: 14, date: "21/10/2025" },
    { position: 4, name: "João Pedro", prize: 400000, correctAnswers: 13, date: "20/10/2025" },
    { position: 5, name: "Beatriz Costa", prize: 300000, correctAnswers: 12, date: "19/10/2025" },
    { position: 6, name: "Rafael Alves", prize: 200000, correctAnswers: 11, date: "18/10/2025" },
    { position: 7, name: "Juliana Lima", prize: 100000, correctAnswers: 10, date: "17/10/2025" },
    { position: 8, name: "Pedro Henrique", prize: 50000, correctAnswers: 9, date: "16/10/2025" },
    { position: 9, name: "Camila Souza", prize: 30000, correctAnswers: 8, date: "15/10/2025" },
    { position: 10, name: "Lucas Martins", prize: 20000, correctAnswers: 7, date: "14/10/2025" },
  ];

  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
      minimumFractionDigits: 0,
    }).format(value);
  };

  const getPositionIcon = (position: number) => {
    switch (position) {
      case 1:
        return <Crown className="w-6 h-6 text-yellow-500" />;
      case 2:
        return <Medal className="w-6 h-6 text-gray-400" />;
      case 3:
        return <Award className="w-6 h-6 text-orange-600" />;
      default:
        return <Trophy className="w-5 h-5 text-primary" />;
    }
  };

  return (
    <div className="min-h-screen bg-gradient-stage p-4">
      <div className="container mx-auto max-w-5xl py-8 space-y-6">
        {/* Header */}
        <Card className="p-8 border-2 border-primary/30 bg-card/95 backdrop-blur text-center">
          <Trophy className="w-16 h-16 mx-auto mb-4 text-primary animate-pulse-gold" />
          <h1 className="text-4xl font-bold text-primary mb-2">Ranking</h1>
          <p className="text-lg text-muted-foreground">
            Os maiores campeões do Show do Milhão
          </p>
        </Card>

        {/* Ranking List */}
        <Card className="p-6 border-2 border-primary/30 bg-card/95 backdrop-blur">
          <div className="space-y-3">
            {rankings.map((player) => (
              <div
                key={player.position}
                className={`
                  flex items-center gap-4 p-4 rounded-lg transition-all hover:scale-[1.02]
                  ${
                    player.position === 1
                      ? "bg-gradient-gold shadow-gold"
                      : player.position === 2
                      ? "bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700"
                      : player.position === 3
                      ? "bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20"
                      : "bg-muted/50 border border-border"
                  }
                `}
              >
                {/* Posição */}
                <div className="flex items-center justify-center w-12 h-12 rounded-full bg-background/50">
                  {getPositionIcon(player.position)}
                </div>

                {/* Nome */}
                <div className="flex-1 min-w-0">
                  <p className="font-bold text-lg truncate">{player.name}</p>
                  <p className="text-sm text-muted-foreground">
                    {player.correctAnswers} acertos • {player.date}
                  </p>
                </div>

                {/* Prêmio */}
                <div className="text-right">
                  <p className="font-bold text-xl text-primary">
                    {formatCurrency(player.prize)}
                  </p>
                  <p className="text-xs text-muted-foreground">
                    #{player.position}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </Card>

        {/* Ações */}
        <div className="flex justify-center">
          <Button
            onClick={() => navigate("/")}
            size="lg"
            className="h-14 px-12 text-lg font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
          >
            Novo Jogo
          </Button>
        </div>
      </div>
    </div>
  );
}
