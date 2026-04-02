<?php

declare(strict_types=1);

namespace LWC\SyliusNotifierPlugin\Serializer;

use LWC\SyliusNotifierPlugin\Entity\NotificationInterface;
use LWC\SyliusNotifierPlugin\Renderer\RichEditorRendererInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmozart\Assert\Assert;

final class NotificationNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'notification_normalizer_already_called';

    public function __construct(
        private readonly RichEditorRendererInterface $richEditorRenderer,
    ) {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        Assert::isInstanceOf($object, NotificationInterface::class);
        Assert::keyNotExists($context, self::ALREADY_CALLED);

        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);
        $date = $object->getStartDate() ?? $object->getCreatedAt();

        if ($date instanceof \DateTimeInterface) {
            $data['date'] = $this->getTimeAgoFormat($date);
        }

        if (isset($data['body']) && $parts = json_decode($data['body'], true)) {
            $data['label'] = $this->richEditorRenderer->renderElements($parts);
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof NotificationInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            NotificationInterface::class => false,
        ];
    }

    private function getTimeAgoFormat(\DateTimeInterface $dateTime): string
    {
        $now = new \DateTime();
        $timeDifference = $now->getTimestamp() - $dateTime->getTimestamp();

        if ($timeDifference < 1) {
            return '1 sec ago';
        }

        if ($timeDifference > 24 * 60 * 60) {
            return $dateTime->format('d M');
        }

        $condition = [
            60 * 60 => 'h',
            60 => 'min',
            1 => 'sec',
        ];

        foreach ($condition as $secs => $str) {
            $d = $timeDifference / $secs;

            if ($d >= 1) {
                $t = round($d);

                return $t . ' ' . $str . ' ago';
            }
        }

        return '1 sec ago';
    }
}
