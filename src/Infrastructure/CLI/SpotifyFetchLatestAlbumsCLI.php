<?php

namespace App\Infrastructure\CLI;

use App\Application\Command\SaveLatestSpotifyAlbumsCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command as CLI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'spotify:fetch-latest-albums',
    description: 'Retrieves a list of the latest albums on Spotify and stores it in database'
)]
class SpotifyFetchLatestAlbumsCLI extends CLI
{
    public function __construct(private MessageBusInterface $messageBus)
    {
        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure() : void
    {
        $this->addOption(
            'amount',
            'a', 
            InputOption::VALUE_REQUIRED,
            'Amount of albums to retrieve',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $amount = $input->getOption('amount');

        if ($amount === null) {
            $output->writeln('<error>Please provide an amount of albums to retrieve</error>');
        }

        $this->messageBus->dispatch(new SaveLatestSpotifyAlbumsCommand($amount));

        return self::SUCCESS;
    }
}
