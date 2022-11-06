<?php
declare(strict_types=1);

namespace Meals\Application\Component\Provider;


use Meals\Domain\User\User;

interface UserProviderInterface
{
    public function getUser(int $userId): User;
}