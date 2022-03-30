<?php

namespace App\Command;

use App\Service\BankData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'app:exchange-rates-custom-date',
    description: 'Get currency exchange data for a set date from API and save to database',
    aliases: ['app:exchange-rates-custom-date'],
    hidden: false
)]

class ExchangeRateCustomDate extends Command
{
    public function __construct(private BankData $bankData)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'Date (Y-m-d)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Fetching data for: '.$input->getArgument('date'),
            'Request for a date on a holiday will return data for the previous workday'
        ]);

        $bankData = $this->bankData->fetchBankDataForDate($input->getArgument('date'));

        if (empty($bankData))
        {
            $output->writeln("Bank API error");

            return Command::FAILURE;
        }

        try
        {
            $this->bankData->uploadToDatabase($bankData);
        }
        catch (\Exception $e)
        {
            return Command::FAILURE;
        }

        $output->writeln('Exchange Rates for '.$input->getArgument('date').' have been updated');

        return Command::SUCCESS;
    }
}
