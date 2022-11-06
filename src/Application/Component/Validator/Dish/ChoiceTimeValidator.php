<?php
declare(strict_types=1);

namespace Meals\Application\Component\Validator\Dish;


use Meals\Application\Component\Validator\Exception\WrongTimeException;

class ChoiceTimeValidator
{
    public function validate($dateTime): void
    {
        if ((int)$dateTime->format('N') !== 1) {
            throw new WrongTimeException();
        }

        if ($dateTime->format('G') < 6 || $dateTime->format('G') >= 22) {
            throw new WrongTimeException();
        }
    }
}