<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Entity\User;
use App\Fixture\UserProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessorTest extends TestCase
{
    private $passwordHasher;

    private function createProcessor()
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        return new UserProcessor($this->passwordHasher);
    }

    public function testPreProcess()
    {
        $processor = $this->createProcessor();

        $user = new User();
        $pswdPlain = 'pswd';
        $user->setPassword($pswdPlain);

        $pswdHash = md5($pswdPlain);
        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($user, $pswdPlain)
            ->willReturn($pswdHash);

        $processor->preProcess('test', $user);

        $this->assertEquals($pswdHash, $user->getPassword());
    }

    public function testPostProcess()
    {
        $processor = $this->createProcessor();

        $user = new User();

        $this->passwordHasher->expects($this->never())
            ->method('hashPassword');

        $processor->postProcess('test', $user);
    }
}
