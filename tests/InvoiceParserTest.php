<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\InvoiceParser;
use App\Service\Parser\ParserFactoryInterface;
use App\Service\Parser\ParserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceParserTest extends KernelTestCase
{
    private $entityManager;
    private $parserFactory;

    public function testParseJson(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->parserFactory = $this->createMock(ParserFactoryInterface::class);
        
        $mockParser = $this->createMock(ParserInterface::class);
        $mockParser->expects($this->once())
            ->method('parse')
            ->with('data/invoices.json')
            ->willReturn($this->createMockInvoices(10));

        $this->parserFactory->expects($this->once())
            ->method('createParser')
            ->with('data/invoices.json')
            ->willReturn($mockParser);

        $this->entityManager->expects($this->exactly(10))
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $invoiceParser = new InvoiceParser($this->entityManager, $this->parserFactory);
        $invoiceParser->parse('data/invoices.json');
    }

    public function testParseCsv(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->parserFactory = $this->createMock(ParserFactoryInterface::class);
        
        $mockParser = $this->createMock(ParserInterface::class);
        $mockParser->expects($this->once())
            ->method('parse')
            ->with('data/invoices.csv')
            ->willReturn($this->createMockInvoices(10));

        $this->parserFactory->expects($this->once())
            ->method('createParser')
            ->with('data/invoices.csv')
            ->willReturn($mockParser);

        $this->entityManager->expects($this->exactly(10))
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $invoiceParser = new InvoiceParser($this->entityManager, $this->parserFactory);
        $invoiceParser->parse('data/invoices.csv');
    }

    private function createMockInvoices(int $count): array
    {
        $invoices = [];
        for ($i = 0; $i < $count; $i++) {
            $invoice = $this->createMock(\App\Entity\Invoice::class);
            $invoice->method('getName')->willReturn("Invoice $i");
            $invoices[] = $invoice;
        }
        return $invoices;
    }
}