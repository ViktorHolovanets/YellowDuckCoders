<?php

namespace App\Http\Controllers;

use App\Models\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {

        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

        $config = [
            'user_cache_time' => 720,

            'config' => [
                'conversation_cache_time' => 720,
            ],

            "telegram" => [
                "token" => env('TELEGRAM_TOKEN'),
            ]
        ];

        // // Create BotMan instance
        $botman = BotManFactory::create($config);

        $botman->hears('/start', function (BotMan $bot) {
            $userTelegram = $bot->getUser();
            $user = User::find($userTelegram->getId());
            if (!$user) {
                $user = User::create([
                    'id' => $userTelegram->getId(),
                    'name' => $userTelegram->getFirstName()??'Unknown',
                ]);
            }
            $bot->reply('Hello, '.$userTelegram->getFirstName().'!');
        });

        $botman->listen();
    }
}
