<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Feature\Poll\UseCase\ManagerGetsPollResult\Interactor;
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
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\Fake\Provider\FakeUserProvider;
use tests\Meals\Functional\FunctionalTestCase;

class ManagerGetsPollResultTest extends FunctionalTestCase
{
    private const USER_ID = 1;
    private const EMPLOYEE_ID = 41;
    private const DISH_ID = 11;
    private const POLL_ID = 21;
    private const POLL_RESULT_ID = 321;

    public function testSuccessful(): void
    {
        $pollResults = $this->performTestMethod(
            $this->getUserWithPermissions(),
            $this->getPollResults()
        );

        Assert::assertEquals($pollResults, $this->getPollResults());
    }

    public function testUserHasNotPermissions(): void
    {
        $this->expectException(AccessDeniedException::class);

        $this->performTestMethod(
            $this->getUserWithNoPermissions(),
            $this->getPollResults()
        );
    }

    /**
     * @param User $user
     * @param PollResult[] $pollResults
     * @return PollResult[]
     * @throws \Exception
     */
    private function performTestMethod(User $user, array $pollResults): array
    {
        $this->getContainer()->get(FakeUserProvider::class)->setUser($user);

        /** @var FakePollResultProvider $fakePollResultProvider */
        $fakePollResultProvider = $this->getContainer()->get(FakePollResultProvider::class);
        $fakePollResultProvider
            ->setPollResults($pollResults)
        ;

        /** @var Interactor $interactor */
        $interactor = $this->getContainer()->get(Interactor::class);

        return $interactor->getPollResults($user->getId());
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            self::USER_ID,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_POLL_RESULTS),
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

    /**
     * @return PollResult[]
     */
    private function getPollResults(): array
    {
        $employee = new Employee(
            self::EMPLOYEE_ID,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );

        return [
            (new PollResult(
                self::POLL_RESULT_ID,
                new Poll(
                    self::POLL_ID,
                    true,
                    new Menu( 1, 'title', new DishList([]))
                ),
                $employee,
                new Dish(self::DISH_ID, 'title', 'description'),
                $employee->getFloor()
            ))
        ];
    }
}
