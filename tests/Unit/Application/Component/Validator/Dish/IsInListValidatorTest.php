<?php
declare(strict_types=1);

namespace Unit\Application\Component\Validator\Dish;


use Meals\Application\Component\Validator\Dish\IsInListValidator;
use Meals\Application\Component\Validator\Exception\DishIsInListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class IsInListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful(): void
    {
        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(false);

        $validator = new IsInListValidator();
        verify($validator->validate($dish->reveal(), $dishList->reveal()))->null();
    }

    public function testFail(): void
    {
        $this->expectException(DishIsInListException::class);

        $dish = $this->prophesize(Dish::class);

        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(true);

        $validator = new IsInListValidator();
        $validator->validate($dish->reveal(), $dishList->reveal());
    }
}