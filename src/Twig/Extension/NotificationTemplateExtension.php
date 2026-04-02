<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class NotificationTemplateExtension extends AbstractExtension
{
    public function __construct(
        private readonly Environment $twig,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('render_notification_template', [$this, 'getRenderNotificationTemplate']),
            new TwigFilter('replace_empty_variable', [$this, 'replacedEmptyVariable']),
        ];
    }

    public function replacedEmptyVariable(string $content, ?string $message = null): string
    {
        $replace = $message;

        if (null === $replace) {
            $replace = $this->translator->trans('lwc_sylius_notifier.ui.not_applicable');
        }

        return preg_replace('/%(\w+)%/', $replace, $content);
    }

    public function getRenderNotificationTemplate($content, array $data = [], ?bool $isPreview = false)
    {
        $elements = json_decode($content, true);

        if (is_array($elements)) {
            $elements = array_filter($elements, static function (array $item) use ($data): bool {
                $sectionCode = $item['data']['section'] ?? 'default';
                if ($item['code'] !== 'monsieurbiz.text' || $sectionCode === 'default') {
                    return true;
                }

                return false;
            });

            $content = json_encode($elements);
        }

        if ($isPreview) {
            return $content;
        }

        return $this->twig->createTemplate($content)->render($data);
    }
}
