<?php
declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;


use Meals\Application\Component\Provider\UserProviderInterface;
use Meals\Domain\User\User;

class FakeUserProvider implements UserProviderInterface
{
    private User $user;

    public function getUser(int $userId): User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}