<?php

namespace App\Command;

use App\Service\DiscordBotService;
use App\Service\SsScraperService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SsBotCommand extends Command
{
    protected static $defaultName = 'app:ssbot';

    public function __construct(
        private DiscordBotService $discordBotService,
    )
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('SS BOT runner');
        $this->discordBotService->runBot();
        return Command::SUCCESS;
    }

}