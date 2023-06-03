<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListNormalizer implements NormalizerInterface
{
    /**
     * @param ConstraintViolationListInterface $volations
     * @return string[]
     */
    private function getMessages(ConstraintViolationListInterface $volations): array
    {
        $messages = [];
        foreach ($volations as $violation) {
            $propertyPath = $violation->getPropertyPath() ? $violation->getPropertyPath() . ': ' : '';
            $messages[] = $propertyPath . $violation->getMessage();
        }
        return $messages;
    }

    /**
     * @param string|null $format
     * @return array<class-string|'*'|'object'|string, bool|null>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ConstraintViolationListInterface::class => true,
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @param mixed[] $context
     * @return boolean
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return 'jsonproblem' === $format && $data instanceof ConstraintViolationListInterface;
    }

    /**
     * @param ConstraintViolationListInterface $object
     * @param string|null $format
     * @param mixed[] $context
     * @return mixed[]
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'errors' => [
                'body' => $this->getMessages($object),
            ],
        ];
    }
}
