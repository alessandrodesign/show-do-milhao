<?php

namespace App\Enums;

enum GameState: string
{
    case RUNNING = 'running';
    case WON = 'won';
    case LOST = 'lost';
    case QUIT = 'quit';
}
