<?php
declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class FakeDishProvider implements DishProviderInterface
{
    private Dish $dish;

    private DishList $dishList;

    public function getDish(int $dishId): Dish
    {
        return $this->dish;
    }

    public function getDishList(): DishList
    {
        return $this->dishList;
    }

    public function setDish(Dish $dish): self
    {
        $this->dish = $dish;
        return $this;
    }

    public function setDishList(DishList $dishList): self
    {
        $this->dishList = $dishList;
        return $this;
    }
}