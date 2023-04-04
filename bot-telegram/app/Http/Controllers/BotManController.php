<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use mysql_xdevapi\Exception;

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
            $user = $bot->getUser();
            $bot->reply('Hello, '.$user->getFirstName().' '.$user->getId().'!');
        });

        // Listen for the "hello" message
        $botman->hears('hello', function (BotMan $bot) {
            $user = $bot->getUser();
            $bot->reply('Hello, '.$user->getFirstName().'!');
        });
        $botman->hears('start', function (BotMan $bot) {
            $bot->reply('Hello start.');
        });
        $botman->listen();
    }
    public function handle1(BotMan $bot)
    {
        $message = $bot->getMessage();
        $user = $message->getUser();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $username = $user->getUsername();
        $id = $user->getId();

        // Використовуйте інформацію про користувача за потреби
        $bot->reply("Привіт, $firstName $lastName (username: @$username, ID: $id)!");
    }
}
