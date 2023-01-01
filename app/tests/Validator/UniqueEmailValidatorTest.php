<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utility\Context;
use App\Validator\UniqueEmail;
use App\Validator\UniqueEmailValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueEmailValidatorTest extends ConstraintValidatorTestCase
{
    private $appContext;
    private $userRepository;

    protected function createUserRepositoryMock()
    {
        return $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->addMethods(['findOneByEmail'])
            ->getMock();
    }

    protected function createValidator(): UniqueEmailValidator
    {
        $this->appContext = $this->createMock(Context::class);
        $this->userRepository = $this->createUserRepositoryMock();
        return new UniqueEmailValidator($this->appContext, $this->userRepository);
    }

    public function testUnexpectedType()
    {
        $this->expectException(UnexpectedTypeException::class);

        $value = 'test@app.test';
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate($value, $constraint);
    }

    public function testNullIsValid()
    {
        $value = null;
        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testUnexpectedValue()
    {
        $this->expectException(UnexpectedValueException::class);

        $value = [];
        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);
    }

    public function testContextUserEmailIsValid()
    {
        $value = 'test@app.test';

        $user = new User();
        $user->setEmail($value);

        $this->appContext
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testWithUser()
    {
        $value = 'test@app.test';

        $this->userRepository
            ->expects($this->once())
            ->method('findOneByEmail')
            ->with($value)
            ->willReturn(new User());

        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ email }}', $value)
            ->assertRaised();
    }
}
