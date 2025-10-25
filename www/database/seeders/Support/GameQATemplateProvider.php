<?php

namespace Database\Seeders\Support;

class GameQATemplateProvider
{
    // ------------- Pools NATAL (ainda mais ampliados) -------------
    private array $natalSimbolos = [
        'Guirlanda','Árvore de Natal','Estrela de Belém','Sinos','Vela','Presépio','Boneco de Neve',
        'Meias na lareira','Renas','Anjos','Fitas douradas','Bolas vermelhas','Laços','Pinheiros','Poinsétia',
        'Azevinho','Visco','Estrela no topo','Trenó','Coroas de Advento','Velas do Advento','Cartões de Natal',
        'Cenas de Natividade','Luzes pisca-pisca','Campainhas','Presentes embrulhados','Cestos de Natal',
        'Flores vermelhas','Sacos de presentes','Anjo trombeteiro','Casa de gengibre','Bengala de açúcar'
    ];

    private array $natalComidas = [
        'Rabanada','Panetone','Chester','Peru','Tender','Farofa','Salpicão','Castanhas','Nozes','Frutas secas',
        'Arroz à grega','Bacalhau','Manjar branco','Pavê','Torta gelada','Pernil','Lombo assado','Maionese',
        'Damascos','Ameixa seca','Quiche','Cuscuz','Batata gratinada','Salada de batata','Sonhos','Biscoito de gengibre',
        'Bolo de frutas','Leitoa','Filé mignon ao molho','Bruschettas','Rocambole','Arroz de forno'
    ];

    private array $natalDocesSabores = [
        'Nozes','Avelã','Chocolate','Baunilha','Frutas cristalizadas','Amêndoas','Damasco','Cereja',
        'Doce de leite','Creme','Marzipã','Especiarias','Canela','Cravo','Gengibre','Cardamomo','Mel'
    ];

    private array $natalMusicas = [
        'Noite Feliz','Jingle Bells','Bate o Sino','Então é Natal','Sinos de Belém','All I Want for Christmas Is You',
        'Jingle Bell Rock','White Christmas','Hark! The Herald Angels Sing','O Velhinho','Feliz Navidad',
        'Deck the Halls','O Holy Night','Santa Claus Is Coming to Town','Little Drummer Boy','The Christmas Song'
    ];

    private array $natalFilmesSeries = [
        'Esqueceram de Mim','O Grinch','Um Duende em Nova York','O Estranho Mundo de Jack','Milagre na Rua 34',
        'A Felicidade Não se Compra','Klaus','Um Herói de Brinquedo','Crônicas de Natal','Cartão de Natal',
        'Um Passado de Presente','A Princesa e a Plebeia','A Very Murray Christmas','Love Actually','Um Conto de Natal',
        'Um Natal Brilhante','Operação Presente','O Expresso Polar'
    ];

    private array $natalPersonagens = [
        'Papai Noel','Duendes','Rena Rudolph','Três Reis Magos','Anjos','José','Maria','Menino Jesus','Biscoito de Gengibre',
        'Mamãe Noel'
    ];

    private array $natalCores = ['Vermelho','Verde','Dourado','Prata','Branco'];

    private array $natalCidadesBiblicas = ['Belém','Jerusalém','Nazaré','Belém da Judeia'];
    private array $natalDatas = ['25 de dezembro'];
    private array $natalPlantas = ['Poinsétia','Azevinho','Visco','Pinheiro','Cedro','Abeto'];

    private array $natalCostumes = [
        'Montar a árvore','Ceia em família','Troca de presentes','Cantar músicas natalinas',
        'Fazer Amigo Secreto','Montar presépio','Acender velas do Advento','Enviar cartões','Visitar parentes',
        'Decorar a casa','Assistir filmes de Natal','Fazer doações','Missas do Galo'
    ];

    private array $natalAntesDepois = [
        'Advento antes do Natal','Ceia na véspera (24) e celebração no dia (25)',
        'Montagem da árvore antes de dezembro e desmontagem em janeiro','Novena de Natal antes do dia 25'
    ];

    // ------------- Pools RÉVEILLON (ainda mais ampliados) -------------
    private array $reveillonCores = ['Branco','Amarelo','Rosa','Vermelho','Verde','Azul','Laranja','Roxo','Dourado','Prata','Preto'];
    private array $reveillonComidas = [
        'Lentilha','Uvas','Romã','Carne de porco','Peixe','Arroz com lentilha','Champanhe com uvas',
        'Uvas-passas','Arroz com romã','Pernil de porco','Lombo suíno'
    ];
    private array $reveillonBebidas = ['Champanhe','Espumante','Sidra','Prosecco','Cava'];
    private array $reveillonCostumes = [
        'Pular sete ondas','Usar roupa branca','Estourar champanhe','Guardar sementes de romã',
        'Comer lentilha','Contagem regressiva','Abraçar à meia-noite','Pisar com o pé direito',
        'Queimar fogos','Fazer desejos','Assistir shows na praia'
    ];
    private array $reveillonCidades = [
        'Rio de Janeiro','São Paulo','Salvador','Fortaleza','Recife','Florianópolis','Balneário Camboriú','Natal','Maceió',
        'Curitiba','Porto Alegre','João Pessoa','Vitória','Belém'
    ];
    private array $fogosLocais = ['Copacabana','Avenida Paulista','Farol da Barra','Beira-Mar','Ponte Hercílio Luz','Praia Central','Ponta Negra','Praia de Iracema','Ponta Verde','Cabo Branco'];

    private array $reveillonMusicas = [
        'Auld Lang Syne','Adeus Ano Velho','Happy New Year','New Year’s Day','Celebration','This Is the New Year'
    ];

    private array $reveillonFilmesSeries = [
        'New Year’s Eve','Friends (ep. de Ano Novo)','How I Met Your Mother (ep. de Ano Novo)',
        'Modern Family (ep. de Ano Novo)','Glee (ep. temáticos)','Doctor Who (Specials de Ano Novo)','High School Musical'
    ];

    private array $reveillonDocesSaboresNaoTipicos = [
        'Gengibre natalino','Frutas cristalizadas típicas de Natal','Marzipã','Biscoito de gengibre','Panetone'
    ];

    // ------------- Pools Gerais / Regionais / Históricos -------------
    private array $coresGerais = ['Vermelho','Azul','Verde','Amarelo','Rosa','Roxo','Laranja','Preto','Branco','Dourado','Prata','Marrom','Cinza'];

    private array $anos = [1950,1955,1960,1965,1970,1975,1980,1985,1990,1995,2000,2005,2010,2015,2019,2020,2021,2022,2023,2024];
    private array $paises = ['Brasil','Portugal','Espanha','Itália','França','Alemanha','Estados Unidos','Canadá','Reino Unido','Argentina','México','Japão','Austrália'];
    private array $estadosBR = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
    private array $cidadesBRLitorais = ['Rio de Janeiro','Santos','Florianópolis','Salvador','Fortaleza','Recife','Natal','Maceió','Vitória','Belém','João Pessoa','Aracaju','Ilhéus','Ubatuba'];

    public function make(int $categoryId, int $difficultyOrder, int $seq): array
    {
        return $categoryId === 1 ? $this->makeNatal($difficultyOrder, $seq) : $this->makeReveillon($difficultyOrder, $seq);
    }

    // ---------------- NATAL ----------------
    private function makeNatal(int $difficultyOrder, int $seq): array
    {
        $templates = [
            fn() => $this->tplEscolha("Qual destes é um símbolo tradicional do Natal?", $this->natalSimbolos, ['Girassol','Abóbora de Halloween','Trevo de quatro folhas','Máscara de carnaval']),
            fn() => $this->tplEscolha("Qual destas comidas é comum na ceia de Natal no Brasil?", $this->natalComidas, ['Sushi','Feijoada','Tacacá','Tacos']),
            fn() => $this->tplEscolha("Qual destas músicas é associada ao Natal?", $this->natalMusicas, ['We Are The Champions','Despacito','Evidências','Garota de Ipanema']),
            fn() => $this->tplEscolha("Qual é a data do feriado de Natal no Brasil?", $this->natalDatas, ['24 de dezembro','1º de janeiro','12 de outubro','31 de dezembro']),
            fn() => $this->tplEscolha("Qual cor é fortemente associada às decorações natalinas?", $this->natalCores, array_values(array_diff($this->coresGerais, $this->natalCores))),
            fn() => $this->tplEscolha("Qual personagem é tradicionalmente associado a presentes de Natal?", $this->natalPersonagens, ['Coelhinho da Páscoa','Curupira','Saci','Duende da sorte']),
            fn() => $this->tplEscolha("Qual cidade é associada ao nascimento de Jesus?", $this->natalCidadesBiblicas, ['Roma','Atenas','Meca','Cairo']),
            fn() => $this->tplEscolha("Qual destas plantas é comumente associada ao Natal?", $this->natalPlantas, ['Lavanda','Girassol','Hortênsia','Cactos']),
            // NÃO é sabor típico de Natal
            fn() => $this->tplNegativo("Qual destes NÃO é um sabor típico de doces natalinos?", $this->natalDocesSabores, ['Tamarindo apimentado','Matcha intenso','Pepino agridoce','Azedinha','Limão siciliano salgado']),
            // Filmes/séries natalinos
            fn() => $this->tplEscolha("Qual destes filmes/séries é comumente associado ao Natal?", $this->natalFilmesSeries, ['Halloween','Velozes e Furiosos','Pantera Negra','Rocky']),
            // Regionais
            fn() => $this->tplEscolha("Qual tradição de Natal é comum em muitas regiões do Brasil?", $this->natalCostumes, ['Desfile de escolas de samba','Micareta fora de época','Trio elétrico no dia 31']),
            // Sequência temporal (antes/depois)
            fn() => $this->tplSequencia("Qual destes costuma ocorrer antes do dia de Natal?", ['Advento','Montagem da árvore','Novena de Natal','Compra de presentes'], ['Ceia do dia 25','Troca de presentes no dia 25','Queima de fogos de Réveillon','Pular sete ondas']),
            // Curiosidades históricas
            fn() => $this->tplAnoPais("Em que década/ano determinada tradição natalina se popularizou em muitos países?", $this->anos, $this->paises),
            // Associação dupla
            fn() => $this->tplAssociacao(sprintf("Qual cor costuma aparecer junto ao item natalino selecionado: %s?", $this->pick($this->natalSimbolos)), $this->natalCores, array_values(array_diff($this->coresGerais, $this->natalCores))),
            // Cloze (lacuna)
            fn() => $this->tplCloze("A canção ___ é tradicional no Natal.", $this->natalMusicas, ['We Are The Champions','Boate Azul','Thriller','Smells Like Teen Spirit']),
            // Exceto (grupo misto)
            fn() => $this->tplExceto("Qual dos itens abaixo NÃO pertence a decorações natalinas?", $this->natalSimbolos, ['Máscara de carnaval','Abóbora de Halloween','Confete de carnaval','Serpentina de carnaval']),
            // Ordem/contagem
            fn() => $this->tplContagem("Quantas velas do Advento são tradicionalmente acesas ao longo das semanas que antecedem o Natal?", 4, [2,3,5]),
            // Geografia detalhada (cidade/estado)
            fn() => $this->tplCidadeEstado(sprintf("Em qual estado brasileiro está a cidade litorânea com decoração de Natal famosa: %s?", $this->pick($this->cidadesBRLitorais))),
        ];

        if ($difficultyOrder >= 8) {
            $templates[] = fn() => $this->tplMusicaAno(sprintf("A música natalina '%s' ganhou destaque em qual período?", $this->pick($this->natalMusicas)));
        }
        if ($difficultyOrder >= 10) {
            $templates[] = fn() => $this->tplPaisCostume("Em qual país é comum a troca de cartões natalinos como tradição popular?", $this->paises, ['Antártida','Atlântida','Wakanda','Nárnia']);
        }

        $qa = $this->pick($templates)();
        $qa['question'] .= " [Nível {$difficultyOrder}] #{$seq}";
        $qa['hint'] = $this->hintNatal($difficultyOrder);
        return $qa;
    }

    // ---------------- RÉVEILLON ----------------
    private function makeReveillon(int $difficultyOrder, int $seq): array
    {
        $templates = [
            fn() => $this->tplEscolha("Qual cor é tradicionalmente usada no Réveillon para atrair paz?", ['Branco'], ['Preto','Roxo','Marrom','Cinza']),
            fn() => $this->tplEscolha("Qual destes alimentos é consumido no Ano Novo para atrair prosperidade?", $this->reveillonComidas, ['Feijoada','Batata frita','Lasanha','Sorvete']),
            fn() => $this->tplEscolha("Em qual data ocorre a virada do ano?", ['31 de dezembro'], ['24 de dezembro','1º de maio','7 de setembro','15 de novembro']),
            fn() => $this->tplEscolha("Qual destes costumes é comum no Réveillon no Brasil?", $this->reveillonCostumes, ['Colorir ovos','Esconder presentes no jardim','Cortar árvore de pinheiro','Acender velas do Advento']),
            fn() => $this->tplEscolha("Qual bebida é frequentemente estourada à meia-noite no Réveillon?", $this->reveillonBebidas, ['Vinho tinto encorpado','Tequila','Vodca','Cerveja escura']),
            fn() => $this->tplFogosCidade(sprintf("Em qual cidade brasileira a queima de fogos em %s é famosa no Réveillon?", $this->pick($this->fogosLocais))),
            // NÃO é típico de Réveillon
            fn() => $this->tplNegativo("Qual destes NÃO é um costume gastronômico típico de Réveillon?", ['Lentilha','Uvas','Romã','Carne de porco'], $this->reveillonDocesSaboresNaoTipicos),
            // Filmes/séries de Ano Novo
            fn() => $this->tplEscolha("Qual destes títulos está relacionado a Ano Novo?", $this->reveillonFilmesSeries, ['O Grinch','Esqueceram de Mim','Klaus','A Felicidade Não se Compra']),
            // Sequência temporal
            fn() => $this->tplSequencia("Qual destes acontece após a contagem regressiva de Ano Novo?", ['Abraços e cumprimentos','Brinde com espumante','Fogos de artifício','Cumprir simpatias'], ['Preparativos de Natal','Novena de Natal','Ceia de 24/12','Montagem de árvore']),
            // Curiosidades históricas
            fn() => $this->tplAnoPais("Em que década/ano a queima de fogos de Réveillon ganhou destaque em muitos países?", $this->anos, $this->paises),
            // Associação por desejo
            fn() => $this->tplAssociacao("Para atrair amor no Réveillon, qual cor é frequentemente escolhida?", ['Rosa','Vermelho'], array_values(array_diff($this->coresGerais, ['Rosa','Vermelho']))),
            // Cloze
            fn() => $this->tplCloze("A canção tradicional de contagem regressiva em muitos países é ___ .", $this->reveillonMusicas, ['Bohemian Rhapsody','Smooth Criminal','Billie Jean','Evidências']),
            // Exceto
            fn() => $this->tplExceto("Qual item NÃO pertence a simpatias de Réveillon?", ['Pular sete ondas','Guardar sementes de romã','Comer lentilha','Usar branco'], ['Montar presépio','Cantar Noite Feliz','Acender velas do Advento','Montar árvore']),
            // Ordem/contagem
            fn() => $this->tplContagem("Quantas ondas se costuma pular para simpatia de sorte no Réveillon?", 7, [5,6,8]),
            // Geografia detalhada (cidade/estado)
            fn() => $this->tplCidadeEstado(sprintf("Em qual estado está a cidade litorânea conhecida por festas de Réveillon: %s?", $this->pick($this->cidadesBRLitorais))),
        ];

        if ($difficultyOrder >= 8) {
            $templates[] = fn() => $this->tplMusicaAno(sprintf("A canção de Ano Novo '%s' se tornou tradicional em que período?", $this->pick($this->reveillonMusicas)));
        }
        if ($difficultyOrder >= 10) {
            $templates[] = fn() => $this->tplPaisCostume("Em qual país shows com fogos na virada são grande atração turística?", $this->paises, ['Atlântida','Neverland','Wakanda','Nárnia']);
        }

        $qa = $this->pick($templates)();
        $qa['question'] .= " [Nível {$difficultyOrder}] #{$seq}";
        $qa['hint'] = $this->hintReveillon($difficultyOrder);
        return $qa;
    }

    // ---------------- Hints ----------------
    private function hintNatal(int $difficultyOrder): ?string
    {
        return $difficultyOrder <= 4 ? 'Pense em tradições e símbolos clássicos do Natal no Brasil e no mundo.' : null;
    }

    private function hintReveillon(int $difficultyOrder): ?string
    {
        return $difficultyOrder <= 4 ? 'Lembre-se de costumes populares brasileiros na virada do ano.' : null;
    }

    // ---------------- Templates genéricos ----------------
    private function tplEscolha(string $q, array $corretos, array $erradosPool): array
    {
        $correct = $this->pick($corretos);
        $wrongs  = $this->pickMany($erradosPool, 3, [$correct]);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplNegativo(string $q, array $grupoTipico, array $naoTipicos): array
    {
        $correct = $this->pick($naoTipicos);
        $wrongs  = $this->pickMany($grupoTipico, 3);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplAssociacao(string $q, array $corretos, array $errados): array
    {
        $correct = $this->pick($corretos);
        $wrongs  = $this->pickMany($errados, 3, [$correct]);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplCloze(string $qMask, array $corretos, array $errados): array
    {
        $q = str_replace('___', '_____', $qMask); // lacuna visual
        $correct = $this->pick($corretos);
        $wrongs  = $this->pickMany($errados, 3, [$correct]);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplExceto(string $q, array $grupo, array $naoPertencePool): array
    {
        $correct = $this->pick($naoPertencePool);
        $wrongs  = $this->pickMany($grupo, 3);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplSequencia(string $q, array $antes, array $depois): array
    {
        $correct = $this->pick($antes);
        $wrongs  = $this->pickMany($depois, 3);
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplAnoPais(string $q, array $anos, array $paises): array
    {
        $correct = (string)$this->pick($anos);
        $pool = array_values(array_diff($anos, [(int)$correct]));
        shuffle($pool);
        $wrongs = array_map(fn($a) => (string)$a, array_slice($pool, 0, 3));
        // O país é usado para contextualizar mentalmente; deixamos implícito.
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplMusicaAno(string $q): array
    {
        $correct = (string)$this->pick($this->anos);
        $pool = array_values(array_diff($this->anos, [(int)$correct]));
        shuffle($pool);
        $wrongs = array_map(fn($a) => (string)$a, array_slice($pool, 0, 3));
        return $this->pack($q, $correct, $wrongs);
    }

    private function tplContagem(string $q, int $correct, array $wrongs): array
    {
        $wrongs = array_map('strval', $wrongs);
        return $this->pack($q, (string)$correct, $wrongs);
    }

    private function tplCidadeEstado(string $q): array
    {
        $cidade = $this->pick($this->cidadesBRLitorais);
        $map = [
            'Rio de Janeiro'=>'RJ','Santos'=>'SP','Florianópolis'=>'SC','Salvador'=>'BA','Fortaleza'=>'CE',
            'Recife'=>'PE','Natal'=>'RN','Maceió'=>'AL','Vitória'=>'ES','Belém'=>'PA','João Pessoa'=>'PB',
            'Aracaju'=>'SE','Ilhéus'=>'BA','Ubatuba'=>'SP'
        ];
        $correct = $map[$cidade] ?? 'RJ';
        $wrongs = $this->pickMany(array_values(array_diff($this->estadosBR, [$correct])), 3);
        $qFull = str_replace('%s', $cidade, $q);
        return $this->pack($qFull, $correct, $wrongs);
    }

    private function tplFogosCidade(string $q): array
    {
        $local = $this->pick($this->fogosLocais);
        $qFull = str_replace('%s', $local, $q);
        $correct = 'Rio de Janeiro';
        $wrongs = $this->pickMany(array_values(array_diff($this->reveillonCidades, [$correct])), 3);
        return $this->pack($qFull, $correct, $wrongs);
    }

    private function tplPaisCostume(string $q, array $paises, array $fantasia): array
    {
        $correct = $this->pick($paises);
        $wrongs = $this->pickMany($fantasia, 3);
        return $this->pack($q, $correct, $wrongs);
    }

    // ---------------- Utils ----------------
    private function pick(array $arr)
    {
        return $arr[array_rand($arr)];
    }

    private function pickMany(array $arr, int $n, array $exclude = []): array
    {
        $pool = array_values(array_filter($arr, fn($x) => !in_array($x, $exclude, true)));
        shuffle($pool);
        if ($n > count($pool)) $n = count($pool);
        return array_slice($pool, 0, $n);
    }

    private function pack(string $q, string $correct, array $wrongs): array
    {
        return ['question' => $q, 'correct' => $correct, 'wrongs' => $wrongs];
    }
}
