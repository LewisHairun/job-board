<?php

namespace App\Twig\Runtime;

use App\Repository\LegalNoticeRepository;
use Twig\Extension\RuntimeExtensionInterface;

class LegalNoticeExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private LegalNoticeRepository $legalNoticeRepository)
    {
    }

    public function legalNotice()
    {
        $legalNotice = $this->legalNoticeRepository->getContent();

        return $legalNotice;
    }
}
