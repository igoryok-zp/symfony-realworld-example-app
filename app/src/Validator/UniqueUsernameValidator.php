<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\ProfileRepository;
use App\Utility\Context;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private Context $appContext,
        private ProfileRepository $profileRepository,
    ) {
    }

    private function isRegistered(string $username): bool
    {
        $profile = $this->profileRepository->findOneByUsername($username);
        return $profile !== null;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($constraint, UniqueUsername::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $user = $this->appContext->getUser();
        if ($user && $user->getProfile()->getUsername() === $value) {
            return;
        }

        if ($this->isRegistered($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $value)
                ->addViolation();
        }
    }
}
