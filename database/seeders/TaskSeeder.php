<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->info('No projects found. Seeding aborted.');
            return;
        }

        foreach ($projects as $project) {
            Task::factory()->count(5)->create([
                'project_id' => $project->id,
            ]);
        }
    }
}
