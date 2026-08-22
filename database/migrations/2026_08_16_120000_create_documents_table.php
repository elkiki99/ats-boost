<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('type')->index();
            $table->string('title');
            $table->string('language', 5)->default('es');
            $table->string('template')->default('modern');

            // El documento estructurado (ResumeData o CoverLetterData). Es la
            // fuente de verdad: el PDF se vuelve a generar desde acá cada vez
            // que se descarga, así un cambio de plantilla alcanza a todos los
            // documentos ya creados sin migrar nada.
            $table->json('payload');

            // Informe ATS, cuando el documento vino del analizador.
            $table->json('report')->nullable();

            $table->string('role')->nullable();
            $table->string('company')->nullable();
            $table->text('job_description')->nullable();
            $table->string('source_filename')->nullable();
            $table->unsignedTinyInteger('ats_score')->nullable();

            $table->timestamps();

            // El listado siempre es "los documentos de este usuario, del más
            // nuevo al más viejo": el índice cubre esa consulta entera.
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
