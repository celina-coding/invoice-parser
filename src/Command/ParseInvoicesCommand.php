<?php

declare(strict_types=1);


namespace App\Command;

use App\Service\InvoiceParser;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\InvoiceProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse')]
class ParseInvoicesCommand extends Command
{
    private InvoiceParser $parser;

    public function __construct(private InvoiceProcessor $processor)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->processor->processFile('data/invoices.csv');
        $this->processor->processFile('data/invoices.json');
        return Command::SUCCESS;
    }
}
