<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use DateTime;
use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\DishIsNotInListException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\WrongTimeException;
use Meals\Application\Feature\Poll\UseCase\EmployeeMakesDishChoice\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use PHPUnit\Framework\Assert;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeMakesDishChoiceTest extends FunctionalTestCase
{
    private const USER_ID = 1;
    private const EMPLOYEE_ID = 41;
    private const DISH_ID = 11;
    private const POLL_ID = 21;

    public function testSuccessful(): void
    {
        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDishList(),
            new DateTime('2022-11-07T12:00:00')
        );

        Assert::assertEquals(4, $pollResult->getEmployeeFloor());
        Assert::assertEquals(self::DISH_ID, $pollResult->getDish()->getId());
        Assert::assertEquals(self::EMPLOYEE_ID, $pollResult->getEmployee()->getId());
        Assert::assertEquals(self::USER_ID, $pollResult->getEmployee()->getUser()->getId());
        Assert::assertEquals(self::POLL_ID, $pollResult->getPoll()->getId());
    }

    public function testUserHasNotPermissions(): void
    {
        $this->expectException(AccessDeniedException::class);

        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithNoPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDishList(),
            new DateTime('2022-11-07T12:00:00')
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testPollIsNotActive(): void
    {
        $this->expectException(PollIsNotActiveException::class);

        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(false),
            $this->getDish(),
            $this->getDishList(),
            new DateTime('2022-11-07T12:00:00')
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testTimeIsNotCorrect(): void
    {
        $this->expectException(WrongTimeException::class);

        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getDishList(),
            new DateTime('2022-11-08T12:00:00') // tuesday
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testDishIsNotInList(): void
    {
        $this->expectException(DishIsNotInListException::class);

        $pollResult = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getEmptyDishList(),
            new DateTime('2022-11-07T12:00:00')
        );

        verify($pollResult)->equals($pollResult);
    }

    private function performTestMethod(
        Employee $employee,
        Poll $poll,
        Dish $dish,
        DishList $dishList,
        DateTime $dateTime
    ): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);

        /** @var FakeDishProvider $fakeDishProvider */
        $fakeDishProvider = $this->getContainer()->get(FakeDishProvider::class);
        $fakeDishProvider
            ->setDish($dish)
            ->setDishList($dishList)
        ;

        /** @var Interactor $interactor */
        $interactor = $this->getContainer()->get(Interactor::class);

        return $interactor->choiceDish($employee->getId(), $poll->getId(), $dish->getId(), $dateTime);
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            self::EMPLOYEE_ID,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            self::USER_ID,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            self::EMPLOYEE_ID,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            self::USER_ID,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            self::POLL_ID,
            $active,
            new Menu(
                1,
                'title',
                new DishList([]),
            )
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
