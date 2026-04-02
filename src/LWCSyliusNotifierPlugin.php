<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class LWCSyliusNotifierPlugin extends AbstractBundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
