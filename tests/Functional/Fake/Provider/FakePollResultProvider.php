<?php
declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    private ?PollResult $pollResultByEmployee = null;

    /** @var PollResult[] */
    private array $pollResults = [];

    public function getPollResultByEmployee(Employee $employee): ?PollResult
    {
        return $this->pollResultByEmployee;
    }

    public function getPollResults(): array
    {
        return $this->pollResults;
    }

    public function setPollResultByEmployee(?PollResult $pollResultByEmployee): self
    {
        $this->pollResultByEmployee = $pollResultByEmployee;
        return $this;
    }

    /**
     * @param PollResult[] $pollResults
     * @return FakePollResultProvider
     */
    public function setPollResults(array $pollResults): self
    {
        $this->pollResults = $pollResults;
        return $this;
    }
}