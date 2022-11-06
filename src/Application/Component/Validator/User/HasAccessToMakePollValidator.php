<?php
declare(strict_types=1);

namespace Meals\Application\Component\Validator\User;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\User;

class HasAccessToMakePollValidator implements AccessValidatorInterface
{
    public function validate(User $user): void
    {
        if (!$user->getPermissions()->hasPermission(new Permission(Permission::PARTICIPATION_IN_POLLS))) {
            throw new AccessDeniedException();
        }
    }
}
