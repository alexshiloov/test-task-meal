<?php
declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\ChangePollResultProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakeChangePollResultProvider implements ChangePollResultProviderInterface
{
    public function addPollResult(Poll $poll, Dish $dish, Employee $employee): PollResult
    {
        $id = time();

        return (new PollResult(
            $id,
            $poll,
            $employee,
            $dish,
            $employee->getFloor()
        ));
    }
}