<?php

namespace App\Command;

use App\Entity\ExchangeRate;
use App\Service\BankData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-exchange-rate',
    description: 'Get currency exchange data from API and save to database',
    aliases: ['app:update-exchange-rate'],
    hidden: false
)]

class ExchangeRateUpdate extends Command
{
    public function __construct(private BankData $bankData, private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Fetching data');

        $bankData = $this->bankData->fetchBankData();

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

        $output->writeln('Exchange Rates have been updated');

        return Command::SUCCESS;
    }
}
