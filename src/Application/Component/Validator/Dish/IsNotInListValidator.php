<?php
declare(strict_types=1);

namespace Meals\Application\Component\Validator\Dish;


use Meals\Application\Component\Validator\Exception\DishIsNotInListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class IsNotInListValidator
{
    public function validate(Dish $dish, DishList $dishList): void
    {
        if (!$dishList->hasDish($dish)) {
            throw new DishIsNotInListException();
        }
    }
}