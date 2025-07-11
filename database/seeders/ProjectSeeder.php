<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Safely delete all projects (respects FK constraints)
        DB::table('projects')->delete();

        // Create 5 fake projects using the Project factory
        Project::factory()->count(5)->create(); // generates fake projects
    }
}
