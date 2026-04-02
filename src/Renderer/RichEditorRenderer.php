<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Renderer;

use MonsieurBiz\SyliusRichEditorPlugin\Exception\UiElementNotFoundException;
use MonsieurBiz\SyliusRichEditorPlugin\UiElement\RegistryInterface;
use Twig\Environment;

final class RichEditorRenderer implements RichEditorRendererInterface
{
    private RegistryInterface $uiElementRegistry;

    private Environment $twig;

    public function __construct(RegistryInterface $uiElementRegistry, Environment $twig)
    {
        $this->uiElementRegistry = $uiElementRegistry;
        $this->twig = $twig;
    }

    public function renderElements(array $elements): string
    {
        $html = '';
        foreach ($elements as $element) {
            try {
                $html .= $this->renderElement($element);
            } catch (UiElementNotFoundException $e) {
                continue;
            }
        }

        return $html;
    }

    public function renderElement(array $element): string
    {
        if (!isset($element['code'])) {
            if (!isset($element['type'], $element['fields'])) {
                throw new UiElementNotFoundException('unknown');
            }
            $element = [
                'code' => $element['type'],
                'data' => $element['fields'],
            ];
        }

        $uiElement = $this->uiElementRegistry->getUiElement($element['code']);
        $template = $uiElement->getFrontRenderTemplate();

        return $this->twig->render($template, [
            'ui_element' => $uiElement,
            'element' => $element['data'],
        ]);
    }
}
