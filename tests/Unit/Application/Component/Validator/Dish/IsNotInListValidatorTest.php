<?php
declare(strict_types=1);

namespace Unit\Application\Component\Validator\Dish;


use Meals\Application\Component\Validator\Dish\IsNotInListValidator;
use Meals\Application\Component\Validator\Exception\DishIsNotInListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class IsNotInListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful(): void
    {
        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(true);

        $validator = new IsNotInListValidator();
        verify($validator->validate($dish->reveal(), $dishList->reveal()))->null();
    }

    public function testFail(): void
    {
        $this->expectException(DishIsNotInListException::class);

        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(false);

        $validator = new IsNotInListValidator();
        $validator->validate($dish->reveal(), $dishList->reveal());
    }
}