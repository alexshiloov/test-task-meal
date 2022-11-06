<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\DishIsInListException;
use Meals\Application\Component\Validator\Exception\DishIsNotInListException;
use Meals\Application\Feature\Poll\UseCase\ManagerChangesDishList\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeUserProvider;
use tests\Meals\Functional\FunctionalTestCase;
use UnexpectedValueException;

class ManagerChangesDishListTest extends FunctionalTestCase
{
    private const ADD_METHOD = 'add';
    private const REMOVE_METHOD = 'remove';

    private const USER_ID = 1;
    private const DISH_ID = 11;

    public function testSuccessfulAdd(): void
    {
        $this->expectNotToPerformAssertions();

        $this->performTestMethod(
            self::ADD_METHOD,
            $this->getUserWithPermissions(),
            $this->getDish(),
            $this->getEmptyDishList()
        );
    }

    public function testSuccessfulRemove(): void
    {
        $this->expectNotToPerformAssertions();

        $this->performTestMethod(
            self::REMOVE_METHOD,
            $this->getUserWithPermissions(),
            $this->getDish(),
            $this->getDishList()
        );
    }

    public function testUserHasNotPermissions(): void
    {
        $this->expectException(AccessDeniedException::class);

        $this->performTestMethod(
            self::ADD_METHOD,
            $this->getUserWithNoPermissions(),
            $this->getDish(),
            $this->getDishList()
        );
    }

    public function testDishIsNotInList(): void
    {
        $this->expectException(DishIsNotInListException::class);

        $this->performTestMethod(
            self::REMOVE_METHOD,
            $this->getUserWithPermissions(),
            $this->getDish(),
            $this->getEmptyDishList()
        );
    }

    public function testDishIsInList(): void
    {
        $this->expectException(DishIsInListException::class);

        $this->performTestMethod(
            self::ADD_METHOD,
            $this->getUserWithPermissions(),
            $this->getDish(),
            $this->getDishList()
        );
    }

    private function performTestMethod(string $methodName, User $user, Dish $dish, DishList $dishList): void
    {
        $this->getContainer()->get(FakeUserProvider::class)->setUser($user);

        /** @var FakeDishProvider $fakeDishProvider */
        $fakeDishProvider = $this->getContainer()->get(FakeDishProvider::class);
        $fakeDishProvider
            ->setDish($dish)
            ->setDishList($dishList)
        ;

        /** @var Interactor $interactor */
        $interactor = $this->getContainer()->get(Interactor::class);

        switch ($methodName) {
            case self::ADD_METHOD:
                $interactor->add($user->getId(), $dish->getId());
                break;
            case self::REMOVE_METHOD:
                $interactor->remove($user->getId(), $dish->getId());
                break;
            default:
                throw new UnexpectedValueException();
        }
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            self::USER_ID,
            new PermissionList(
                [
                    new Permission(Permission::CHANGE_DISH_LIST),
                ]
            ),
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            self::USER_ID,
            new PermissionList([]),
        );
    }

    private function getDish(): Dish
    {
        return new Dish(self::DISH_ID, 'title', 'description');
    }

    private function getDishList(): DishList
    {
        return new DishList([
            new Dish(self::DISH_ID, 'title', 'description')
        ]);
    }

    private function getEmptyDishList(): DishList
    {
        return new DishList([]);
    }
}
