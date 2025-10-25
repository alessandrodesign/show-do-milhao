import { Dialog, DialogContent } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { CheckCircle2, XCircle } from "lucide-react";
import { Confetti } from "./Confetti";

interface ResultModalProps {
  open: boolean;
  isCorrect: boolean;
  onContinue: () => void;
  onStop: () => void;
  nextPrize?: number;
  currentPrize: number;
  isFinalQuestion?: boolean;
}

export function ResultModal({
  open,
  isCorrect,
  onContinue,
  onStop,
  nextPrize,
  currentPrize,
  isFinalQuestion = false,
}: ResultModalProps) {
  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
      minimumFractionDigits: 0,
    }).format(value);
  };

  if (!isCorrect) {
    return (
      <Dialog open={open}>
        <DialogContent className="max-w-2xl border-2 border-destructive/50 bg-gradient-to-br from-background via-destructive/5 to-background">
          <div className="flex flex-col items-center justify-center py-12 space-y-8">
            <div className="relative">
              <XCircle className="w-32 h-32 text-destructive animate-pulse" />
              <div className="absolute inset-0 bg-destructive/20 blur-3xl animate-pulse" />
            </div>
            
            <div className="text-center space-y-4">
              <h2 className="text-5xl font-bold text-destructive animate-pulse">
                Errrrooouuuu!
              </h2>
              <p className="text-2xl text-muted-foreground">
                Você levou {formatCurrency(currentPrize)}
              </p>
            </div>

            <Button
              onClick={onStop}
              size="lg"
              className="h-16 px-12 text-xl font-bold"
            >
              Ver Resumo
            </Button>
          </div>
        </DialogContent>
      </Dialog>
    );
  }

  if (isFinalQuestion) {
    return (
      <>
        <Confetti active={open} />
        <Dialog open={open}>
          <DialogContent className="max-w-2xl border-2 border-primary/50 bg-gradient-to-br from-background via-primary/5 to-background">
            <div className="flex flex-col items-center justify-center py-12 space-y-8">
              <div className="relative">
                <CheckCircle2 className="w-32 h-32 text-primary animate-pulse-gold" />
                <div className="absolute inset-0 bg-primary/30 blur-3xl animate-pulse" />
              </div>
              
              <div className="text-center space-y-4">
                <h2 className="text-5xl font-bold text-primary animate-pulse-gold">
                  Parabéns!
                </h2>
                <p className="text-3xl font-bold bg-gradient-gold bg-clip-text text-transparent">
                  Você ganhou {formatCurrency(currentPrize)}!
                </p>
                <p className="text-xl text-muted-foreground">
                  Você é um campeão!
                </p>
              </div>

              <Button
                onClick={onContinue}
                size="lg"
                className="h-16 px-12 text-xl font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
              >
                Continuar
              </Button>
            </div>
          </DialogContent>
        </Dialog>
      </>
    );
  }

  return (
    <>
      <Confetti active={open} />
      <Dialog open={open}>
        <DialogContent className="max-w-2xl border-2 border-primary/50 bg-gradient-to-br from-background via-primary/5 to-background">
          <div className="flex flex-col items-center justify-center py-12 space-y-8">
            <div className="relative">
              <CheckCircle2 className="w-32 h-32 text-primary animate-pulse-gold" />
              <div className="absolute inset-0 bg-primary/30 blur-3xl animate-pulse" />
            </div>
            
            <div className="text-center space-y-4">
              <h2 className="text-5xl font-bold text-primary animate-pulse-gold">
                Certa resposta!
              </h2>
              <p className="text-2xl text-muted-foreground">
                Você ganhou {formatCurrency(currentPrize)}
              </p>
            </div>

            <div className="text-center space-y-2">
              <p className="text-lg text-muted-foreground">Próxima pergunta vale</p>
              <p className="text-4xl font-bold bg-gradient-gold bg-clip-text text-transparent">
                {formatCurrency(nextPrize || 0)}
              </p>
            </div>

            <div className="flex gap-4">
              <Button
                onClick={onStop}
                variant="outline"
                size="lg"
                className="h-16 px-8 text-lg font-semibold"
              >
                Parar e Levar {formatCurrency(currentPrize)}
              </Button>
              <Button
                onClick={onContinue}
                size="lg"
                className="h-16 px-12 text-xl font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
              >
                Continuar
              </Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
}
