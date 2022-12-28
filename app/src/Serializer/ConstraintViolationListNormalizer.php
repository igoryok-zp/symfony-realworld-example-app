<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListNormalizer implements NormalizerInterface
{
    private function getMessages(ConstraintViolationListInterface $volations): array
    {
        $messages = [];
        foreach ($volations as $violation) {
            $propertyPath = $violation->getPropertyPath() ? $violation->getPropertyPath() . ': ' : '';
            $messages[] = $propertyPath . $violation->getMessage();
        }
        return $messages;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return 'jsonproblem' === $format && $data instanceof ConstraintViolationListInterface;
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'errors' => [
                'body' => $this->getMessages($object),
            ],
        ];
    }
}
