<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\LegalNoticeExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LegalNoticeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('legalNotice', [LegalNoticeExtensionRuntime::class, 'legalNotice']),
        ];
    }
}
