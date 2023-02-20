<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utility\Context;
use App\Validator\UniqueEmail;
use App\Validator\UniqueEmailValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<UniqueEmailValidator>
 */
class UniqueEmailValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var MockObject&Context
     */
    private $appContext;

    /**
     * @var MockObject&UserRepository
     */
    private $userRepository;

    /**
     * @return MockObject&UserRepository
     */
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

    public function testUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $value = 'test@app.test';
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate($value, $constraint);
    }

    public function testNullIsValid(): void
    {
        $value = null;
        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public function testUnexpectedValue(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $value = [];
        $constraint = new UniqueEmail();
        $this->validator->validate($value, $constraint);
    }

    public function testContextUserEmailIsValid(): void
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

    public function testWithUser(): void
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
