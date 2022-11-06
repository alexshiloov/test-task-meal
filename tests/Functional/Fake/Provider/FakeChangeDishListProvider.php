<?php
declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\ChangeDishListProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class FakeChangeDishListProvider implements ChangeDishListProviderInterface
{
    public function add(Dish $dish, DishList $dishList): void
    {
    }

    public function remove(Dish $dish, DishList $dishList): void
    {
    }
}