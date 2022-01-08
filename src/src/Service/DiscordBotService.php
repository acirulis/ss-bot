<?php

namespace App\Service;

use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DiscordBotService
{
    private Discord $discord;
    private Channel $channel;

    /**
     * @throws IntentException
     */
    public function __construct(
        private SsScraperService $ssScraperService,
    )
    {
        $this->discord = new Discord([
            'token' => 'ODY2MjQ5ODE1MTQ3MTUxMzYx.YPP0Ag.kBZMZTbjZFBmuJds2OmD-37HX3A',
        ]);
        /**
         * @var Logger $logger
         */
        $logger = $this->discord->getLogger();
        /**
         * @var StreamHandler $stream_handler
         */
        $stream_handler = $logger->getHandlers()[0];
        $stream_handler->setLevel(Logger::WARNING);

    }

    public function runBot(): void
    {
        $this->discord->getLoop()->addPeriodicTimer(1823, function () { //1800s = 30m
            echo "SS bot started for Riga Region House\n";
            $houses = $this->ssScraperService->getHouseDataRigaRegion();
            if (0 < count($houses)) {
                foreach ($houses as $house) {
                    $this->channel->sendMessage(sprintf('Riga Region house arrived %s [%s] [%s] [%s]', $house['href'], $house['description'], $house['address'], $house['price']));
                }
            }
        });

        $this->discord->getLoop()->addPeriodicTimer(1715, function () { //1800s = 30m
            echo "SS bot started for Riga House\n";
            $houses = $this->ssScraperService->getHouseDataRiga();
            if (0 < count($houses)) {
                foreach ($houses as $house) {
                    $this->channel->sendMessage(sprintf('Riga Region house arrived %s [%s] [%s] [%s]', $house['href'], $house['description'], $house['address'], $house['price']));
                }
            }
        });

        $this->discord->on('ready', function (Discord $discord) {
            echo "Bot is ready!", PHP_EOL;
            $this->channel = $this->discord->getChannel('795626800671752212');
            $this->channel->sendMessage('SS Bot just woke up!');

        });

        $this->discord->run();
    }
}