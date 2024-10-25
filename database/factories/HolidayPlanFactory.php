<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayPlan>
 */
class HolidayPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->date('Y-m-d'),
            'location' => $this->faker->city(),
            'participants' => $this->generateParticipants()
        ];
    }

    private function generateParticipants()
    {
        $participants = [];
        for ($i = 0; $i < rand(1, 10); $i++) {
            $participants[] = $this->faker->name;
        }

        return $participants;
    }
}
