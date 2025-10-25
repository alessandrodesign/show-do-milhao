import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from "@/components/ui/alert-dialog";
import { Card } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";

interface ConfirmLifelineProps {
  open: boolean;
  onConfirm: () => void;
  onCancel: () => void;
  type: "phone" | "audience" | "skip" | "cards";
}

export function ConfirmLifeline({ open, onConfirm, onCancel, type }: ConfirmLifelineProps) {
  const titles = {
    phone: "Ajuda dos Universitários",
    audience: "Ajuda da Plateia",
    skip: "Pular Pergunta",
    cards: "Ajuda das Cartas",
  };

  const descriptions = {
    phone: "Três universitários darão suas opiniões sobre a resposta correta.",
    audience: "A plateia votará na alternativa que acha correta.",
    skip: "Esta pergunta será trocada por outra de mesmo valor.",
    cards: "Escolha uma carta que revelará uma dica sobre a resposta.",
  };

  return (
    <AlertDialog open={open}>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>{titles[type]}</AlertDialogTitle>
          <AlertDialogDescription>{descriptions[type]}</AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel onClick={onCancel}>Cancelar</AlertDialogCancel>
          <AlertDialogAction onClick={onConfirm} className="bg-gradient-gold">
            Confirmar
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}

interface PhoneResultProps {
  open: boolean;
  onClose: () => void;
  opinions: Array<{ name: string; answer: string; confidence: string }>;
}

export function PhoneResult({ open, onClose, opinions }: PhoneResultProps) {
  return (
    <AlertDialog open={open}>
      <AlertDialogContent className="max-w-2xl">
        <AlertDialogHeader>
          <AlertDialogTitle className="text-2xl">Opinião dos Universitários</AlertDialogTitle>
          <AlertDialogDescription>
            Veja o que cada universitário pensa sobre a resposta
          </AlertDialogDescription>
        </AlertDialogHeader>
        
        <div className="space-y-4 py-4">
          {opinions.map((opinion, index) => (
            <Card key={index} className="p-4">
              <div className="space-y-2">
                <p className="font-semibold text-lg">{opinion.name}</p>
                <p className="text-primary font-bold text-xl">Resposta: {opinion.answer}</p>
                <p className="text-sm text-muted-foreground">{opinion.confidence}</p>
              </div>
            </Card>
          ))}
        </div>

        <AlertDialogFooter>
          <AlertDialogAction onClick={onClose}>Entendi</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}

interface AudienceResultProps {
  open: boolean;
  onClose: () => void;
  results: Array<{ answer: string; percentage: number }>;
}

export function AudienceResult({ open, onClose, results }: AudienceResultProps) {
  return (
    <AlertDialog open={open}>
      <AlertDialogContent className="max-w-2xl">
        <AlertDialogHeader>
          <AlertDialogTitle className="text-2xl">Votação da Plateia</AlertDialogTitle>
          <AlertDialogDescription>
            Veja como a plateia votou em cada alternativa
          </AlertDialogDescription>
        </AlertDialogHeader>
        
        <div className="space-y-4 py-4">
          {results.map((result, index) => (
            <div key={index} className="space-y-2">
              <div className="flex items-center justify-between">
                <span className="font-semibold text-lg">Alternativa {result.answer}</span>
                <span className="text-primary font-bold text-xl">{result.percentage}%</span>
              </div>
              <Progress value={result.percentage} className="h-3" />
            </div>
          ))}
        </div>

        <AlertDialogFooter>
          <AlertDialogAction onClick={onClose}>Entendi</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}

interface CardsResultProps {
  open: boolean;
  onSelectCard: (card: string) => void;
}

export function CardsResult({ open, onSelectCard }: CardsResultProps) {
  const cards = ["A", "B", "C", "D"];

  return (
    <AlertDialog open={open}>
      <AlertDialogContent className="max-w-2xl">
        <AlertDialogHeader>
          <AlertDialogTitle className="text-2xl">Escolha uma Carta</AlertDialogTitle>
          <AlertDialogDescription>
            Cada carta revelará uma dica sobre a resposta correta
          </AlertDialogDescription>
        </AlertDialogHeader>
        
        <div className="grid grid-cols-4 gap-4 py-8">
          {cards.map((card) => (
            <button
              key={card}
              onClick={() => onSelectCard(card)}
              className="aspect-[3/4] rounded-xl border-2 border-primary/30 bg-gradient-gold hover:scale-105 transition-transform flex items-center justify-center text-6xl font-bold text-primary-foreground shadow-gold"
            >
              {card}
            </button>
          ))}
        </div>
      </AlertDialogContent>
    </AlertDialog>
  );
}

interface CardHintProps {
  open: boolean;
  onClose: () => void;
  hint: string;
}

export function CardHint({ open, onClose, hint }: CardHintProps) {
  return (
    <AlertDialog open={open}>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle className="text-2xl">Dica da Carta</AlertDialogTitle>
        </AlertDialogHeader>
        
        <div className="py-6">
          <p className="text-lg text-center font-medium">{hint}</p>
        </div>

        <AlertDialogFooter>
          <AlertDialogAction onClick={onClose}>Entendi</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}
