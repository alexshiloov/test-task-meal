<?php
declare(strict_types=1);

namespace Meals\Application\Component\Validator\User;


use Meals\Domain\User\User;

interface AccessValidatorInterface
{
    public function validate(User $user): void;
}