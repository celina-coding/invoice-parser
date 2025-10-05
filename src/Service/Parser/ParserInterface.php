<?php

declare(strict_types=1);
namespace App\Service\Parser;

interface ParserInterface
{
    //Retourne un tableau de factures
    public function parse(string $filePath): array;
}