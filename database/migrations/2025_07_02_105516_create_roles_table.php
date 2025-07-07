<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'name' => 'Watcher',
                'slug' => 'watcher',
                'description' => 'É a entidade máxima do sistema, com acesso irrestrito a todas as funcionalidades e informação. ',
                'created_at' => \Carbon\Carbon::now()],
            [
                'name' => 'Director',
                'slug' => 'director',
                'description' => 'Administra e supervisiona áreas estratégicas do sistema, com permissões avançadas mas sem acesso irrestrito.',
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Collector',
                'slug' => 'collector',
                'description' => 'Responsável pela gestão da coleção digital e pelo acesso a fotografias de determinados fundos.',
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
