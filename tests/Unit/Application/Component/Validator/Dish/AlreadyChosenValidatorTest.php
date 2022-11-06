<?php
declare(strict_types=1);

namespace Unit\Application\Component\Validator\Dish;


use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\Dish\AlreadyChosenValidator;
use Meals\Application\Component\Validator\Exception\DishIsAlreadyChosenException;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;
use PHPUnit\Framework\TestCase;

class AlreadyChosenValidatorTest extends TestCase
{
    public function testSuccessful(): void
    {
        $pollResultProvider = $this->createMock(PollResultProviderInterface::class);
        $pollResultProvider->method('getPollResultByEmployee')->willReturn(null);

        $validator = new AlreadyChosenValidator($pollResultProvider);
        verify($validator->validate($this->createMock(Employee::class)))->null();
    }

    public function testFailure(): void
    {
        $this->expectException(DishIsAlreadyChosenException::class);

        $pollResultProvider = $this->createMock(PollResultProviderInterface::class);
        $pollResultProvider->method('getPollResultByEmployee')->willReturn($this->createMock(PollResult::class));

        $validator = new AlreadyChosenValidator($pollResultProvider);
        $validator->validate($this->createMock(Employee::class));
    }
}