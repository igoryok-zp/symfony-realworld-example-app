<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\UserRepository;
use App\Utility\Context;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(
        private Context $appContext,
        private UserRepository $userRepository,
    ) {
    }

    private function isRegistered(string $email): bool
    {
        $user = $this->userRepository->findOneByEmail($email);
        return $user !== null;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $user = $this->appContext->getUser();
        if ($user && $user->getEmail() === $value) {
            return;
        }

        if ($this->isRegistered($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
