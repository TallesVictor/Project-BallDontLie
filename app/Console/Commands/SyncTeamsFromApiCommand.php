<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Services\BallDontLieService;
use Illuminate\Console\Command;

class SyncTeamsFromApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-teams-from-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync teams from api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Syncing teams from api');

        $this->syncTeams();
    }

    private function syncTeams()
    {
        $ballDontLieService = new BallDontLieService();
        $teams = $ballDontLieService->getTeams();

        $this->output->progressStart(count($teams->data));

        foreach ($teams->data as $team) {

            try {
                $this->makeTeam($team);
            } catch (\Exception $exception) {
                $this->output->error('Error syncing team(' . $team->id . ') ' . $team->name . '|| ' . $exception->getMessage());
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    private function makeTeam(object $team): void
    {
        Team::updateOrCreate(
            [
                'team_origin_id' => $team->id,
            ],
            [
                'conference' => $team->conference,
                'division' => $team->division,
                'city' => $team->city,
                'name' => $team->name,
                'full_name' => $team->full_name,
                'abbreviation' => $team->abbreviation,
            ]
        );
    }
}
