<?php

namespace App\Services\Db\Orm\DataManager;

abstract class Schema
{
    protected string $sql;
    protected string $begin = '';
    protected string $commit = '';
    protected string $end;

    protected array $compare = [
        '<', '>', '='
    ];

    protected function getQuerySnippet(array $round, string $symbol = '', string $defaultSymbol = ', '): string
    {
        $condition = '';
        foreach ($round as $key => $value) {
            $condition .= "'$value'";

            unset($round[$key]);

            $sign = null;
            if (count($round) > 0) $sign = $defaultSymbol;
            $condition .= $sign ?? $symbol;
        }
        return $condition;
    }
}