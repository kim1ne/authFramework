<?php

namespace App\Services\Db;

class Transaction
{
    private string $sql;

    public function transact(string $sql): string
    {
        return
            'BEGIN;' . "\n" .
            $sql . "\n" .
            'COMMIT;' . "\n";
    }
}