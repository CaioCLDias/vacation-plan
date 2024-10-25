<?php

namespace Database\Seeders;

use App\Models\HolidayPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidayPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HolidayPlan::factory()->count(10)->create();
    }
}
