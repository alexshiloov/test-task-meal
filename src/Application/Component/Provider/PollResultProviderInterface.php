<?php
declare(strict_types=1);

namespace Meals\Application\Component\Provider;


use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;

interface PollResultProviderInterface
{
    public function getPollResultByEmployee(Employee $employee): ?PollResult;

    /** @return PollResult[] */
    public function getPollResults(): array;
}