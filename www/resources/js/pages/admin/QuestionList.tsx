import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { AdminLayout } from '@/components/admin/AdminLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Pencil, Trash2, Search } from 'lucide-react';
import { getQuestions, getCategories, getDifficulties, deleteQuestion, isAdminAuthenticated, type Question } from '@/lib/storage';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';

export default function QuestionList() {
  const [questions, setQuestions] = useState<Question[]>([]);
  const [filteredQuestions, setFilteredQuestions] = useState<Question[]>([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [categories, setCategories] = useState<Record<string, string>>({});
  const [difficulties, setDifficulties] = useState<Record<string, string>>({});
  const navigate = useNavigate();
  const { toast } = useToast();

  useEffect(() => {
    if (!isAdminAuthenticated()) {
      navigate('/admin/login');
      return;
    }

    loadData();
  }, [navigate]);

  useEffect(() => {
    if (searchTerm) {
      setFilteredQuestions(
        questions.filter(q =>
          q.question.toLowerCase().includes(searchTerm.toLowerCase())
        )
      );
    } else {
      setFilteredQuestions(questions);
    }
  }, [searchTerm, questions]);

  const loadData = () => {
    const q = getQuestions();
    setQuestions(q);
    setFilteredQuestions(q);

    const cats = getCategories();
    const catMap: Record<string, string> = {};
    cats.forEach(c => catMap[c.id] = c.name);
    setCategories(catMap);

    const diffs = getDifficulties();
    const diffMap: Record<string, string> = {};
    diffs.forEach(d => diffMap[d.id] = d.name);
    setDifficulties(diffMap);
  };

  const handleDelete = (id: string) => {
    if (confirm('Tem certeza que deseja excluir esta pergunta?')) {
      if (deleteQuestion(id)) {
        toast({
          title: 'Pergunta excluída!',
          description: 'A pergunta foi removida com sucesso.',
        });
        loadData();
      }
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gold">Perguntas</h1>
            <p className="text-gold-light mt-1">Gerencie as perguntas do jogo</p>
          </div>
          <Button
            onClick={() => navigate('/admin/questions/new')}
            className="bg-gradient-to-r from-gold-dark to-gold hover:from-gold to-gold-light text-black font-bold"
          >
            <Plus className="w-4 h-4 mr-2" />
            Nova Pergunta
          </Button>
        </div>

        <Card className="bg-black/40 backdrop-blur-sm border-gold/20">
          <CardHeader>
            <CardTitle className="text-gold">Buscar</CardTitle>
            <CardDescription className="text-gold-light">
              Encontre perguntas específicas
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gold-light" />
              <Input
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Buscar por texto da pergunta..."
                className="pl-10 bg-black/40 border-gold/30 text-white placeholder:text-gray-500"
              />
            </div>
          </CardContent>
        </Card>

        <div className="grid gap-4">
          {filteredQuestions.length === 0 ? (
            <Card className="bg-black/40 backdrop-blur-sm border-gold/20">
              <CardContent className="py-12 text-center">
                <p className="text-gold-light">
                  {searchTerm ? 'Nenhuma pergunta encontrada.' : 'Nenhuma pergunta cadastrada ainda.'}
                </p>
              </CardContent>
            </Card>
          ) : (
            filteredQuestions.map((question) => (
              <Card key={question.id} className="bg-black/40 backdrop-blur-sm border-gold/20 hover:border-gold/40 transition-all">
                <CardContent className="p-6">
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1">
                      <h3 className="text-lg font-semibold text-white mb-2">
                        {question.question}
                      </h3>
                      <div className="flex gap-4 text-sm text-gold-light">
                        <span>Categoria: {categories[question.categoryId] || 'N/A'}</span>
                        <span>Dificuldade: {difficulties[question.difficultyId] || 'N/A'}</span>
                      </div>
                      <div className="mt-3 space-y-1">
                        {question.answers.map((answer, idx) => (
                          <div
                            key={idx}
                            className={`text-sm ${
                              answer.isCorrect ? 'text-green-400 font-semibold' : 'text-gray-400'
                            }`}
                          >
                            {String.fromCharCode(65 + idx)}) {answer.text}
                            {answer.isCorrect && ' ✓'}
                          </div>
                        ))}
                      </div>
                    </div>
                    <div className="flex gap-2">
                      <Button
                        onClick={() => navigate(`/admin/questions/edit/${question.id}`)}
                        variant="outline"
                        size="icon"
                        className="border-gold/30 text-gold hover:bg-gold/10"
                      >
                        <Pencil className="w-4 h-4" />
                      </Button>
                      <Button
                        onClick={() => handleDelete(question.id)}
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
            ))
          )}
        </div>
      </div>
    </AdminLayout>
  );
}
