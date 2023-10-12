<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\TermGeneralExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GeneralTermExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('generalTerm', [generalTermExtensionRuntime::class, 'termGeneral']),
        ];
    }
}
