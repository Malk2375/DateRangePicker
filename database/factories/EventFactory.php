<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $startsAt = $this->faker->dateTimeThisYear();
        $endsAt = $this->faker->dateTimeBetween($startsAt, '+2 days');
        return [
            'title' => $this->faker->jobTitle(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];
    }
}
