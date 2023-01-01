<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Utility\Context;
use App\Validator\UniqueUsername;
use App\Validator\UniqueUsernameValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueUsernameValidatorTest extends ConstraintValidatorTestCase
{
    private $appContext;
    private $profileRepository;

    protected function createProfileRepositoryMock()
    {
        return $this->getMockBuilder(ProfileRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->addMethods(['findOneByUsername'])
            ->getMock();
    }

    protected function createValidator(): UniqueUsernameValidator
    {
        $this->appContext = $this->createMock(Context::class);
        $this->profileRepository = $this->createProfileRepositoryMock();
        return new UniqueUsernameValidator($this->appContext, $this->profileRepository);
    }

    public function testUnexpectedType()
    {
        $this->expectException(UnexpectedTypeException::class);

        $value = 'test';
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate($value, $constraint);
    }

    public function testNullIsValid()
    {
        $value = null;
        $constraint = new UniqueUsername();
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testUnexpectedValue()
    {
        $this->expectException(UnexpectedValueException::class);

        $value = [];
        $constraint = new UniqueUsername();
        $this->validator->validate($value, $constraint);
    }

    public function testContextProfileUsernameIsValid()
    {
        $value = 'test';

        $profile = new Profile();
        $profile->setUsername($value);

        $user = new User();
        $user->setProfile($profile);

        $this->appContext
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $constraint = new UniqueUsername();
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testWithUser()
    {
        $value = 'test';

        $this->profileRepository
            ->expects($this->once())
            ->method('findOneByUsername')
            ->with($value)
            ->willReturn(new Profile());

        $constraint = new UniqueUsername();
        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ username }}', $value)
            ->assertRaised();
    }
}
