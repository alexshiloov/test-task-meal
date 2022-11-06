<?php
declare(strict_types=1);

namespace Meals\Application\Feature\Poll\UseCase\ManagerGetsPollResult;

use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Provider\UserProviderInterface;
use Meals\Application\Component\Validator\User\HasAccessToViewPollResultsValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private PollResultProviderInterface $pollResultProvider,
        private HasAccessToViewPollResultsValidator $hasAccessToViewPollResultsValidator,
    ) {}

    /**
     * @param int $userId
     * @return PollResult[]
     */
    public function getPollResults(int $userId): array
    {
        $user = $this->userProvider->getUser($userId);

        $this->hasAccessToViewPollResultsValidator->validate($user);

        return $this->pollResultProvider->getPollResults();
    }
}