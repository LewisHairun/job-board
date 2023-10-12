<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GeneralTermExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GeneralTermExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('generalTerm', [GeneralTermExtensionRuntime::class, 'generalTerm']),
        ];
    }
}
