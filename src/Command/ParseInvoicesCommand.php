<?php

declare(strict_types=1);


namespace App\Command;

use App\Service\InvoiceParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse')]
class ParseInvoicesCommand extends Command
{

    public function __construct( private InvoiceParser $parser)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parser->parse('data/invoices.csv');
        $this->parser->parse('data/invoices.json');
        return Command::SUCCESS;
    }
}
