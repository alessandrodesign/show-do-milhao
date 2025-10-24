<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adiciona um índice FULLTEXT na coluna 'statement' para otimizar buscas.
     */
    public function up(): void
    {
        // Verifica se o driver de banco de dados não é SQLite
        // pois o SQLite não suporta FULLTEXT e falharia nos testes.
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('questions', function (Blueprint $table) {
                // Isso é o equivalente a:
                // ALTER TABLE questions ADD FULLTEXT(statement); (no MySQL)
                $table->fullText('statement');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Remove o índice FULLTEXT.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('questions', function (Blueprint $table) {
                // O Laravel nomeia o índice automaticamente como: [tabela]_[coluna]_fulltext
                // $table->dropFullText('questions_statement_fulltext');
                // ou de forma mais simples, passando a coluna:
                $table->dropFullText('statement');
            });
        }
    }
};
