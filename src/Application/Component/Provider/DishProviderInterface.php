<?php
declare(strict_types=1);

namespace Meals\Application\Component\Provider;


use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

interface DishProviderInterface
{
    public function getDish(int $dishId): Dish;

    public function getDishList(): DishList;
}