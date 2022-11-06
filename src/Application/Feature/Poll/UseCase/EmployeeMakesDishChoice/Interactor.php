<?php
declare(strict_types=1);

namespace Meals\Application\Feature\Poll\UseCase\EmployeeMakesDishChoice;

use DateTime;
use Meals\Application\Component\Provider\ChangePollResultProviderInterface;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Validator\Dish\AlreadyChosenValidator;
use Meals\Application\Component\Validator\Dish\ChoiceTimeValidator;
use Meals\Application\Component\Validator\Dish\IsNotInListValidator;
use Meals\Application\Component\Validator\User\HasAccessToMakePollValidator;
use Meals\Application\Feature\Poll\UseCase\EmployeeGetsActivePoll\Interactor as ActivePollInteractor;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private ActivePollInteractor $activePollInteractor,
        private EmployeeProviderInterface $employeeProvider,
        private DishProviderInterface $dishProvider,
        private ChangePollResultProviderInterface $changePollResultProvider,
        private ChoiceTimeValidator $dishChoiceTimeValidator,
        private IsNotInListValidator $dishIsNotInListValidator,
        private AlreadyChosenValidator $dishAlreadyChosenValidator,
        private HasAccessToMakePollValidator $accessToMakePollValidator,
    ) {}

    public function choiceDish(int $employeeId, int $pollId, int $dishId, DateTime $serverTime): PollResult
    {
        $activePoll = $this->activePollInteractor->getActivePoll($employeeId, $pollId);
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $dish = $this->dishProvider->getDish($dishId);
        $dishList = $this->dishProvider->getDishList();

        $this->accessToMakePollValidator->validate($employee->getUser());
        $this->dishAlreadyChosenValidator->validate($employee);
        $this->dishChoiceTimeValidator->validate($serverTime);
        $this->dishIsNotInListValidator->validate($dish, $dishList);

        return $this->changePollResultProvider->addPollResult(
            $activePoll,
            $dish,
            $employee
        );
    }
}