<?php

namespace DefStudio\Telegraph\Database\Factories;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegraphChatFactory extends Factory
{
    protected $model = TelegraphChat::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'chat_id' => $this->faker->randomNumber(),
            'name' => $this->faker->word,
            'telegraph_bot_id' => TelegraphBot::factory()->create(),
        ];
    }
}
