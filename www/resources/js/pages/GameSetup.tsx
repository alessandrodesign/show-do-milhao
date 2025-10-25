import {useState, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import {Button} from "@/components/ui/button";
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from "@/components/ui/card";
import {Checkbox} from "@/components/ui/checkbox";
import {RadioGroup, RadioGroupItem} from "@/components/ui/radio-group";
import {Trophy, Sparkles} from "lucide-react";
import api from "@/lib/api";

export default function GameSetup() {
    const navigate = useNavigate();
    const [playerName, setPlayerName] = useState("");
    const [categories, setCategories] = useState<any[]>([]);
    const [selectedCategories, setSelectedCategories] = useState<number[]>([]);
    const [difficultyMode, setDifficultyMode] = useState<"progressive" | "fixed">("progressive");
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        api.get("/game/categories").then(({data}) => setCategories(data.data || []));
    }, []);

    const handleCategoryToggle = (id: number) => {
        setSelectedCategories((prev) =>
            prev.includes(id) ? prev.filter((c) => c !== id) : [...prev, id]
        );
    };

    const handleStartGame = async () => {
        if (!playerName.trim() || selectedCategories.length === 0) return;
        setLoading(true);
        try {
            const {data} = await api.post("/game/start", {
                player_name: playerName,
                selected_categories: selectedCategories,
                mode: difficultyMode,
            });
            localStorage.setItem("currentGame", JSON.stringify(data));
            navigate("/game/play");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gradient-stage flex items-center justify-center p-4">
            <div className="w-full max-w-4xl">
                <div className="text-center mb-8 animate-float">
                    <div
                        className="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-gold mb-4 shadow-gold">
                        <Trophy className="w-12 h-12 text-primary-foreground"/>
                    </div>
                    <h1 className="text-5xl font-bold text-primary mb-2 animate-pulse-gold">
                        Show do Milhão
                    </h1>
                    <p className="text-xl text-muted-foreground">Prepare-se para o desafio!</p>
                </div>

                <Card className="border-2 border-primary/20 shadow-glow">
                    <CardHeader>
                        <CardTitle className="text-2xl flex items-center gap-2">
                            <Sparkles className="w-6 h-6 text-primary"/>
                            Configure sua Partida
                        </CardTitle>
                        <CardDescription>
                            Informe seu nome e selecione as categorias desejadas
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="space-y-2">
                            <Label>Nome do Jogador *</Label>
                            <Input
                                value={playerName}
                                onChange={(e) => setPlayerName(e.target.value)}
                                placeholder="Digite seu nome"
                                className="text-lg h-12"
                            />
                        </div>

                        <div className="space-y-3">
                            <Label>Categorias *</Label>
                            <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                                {categories.map((cat) => (
                                    <div
                                        key={cat.id}
                                        className="flex items-center space-x-2 p-3 rounded-lg border hover:border-primary/50 cursor-pointer"
                                        onClick={() => handleCategoryToggle(cat.id)}
                                    >
                                        <Checkbox checked={selectedCategories.includes(cat.id)}/>
                                        <label className="text-sm font-medium cursor-pointer">
                                            {cat.name}
                                        </label>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="space-y-3">
                            <Label>Modo de Dificuldade</Label>
                            <RadioGroup
                                value={difficultyMode}
                                onValueChange={(v) => setDifficultyMode(v as "progressive" | "fixed")}
                            >
                                <div className="flex items-center space-x-2 p-3 rounded-lg border border-border">
                                    <RadioGroupItem value="progressive" id="progressive"/>
                                    <Label htmlFor="progressive" className="flex-1 cursor-pointer">
                                        <div>
                                            <p className="font-medium">Progressiva (Recomendado)</p>
                                            <p className="text-xs text-muted-foreground">
                                                A dificuldade aumenta a cada pergunta
                                            </p>
                                        </div>
                                    </Label>
                                </div>
                                <div className="flex items-center space-x-2 p-3 rounded-lg border border-border">
                                    <RadioGroupItem value="fixed" id="fixed"/>
                                    <Label htmlFor="fixed" className="flex-1 cursor-pointer">
                                        <div>
                                            <p className="font-medium">Fixa</p>
                                            <p className="text-xs text-muted-foreground">
                                                Todas as perguntas na mesma dificuldade
                                            </p>
                                        </div>
                                    </Label>
                                </div>
                            </RadioGroup>
                        </div>

                        <Button
                            onClick={handleStartGame}
                            disabled={loading || !playerName || !selectedCategories.length}
                            className="w-full h-14 text-lg font-bold bg-gradient-gold hover:opacity-90 shadow-gold"
                        >
                            <Trophy className="w-6 h-6 mr-2"/>
                            {loading ? "Iniciando..." : "Começar o Jogo!"}
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}
