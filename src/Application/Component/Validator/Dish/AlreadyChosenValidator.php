<?php
declare(strict_types=1);

namespace Meals\Application\Component\Validator\Dish;


use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\Exception\DishIsAlreadyChosenException;
use Meals\Domain\Employee\Employee;

class AlreadyChosenValidator
{
    public function __construct( private PollResultProviderInterface $pollResultProvider)
    {}

    public function validate(Employee $employee): void
    {
        if ($this->pollResultProvider->getPollResultByEmployee($employee)) {
            throw new DishIsAlreadyChosenException();
        }
    }
}