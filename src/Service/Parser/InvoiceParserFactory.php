<?php
declare(strict_types=1);

namespace App\Service\Parser;
use InvalidArgumentException;

class InvoiceParserFactory implements ParserFactoryInterface {
    public function createParser(string $filePath): ParserInterface {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        return match (strtolower($extension)) {
            'json' => new JsonParser(),
            'csv' => new CsvParser(),
            default => throw new InvalidArgumentException("Format non supporté: $extension. Les formats supportés sont: json, csv"),
        };
    }
}