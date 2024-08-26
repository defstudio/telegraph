<?php

namespace DefStudio\Telegraph\Database\Factories;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TelegraphBot>
 */
class TelegraphBotFactory extends Factory
{
    protected $model = TelegraphBot::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'token' => $this->faker->uuid,
            'name' => $this->faker->word,
        ];
    }
}
