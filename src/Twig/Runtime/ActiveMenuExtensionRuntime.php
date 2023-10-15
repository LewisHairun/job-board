<?php

namespace App\Twig\Runtime;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class ActiveMenuExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function activeMenu($menuItem)
    {
        return $menuItem === $this->requestStack->getCurrentRequest()->attributes->get("_route") ? true : false;
    }
}
