<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameQuestion;
use App\Services\GameEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * Tela principal do jogo.
     * Se houver um jogo em andamento, ele é enviado para a view;
     * caso contrário, a view recebe null e o botão "Iniciar" cria/começa o jogo.
     */
    public function index(?Game $game = null)
    {
        // $game pode ter vindo via rota /play/{game?}
        // ou você pode preferir buscar o mais recente do usuário logado:
        $game = $game ?? Game::where('user_id', auth()->id())->latest()->first();

        return view('game.index', [
            'game' => $game?->load('gameQuestions.question.answers'),
        ]);
    }

    /**
     * Cria um novo jogo e sorteia as 16 perguntas.
     * Endpoint consumido pelo front ao clicar em "Iniciar".
     * @throws \Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $user = $request->user();

        $game = DB::transaction(function () use ($user) {
            // cria jogo
            $game = Game::create([
                'user_id' => $user->id,
                'current_step' => 1,
                'current_prize' => 0,
                'secured_prize' => 0,
                'lifeline_5050' => true,
                'lifeline_universitarios' => true,
                'lifeline_placas' => true,
                'lifeline_pulo' => 3,
                'finished' => false,
            ]);

            // sorteia/perfila as perguntas
            GameEngine::generateQuestionsForGame($game);

            return $game;
        });

        return response()->json([
            'id' => $game->id,
            'current_step' => $game->current_step,
        ], 201);
    }

    /**
     * Retorna as perguntas (com respostas) já relacionadas ao jogo.
     * Usado pelo front imediatamente após iniciar/criar o jogo.
     */
    public function getQuestions(Game $game): JsonResponse
    {
        $questions = GameQuestion::where('game_id', $game->id)
            ->with(['question.answers'])
            ->orderBy('step')
            ->get();

        return response()->json($questions);
    }

    /**
     * Recebe resposta do jogador para a "step" atual.
     * Retorna JSON indicando correto/errado e prêmios atualizados.
     */
    public function answer(Game $game, Request $request): JsonResponse
    {
        $data = $request->validate([
            'step' => 'required|integer|min:1|max:16',
            'answer_id' => 'required|integer|exists:answers,id',
        ]);

        $result = GameEngine::processAnswer($game, (int)$data['step'], (int)$data['answer_id']);

        $result['message'] = $result['status'] === 'correct' ? 'Resposta correta!' : 'Resposta errada!';

        return response()->json($result);
    }

    /**
     * Usa lifelines: 5050, universitarios, placas, pulo.
     * Cada uma tem sua regra e estado no Game.
     */
    public function lifeline(Game $game, string $type, Request $request): JsonResponse
    {
        $data = $request->validate([
            'step' => 'required|integer|min:1|max:16',
        ]);

        $payload = GameEngine::processLifeline($game, $type, (int)$data['step']);

        return response()->json($payload);
    }

    /**
     * Jogador encerra manualmente, levando o prêmio garantido.
     */
    public function quit(Game $game): JsonResponse
    {
        GameEngine::quit($game);

        return response()->json([
            'message' => 'Jogo encerrado!',
            'current_prize' => $game->current_prize,
            'secured_prize' => $game->secured_prize,
            'finished' => $game->finished,
        ]);
    }

    /**
     * Garante que o jogo pertence ao usuário autenticado.
     */
    protected function authorizeGame(Game $game): void
    {
        abort_if($game->user_id !== auth()->id(), 403, 'Você não pode acessar este jogo.');
    }
}
