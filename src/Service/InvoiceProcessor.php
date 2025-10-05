<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Invoice;
use App\Service\Parser\ParserFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class InvoiceProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParserFactoryInterface $parserFactory
    ) {}

    public function processFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("Fichier non trouvé: {$filePath}");
        }

        $parser = $this->parserFactory->createParser($filePath);
        $invoices = $parser->parse($filePath);

        foreach ($invoices as $invoice) {
            $this->upsertInvoice($invoice);
        }

        $this->entityManager->flush();
    }

    private function upsertInvoice(Invoice $invoice): void
    {
        $existingInvoice = $this->entityManager->getRepository(Invoice::class)
            ->findOneBy(['name' => $invoice->getName()]);

        if ($existingInvoice !== null) {
            $existingInvoice->setAmount($invoice->getAmount());
            $existingInvoice->setCurrency($invoice->getCurrency());
            $existingInvoice->setDate($invoice->getDate());
        } else {
            $this->entityManager->persist($invoice);
        }
    }
}