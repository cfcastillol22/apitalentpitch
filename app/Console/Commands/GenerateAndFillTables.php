<?php

// app/Console/Commands/GenerateAndFillTables.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenAIService;
use App\Models\Challenge;
use App\Models\Video;

class GenerateAndFillTables extends Command
{
    protected $signature = 'api:generate-fill-tables';
    protected $description = 'Generar datos y llenar las tablas challenges y videos';

    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        parent::__construct();
        $this->openAIService = $openAIService;
    }

    public function handle()
    {
        // Generar datos para challenges
        $challengesPrompt = "Genera una lista de challenges con título, descripción y dificultad.";
        $challengesData = $this->openAIService->generateChallengesData($challengesPrompt);

        // Insertar datos en la tabla de challenges
        foreach ($challengesData['choices'] as $item) {
            Challenge::updateOrCreate(
                ['id' => $item['id']],
                [
                    'title' => $item['text']['title'],
                    'description' => $item['text']['description'],
                    'difficulty' => $item['text']['difficulty'],
                    'user_id' => 1,
                ]
            );
        }


        $videosPrompt = "Genera una lista de videos con descripción, URL y logo.";
        $videosData = $this->openAIService->generateVideosData($videosPrompt);

        // Insertar datos en la tabla de videos
        foreach ($videosData['choices'] as $item) {
            Video::updateOrCreate(
                ['id' => $item['id']],
                [
                    'description' => $item['text']['description'],
                    'url' => $item['text']['url'],
                    'logo' => $item['text']['logo'],
                    'user_id' => 1,
                ]
            );
        }

        $this->info('Las tablas challenges y videos han sido llenadas con datos generados IA.');
    }
}
