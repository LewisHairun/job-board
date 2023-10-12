<?php

namespace App\Twig\Runtime;

use App\Repository\GeneralTermRepository;
use Twig\Extension\RuntimeExtensionInterface;

class GeneralTermExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private GeneralTermRepository $generalTermRepository)
    {
    }

    public function generalTerm()
    {
        $generalTerm = $this->generalTermRepository->getContent();

        return $generalTerm;
    }
}
