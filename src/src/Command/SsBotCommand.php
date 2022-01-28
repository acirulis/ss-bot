<?php

namespace App\Command;

use App\Service\DiscordBotService;
use App\Service\SsScraperService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SsBotCommand extends Command
{
    protected static $defaultName = 'app:ssbot';

    public function __construct(
        private DiscordBotService $discordBotService,
        private SsScraperService $ssScraperService,
    )
    {
        parent::__construct();
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('SS BOT runner');
        switch ((int)$input->getArgument('mode')) {
            case 0:
                $this->discordBotService->runBot();
                break;
            case 1:
                $this->ssScraperService->getApartmentDataRiga(false);
                break;
            case 2:
                $this->ssScraperService->getHouseDataRigaRegion();
                $this->ssScraperService->getHouseDataRiga();
                $this->ssScraperService->getApartmentDataRiga(true);
                break;
            default:
                throw new \RuntimeException('Unknown mode');
        }
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('mode', InputArgument::OPTIONAL, 'Mode (0 = bot (default); 1 = ss_scraper no db; 2 = ss_scraper with db)',0);
    }

}