import { Dialog, DialogContent } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { HelpCircle } from "lucide-react";

interface SuspenseModalProps {
  open: boolean;
  onReveal: () => void;
}

export function SuspenseModal({ open, onReveal }: SuspenseModalProps) {
  return (
    <Dialog open={open}>
      <DialogContent className="max-w-2xl border-2 border-primary/50 bg-gradient-to-br from-background via-primary/5 to-background">
        <div className="flex flex-col items-center justify-center py-12 space-y-8">
          <div className="relative">
            <HelpCircle className="w-32 h-32 text-primary animate-pulse-gold" />
            <div className="absolute inset-0 bg-primary/20 blur-3xl animate-pulse" />
          </div>
          
          <div className="text-center space-y-4">
            <h2 className="text-4xl font-bold text-primary animate-pulse-gold">
              Será que acertou?
            </h2>
            <p className="text-xl text-muted-foreground">
              Prepare-se para descobrir...
            </p>
          </div>

          <Button
            onClick={onReveal}
            size="lg"
            className="h-16 px-12 text-xl font-bold bg-gradient-gold hover:opacity-90 shadow-gold animate-pulse-gold"
          >
            Ver Resposta
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  );
}
