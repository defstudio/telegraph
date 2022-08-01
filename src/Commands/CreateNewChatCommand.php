<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;

class CreateNewChatCommand extends Command
{
    public $signature = 'telegraph:new-chat
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}';

    public $description = 'Create a new chat';

    public function handle(): int
    {
        /** @var int|null $bot_id */
        $bot_id = $this->argument('bot');

        /** @var class-string<TelegraphBot> $botModel */
        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot|null $bot */
        $bot = rescue(fn () => $botModel::fromId($bot_id), report: false);

        if (empty($bot)) {
            $this->error("Please specify a Bot ID");

            return self::FAILURE;
        }

        $this->info("You are about to create a new Telegram Chat for bot $bot->name");


        while (empty($chat_id = $this->ask("Enter the chat id - press [x] to abort:"))) {
            $this->error("The chat ID cannot be null");
        }

        if ($chat_id != 'x') {
            $chat_name = $this->ask("Enter the chat name (optional):");

            /** @var TelegraphChat $chat */
            $chat = $bot->chats()->create([
                'chat_id' => $chat_id,
                'name' => $chat_name,
            ]);

            $this->info("New chat $chat->name has been create for bot $bot->name");
        }


        return self::SUCCESS;
    }
}
