import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { AdminLayout } from '@/components/admin/AdminLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Plus, Pencil, Trash2, Trophy } from 'lucide-react';
import { getDifficulties, saveDifficulty, updateDifficulty, deleteDifficulty, isAdminAuthenticated, type Difficulty } from '@/lib/storage';
import { useToast } from '@/hooks/use-toast';

export default function Difficulties() {
  const [difficulties, setDifficulties] = useState<Difficulty[]>([]);
  const [isDialogOpen, setIsDialogOpen] = useState(false);
  const [editingDifficulty, setEditingDifficulty] = useState<Difficulty | null>(null);
  const [name, setName] = useState('');
  const [prize, setPrize] = useState('');
  const [order, setOrder] = useState('');
  const navigate = useNavigate();
  const { toast } = useToast();

  useEffect(() => {
    if (!isAdminAuthenticated()) {
      navigate('/admin/login');
      return;
    }
    loadDifficulties();
  }, [navigate]);

  const loadDifficulties = () => {
    setDifficulties(getDifficulties());
  };

  const handleOpenDialog = (difficulty?: Difficulty) => {
    if (difficulty) {
      setEditingDifficulty(difficulty);
      setName(difficulty.name);
      setPrize(difficulty.prize.toString());
      setOrder(difficulty.order.toString());
    } else {
      setEditingDifficulty(null);
      setName('');
      setPrize('');
      setOrder('');
    }
    setIsDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!name.trim() || !prize || !order) {
      toast({
        title: 'Campos obrigatórios',
        description: 'Preencha todos os campos.',
        variant: 'destructive',
      });
      return;
    }

    const difficultyData = {
      name,
      prize: Number(prize),
      order: Number(order),
    };

    if (editingDifficulty) {
      updateDifficulty(editingDifficulty.id, difficultyData);
      toast({
        title: 'Dificuldade atualizada!',
        description: 'A dificuldade foi atualizada com sucesso.',
      });
    } else {
      saveDifficulty(difficultyData);
      toast({
        title: 'Dificuldade criada!',
        description: 'A dificuldade foi criada com sucesso.',
      });
    }

    setIsDialogOpen(false);
    loadDifficulties();
  };

  const handleDelete = (id: string) => {
    if (confirm('Tem certeza que deseja excluir esta dificuldade?')) {
      if (deleteDifficulty(id)) {
        toast({
          title: 'Dificuldade excluída!',
          description: 'A dificuldade foi removida com sucesso.',
        });
        loadDifficulties();
      }
    }
  };

  const formatPrize = (value: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    }).format(value);
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gold">Dificuldades</h1>
            <p className="text-gold-light mt-1">Gerencie os níveis de dificuldade e premiações</p>
          </div>
          <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
            <DialogTrigger asChild>
              <Button
                onClick={() => handleOpenDialog()}
                className="bg-gradient-to-r from-gold-dark to-gold hover:from-gold to-gold-light text-black font-bold"
              >
                <Plus className="w-4 h-4 mr-2" />
                Nova Dificuldade
              </Button>
            </DialogTrigger>
            <DialogContent className="bg-slate-900 border-gold/30">
              <DialogHeader>
                <DialogTitle className="text-gold">
                  {editingDifficulty ? 'Editar Dificuldade' : 'Nova Dificuldade'}
                </DialogTitle>
                <DialogDescription className="text-gold-light">
                  {editingDifficulty ? 'Atualize as informações da dificuldade' : 'Cadastre uma nova dificuldade'}
                </DialogDescription>
              </DialogHeader>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="name" className="text-gold-light">
                    Nome *
                  </Label>
                  <Input
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Ex: Nível 1"
                    className="bg-black/40 border-gold/30 text-white"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="prize" className="text-gold-light">
                    Premiação (R$) *
                  </Label>
                  <Input
                    id="prize"
                    type="number"
                    value={prize}
                    onChange={(e) => setPrize(e.target.value)}
                    placeholder="Ex: 1000"
                    className="bg-black/40 border-gold/30 text-white"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="order" className="text-gold-light">
                    Ordem *
                  </Label>
                  <Input
                    id="order"
                    type="number"
                    value={order}
                    onChange={(e) => setOrder(e.target.value)}
                    placeholder="Ex: 1"
                    className="bg-black/40 border-gold/30 text-white"
                    required
                  />
                </div>
                <div className="flex justify-end gap-2">
                  <Button
                    type="button"
                    onClick={() => setIsDialogOpen(false)}
                    variant="outline"
                    className="border-gold/30 text-gold hover:bg-gold/10"
                  >
                    Cancelar
                  </Button>
                  <Button
                    type="submit"
                    className="bg-gradient-to-r from-gold-dark to-gold hover:from-gold to-gold-light text-black font-bold"
                  >
                    {editingDifficulty ? 'Atualizar' : 'Criar'}
                  </Button>
                </div>
              </form>
            </DialogContent>
          </Dialog>
        </div>

        <div className="grid gap-4">
          {difficulties.map((difficulty) => (
            <Card key={difficulty.id} className="bg-black/40 backdrop-blur-sm border-gold/20 hover:border-gold/40 transition-all">
              <CardContent className="p-6">
                <div className="flex items-center justify-between gap-4">
                  <div className="flex items-center gap-4 flex-1">
                    <div className="w-12 h-12 rounded-full bg-gradient-to-br from-gold-dark to-gold flex items-center justify-center">
                      <Trophy className="w-6 h-6 text-black" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg font-semibold text-white">
                        {difficulty.name}
                      </h3>
                      <p className="text-2xl font-bold text-gold mt-1">
                        {formatPrize(difficulty.prize)}
                      </p>
                    </div>
                    <div className="text-center px-4">
                      <p className="text-sm text-gold-light">Ordem</p>
                      <p className="text-xl font-bold text-gold">{difficulty.order}</p>
                    </div>
                  </div>
                  <div className="flex gap-2">
                    <Button
                      onClick={() => handleOpenDialog(difficulty)}
                      variant="outline"
                      size="icon"
                      className="border-gold/30 text-gold hover:bg-gold/10"
                    >
                      <Pencil className="w-4 h-4" />
                    </Button>
                    <Button
                      onClick={() => handleDelete(difficulty.id)}
                      variant="outline"
                      size="icon"
                      className="border-red-500/30 text-red-400 hover:bg-red-500/10"
                    >
                      <Trash2 className="w-4 h-4" />
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
