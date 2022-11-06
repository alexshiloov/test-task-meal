<?php
declare(strict_types=1);

namespace Meals\Application\Feature\Poll\UseCase\ManagerChangesDishList;

use Meals\Application\Component\Provider\ChangeDishListProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\UserProviderInterface;
use Meals\Application\Component\Validator\Dish\IsInListValidator;
use Meals\Application\Component\Validator\Dish\IsNotInListValidator;
use Meals\Application\Component\Validator\User\HasAccessToChangeDishListValidator;

class Interactor
{
    public function __construct(
        private ChangeDishListProviderInterface $changeDishListProvider,
        private UserProviderInterface $userProvider,
        private DishProviderInterface $dishProvider,
        private IsNotInListValidator $dishIsNotInListValidator,
        private IsInListValidator $dishIsInListValidator,
        private HasAccessToChangeDishListValidator $userHasAccessToChangeDishListValidator,
    ) {}

    public function remove(int $userId, int $dishId): void
    {
        $user = $this->userProvider->getUser($userId);
        $dish = $this->dishProvider->getDish($dishId);
        $dishList = $this->dishProvider->getDishList();

        $this->userHasAccessToChangeDishListValidator->validate($user);
        $this->dishIsNotInListValidator->validate($dish, $dishList);

        $this->changeDishListProvider->remove($dish, $dishList);
    }

    public function add(int $userId, int $dishId): void
    {
        $user = $this->userProvider->getUser($userId);
        $dish = $this->dishProvider->getDish($dishId);
        $dishList = $this->dishProvider->getDishList();

        $this->userHasAccessToChangeDishListValidator->validate($user);
        $this->dishIsInListValidator->validate($dish, $dishList);

        $this->changeDishListProvider->add($dish, $dishList);
    }
}