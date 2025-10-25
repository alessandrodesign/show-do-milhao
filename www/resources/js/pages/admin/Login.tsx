import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { loginAdmin, isAdminAuthenticated } from '@/lib/storage';
import { useToast } from '@/hooks/use-toast';

export default function AdminLogin() {
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const { toast } = useToast();

  useEffect(() => {
    if (isAdminAuthenticated()) {
      navigate('/admin/questions');
    }
  }, [navigate]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    setTimeout(() => {
      if (loginAdmin(password)) {
        toast({
          title: 'Login realizado!',
          description: 'Bem-vindo ao painel administrativo.',
        });
        navigate('/admin/questions');
      } else {
        toast({
          title: 'Erro ao fazer login',
          description: 'Senha incorreta. Tente novamente.',
          variant: 'destructive',
        });
      }
      setLoading(false);
    }, 500);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4">
      <Card className="w-full max-w-md bg-black/60 backdrop-blur-sm border-gold/30">
        <CardHeader className="text-center">
          <CardTitle className="text-3xl font-bold text-gold">Admin Panel</CardTitle>
          <CardDescription className="text-gold-light">
            Entre com sua senha para acessar
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="password" className="text-gold-light">
                Senha
              </Label>
              <Input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Digite sua senha"
                className="bg-black/40 border-gold/30 text-white placeholder:text-gray-500"
                required
              />
            </div>
            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-gold-dark to-gold hover:from-gold to-gold-light text-black font-bold"
              disabled={loading}
            >
              {loading ? 'Entrando...' : 'Entrar'}
            </Button>
            <p className="text-xs text-center text-gold-light/60 mt-4">
              Senha padrão: admin123
            </p>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}
