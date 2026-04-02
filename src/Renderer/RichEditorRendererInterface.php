<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Renderer;

interface RichEditorRendererInterface
{
    public function renderElements(array $elements): string;

    public function renderElement(array $element): string;
}
