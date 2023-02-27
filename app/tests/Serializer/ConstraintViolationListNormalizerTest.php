<?php

declare(strict_types=1);

namespace App\Tests\Serializer;

use App\Config\CommentConfig;
use App\Dto\CommentDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConstraintViolationListNormalizerTest extends KernelTestCase
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        /** @var SerializerInterface */
        $serializer = static::getContainer()->get(SerializerInterface::class);
        $this->assertInstanceOf(SerializerInterface::class, $serializer);
        $this->serializer = $serializer;

        /** @var ValidatorInterface */
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $this->assertInstanceOf(ValidatorInterface::class, $validator);
        $this->validator = $validator;
    }

    public function testNormalize(): void
    {
        $comment = new CommentDto();

        $errors = $this->validator->validate($comment, groups: [CommentConfig::VALID]);

        $this->assertCount(1, $errors);

        $json = $this->serializer->serialize($errors, 'jsonproblem');

        $this->assertJson($json);
        $this->assertEquals('{"errors":{"body":["body: This value should not be blank."]}}', $json);
    }
}
