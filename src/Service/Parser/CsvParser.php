<?php
declare(strict_types=1);
namespace App\Service\Parser;
use App\Entity\Invoice;

class CsvParser implements ParserInterface {

    public function parse(string $filePath): array {
        $invoices = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
                if (count($data) >= 4) {
                    $invoices[] = $this->createInvoice($data);
                }
            }
            fclose($handle);
        }
        return $invoices;
    }

    private function createInvoice(array $data): Invoice {   
        $invoice = new Invoice();
        $invoice->setAmount((float)$data[0]);
        $invoice->setCurrency($data[1]);
        $invoice->setName($data[2]);
        $dateString = trim($data[3]);
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        
        if ($date === false) {
            throw new RuntimeException(
                "Format de date invalide dans le CSV: '{$dateString}'. Format attendu: Y-m-d"
            );
        }
        
        $invoice->setDate($date);
        return $invoice;
    }

}