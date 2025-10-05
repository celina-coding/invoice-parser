<?php
declare(strict_types=1);
namespace App\Service\Parser;
use App\Entity\Invoice;

class JsonParser implements ParserInterface {

    public function parse(string $filePath): array {
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);
        $invoices = [];
        foreach ($data as $item) {
            $invoices[] = $this->createInvoice($item);
        }
        return $invoices;
    }

    private function createInvoice(array $data): Invoice {   
        $invoice = new Invoice();
        $invoice->setName($data['nom'] ?? '');
        $invoice->setAmount(isset($data['montant']) ? (float)$data['montant'] : 0.0);
        $invoice->setCurrency($data['devise'] ?? '');
        $invoice->setDate(isset($data['date']) ? new \DateTime($data['date']) : new \DateTime());
        return $invoice;
    }

}