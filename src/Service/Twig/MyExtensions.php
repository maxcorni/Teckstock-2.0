<?php

namespace App\Service\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyExtensions extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('statusStock', [$this, 'statusStock']),
        ];
    }

    public function statusStock(int $stock): string
    {
        if($stock > 0) {
            return '🟢';
        } else {
            return '🔴';
        }
    }
}