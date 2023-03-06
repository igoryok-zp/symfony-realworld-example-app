<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function preProcess(string $fixtureId, object $object): void
    {
        if ($object instanceof User) {
            $object->setPassword($this->passwordHasher->hashPassword($object, (string) $object->getPassword()));
        }
    }

    /**
     * @inheritdoc
     */
    public function postProcess(string $fixtureId, object $object): void
    {
        // do nothing
    }
}
