import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { useNavigate } from "react-router-dom";
import { Trophy, Crown, Star } from "lucide-react";
import { Confetti } from "@/components/game/Confetti";

export default function Victory() {
  const navigate = useNavigate();
  const [showConfetti, setShowConfetti] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => setShowConfetti(false), 5000);
    return () => clearTimeout(timer);
  }, []);

  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
      minimumFractionDigits: 0,
    }).format(value);
  };

  return (
    <div className="min-h-screen bg-gradient-stage flex items-center justify-center p-4">
      <Confetti active={showConfetti} />
      
      <Card className="max-w-3xl w-full p-12 border-2 border-primary/30 bg-card/95 backdrop-blur shadow-glow">
        <div className="space-y-8 text-center">
          {/* Ícones Decorativos */}
          <div className="flex justify-center gap-8">
            <Star className="w-16 h-16 text-primary animate-pulse-gold" />
            <Crown className="w-24 h-24 text-primary animate-float" />
            <Star className="w-16 h-16 text-primary animate-pulse-gold" />
          </div>

          {/* Título */}
          <div className="space-y-4">
            <h1 className="text-6xl font-bold bg-gradient-gold bg-clip-text text-transparent animate-pulse-gold">
              PARABÉNS!
            </h1>
            <p className="text-3xl font-bold text-primary">
              Você é um Campeão!
            </p>
          </div>

          {/* Prêmio */}
          <div className="p-8 rounded-2xl bg-gradient-gold shadow-gold">
            <p className="text-xl text-primary-foreground/80 mb-3">
              Você ganhou
            </p>
            <p className="text-7xl font-bold text-primary-foreground">
              {formatCurrency(1000000)}
            </p>
            <div className="flex items-center justify-center gap-3 mt-4">
              <Trophy className="w-10 h-10 text-primary-foreground" />
              <p className="text-2xl font-semibold text-primary-foreground">
                Um Milhão de Reais!
              </p>
              <Trophy className="w-10 h-10 text-primary-foreground" />
            </div>
          </div>

          {/* Mensagem */}
          <div className="space-y-4">
            <p className="text-xl text-muted-foreground">
              Você respondeu todas as 16 perguntas corretamente!
            </p>
            <p className="text-lg text-muted-foreground">
              Uma performance extraordinária digna de um verdadeiro campeão!
            </p>
          </div>

          {/* Ações */}
          <div className="flex flex-col sm:flex-row gap-4 pt-4">
            <Button
              onClick={() => navigate("/ranking")}
              variant="outline"
              size="lg"
              className="flex-1 h-16 text-lg font-semibold"
            >
              Ver Ranking
            </Button>
            <Button
              onClick={() => navigate("/")}
              size="lg"
              className="flex-1 h-16 text-xl font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
            >
              Novo Jogo
            </Button>
          </div>
        </div>
      </Card>
    </div>
  );
}
