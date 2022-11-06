<?php
declare(strict_types=1);

namespace Meals\Application\Component\Provider;


use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

interface ChangeDishListProviderInterface
{
    public function add(Dish $dish, DishList $dishList): void;

    public function remove(Dish $dish, DishList $dishList): void;
}