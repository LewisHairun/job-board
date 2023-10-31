<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AuthorizedExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AuthorizedExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('can', [AuthorizedExtensionRuntime::class, 'can']),
        ];
    }
}
