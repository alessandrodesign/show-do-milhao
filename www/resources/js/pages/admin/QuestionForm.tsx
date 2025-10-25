import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { AdminLayout } from '@/components/admin/AdminLayout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { getCategories, getDifficulties, saveQuestion, updateQuestion, getQuestion, isAdminAuthenticated } from '@/lib/storage';
import { useToast } from '@/hooks/use-toast';
import { ArrowLeft } from 'lucide-react';

export default function QuestionForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { toast } = useToast();
  const isEdit = !!id;

  const [question, setQuestion] = useState('');
  const [categoryId, setCategoryId] = useState('');
  const [difficultyId, setDifficultyId] = useState('');
  const [hint, setHint] = useState('');
  const [answers, setAnswers] = useState([
    { text: '', isCorrect: false },
    { text: '', isCorrect: false },
    { text: '', isCorrect: false },
    { text: '', isCorrect: false },
  ]);

  const [categories, setCategories] = useState<Array<{ id: string; name: string }>>([]);
  const [difficulties, setDifficulties] = useState<Array<{ id: string; name: string }>>([]);

  useEffect(() => {
    if (!isAdminAuthenticated()) {
      navigate('/admin/login');
      return;
    }

    setCategories(getCategories());
    setDifficulties(getDifficulties());

    if (isEdit && id) {
      const q = getQuestion(id);
      if (q) {
        setQuestion(q.question);
        setCategoryId(q.categoryId);
        setDifficultyId(q.difficultyId);
        setHint(q.hint || '');
        setAnswers(q.answers.map(a => ({ text: a.text, isCorrect: a.isCorrect })));
      }
    }
  }, [navigate, isEdit, id]);

  const handleAnswerChange = (index: number, text: string) => {
    const newAnswers = [...answers];
    newAnswers[index].text = text;
    setAnswers(newAnswers);
  };

  const handleCorrectChange = (index: number) => {
    const newAnswers = answers.map((a, i) => ({
      ...a,
      isCorrect: i === index,
    }));
    setAnswers(newAnswers);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!question.trim() || !categoryId || !difficultyId) {
      toast({
        title: 'Campos obrigatórios',
        description: 'Preencha todos os campos obrigatórios.',
        variant: 'destructive',
      });
      return;
    }

    if (answers.some(a => !a.text.trim())) {
      toast({
        title: 'Respostas incompletas',
        description: 'Preencha todas as 4 respostas.',
        variant: 'destructive',
      });
      return;
    }

    if (!answers.some(a => a.isCorrect)) {
      toast({
        title: 'Resposta correta não marcada',
        description: 'Marque qual é a resposta correta.',
        variant: 'destructive',
      });
      return;
    }

    const questionData = {
      question,
      categoryId,
      difficultyId,
      hint: hint.trim() || undefined,
      answers: answers.map((a, i) => ({
        id: (i + 1).toString(),
        text: a.text,
        isCorrect: a.isCorrect,
      })),
    };

    if (isEdit && id) {
      updateQuestion(id, questionData);
      toast({
        title: 'Pergunta atualizada!',
        description: 'A pergunta foi atualizada com sucesso.',
      });
    } else {
      saveQuestion(questionData);
      toast({
        title: 'Pergunta criada!',
        description: 'A pergunta foi criada com sucesso.',
      });
    }

    navigate('/admin/questions');
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Button
            onClick={() => navigate('/admin/questions')}
            variant="outline"
            size="icon"
            className="border-gold/30 text-gold hover:bg-gold/10"
          >
            <ArrowLeft className="w-4 h-4" />
          </Button>
          <div>
            <h1 className="text-3xl font-bold text-gold">
              {isEdit ? 'Editar Pergunta' : 'Nova Pergunta'}
            </h1>
            <p className="text-gold-light mt-1">
              {isEdit ? 'Atualize as informações da pergunta' : 'Cadastre uma nova pergunta'}
            </p>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          <Card className="bg-black/40 backdrop-blur-sm border-gold/20">
            <CardHeader>
              <CardTitle className="text-gold">Informações da Pergunta</CardTitle>
              <CardDescription className="text-gold-light">
                Preencha os detalhes da pergunta
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="question" className="text-gold-light">
                  Pergunta *
                </Label>
                <Textarea
                  id="question"
                  value={question}
                  onChange={(e) => setQuestion(e.target.value)}
                  placeholder="Digite a pergunta..."
                  className="bg-black/40 border-gold/30 text-white placeholder:text-gray-500 min-h-[100px]"
                  required
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="category" className="text-gold-light">
                    Categoria *
                  </Label>
                  <Select value={categoryId} onValueChange={setCategoryId}>
                    <SelectTrigger className="bg-black/40 border-gold/30 text-white">
                      <SelectValue placeholder="Selecione..." />
                    </SelectTrigger>
                    <SelectContent>
                      {categories.map((cat) => (
                        <SelectItem key={cat.id} value={cat.id}>
                          {cat.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="difficulty" className="text-gold-light">
                    Dificuldade *
                  </Label>
                  <Select value={difficultyId} onValueChange={setDifficultyId}>
                    <SelectTrigger className="bg-black/40 border-gold/30 text-white">
                      <SelectValue placeholder="Selecione..." />
                    </SelectTrigger>
                    <SelectContent>
                      {difficulties.map((diff) => (
                        <SelectItem key={diff.id} value={diff.id}>
                          {diff.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="hint" className="text-gold-light">
                  Dica (Opcional)
                </Label>
                <Input
                  id="hint"
                  value={hint}
                  onChange={(e) => setHint(e.target.value)}
                  placeholder="Digite uma dica..."
                  className="bg-black/40 border-gold/30 text-white placeholder:text-gray-500"
                />
              </div>
            </CardContent>
          </Card>

          <Card className="bg-black/40 backdrop-blur-sm border-gold/20">
            <CardHeader>
              <CardTitle className="text-gold">Respostas</CardTitle>
              <CardDescription className="text-gold-light">
                Cadastre as 4 respostas e marque a correta
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              {answers.map((answer, index) => (
                <div key={index} className="flex items-start gap-3">
                  <div className="flex items-center h-10">
                    <Checkbox
                      checked={answer.isCorrect}
                      onCheckedChange={() => handleCorrectChange(index)}
                      className="border-gold/50 data-[state=checked]:bg-gold data-[state=checked]:border-gold"
                    />
                  </div>
                  <div className="flex-1 space-y-2">
                    <Label className="text-gold-light">
                      Resposta {String.fromCharCode(65 + index)} *
                    </Label>
                    <Input
                      value={answer.text}
                      onChange={(e) => handleAnswerChange(index, e.target.value)}
                      placeholder={`Digite a resposta ${String.fromCharCode(65 + index)}...`}
                      className="bg-black/40 border-gold/30 text-white placeholder:text-gray-500"
                      required
                    />
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>

          <div className="flex justify-end gap-4">
            <Button
              type="button"
              onClick={() => navigate('/admin/questions')}
              variant="outline"
              className="border-gold/30 text-gold hover:bg-gold/10"
            >
              Cancelar
            </Button>
            <Button
              type="submit"
              className="bg-gradient-to-r from-gold-dark to-gold hover:from-gold to-gold-light text-black font-bold"
            >
              {isEdit ? 'Atualizar' : 'Criar'} Pergunta
            </Button>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}
