<?php
declare(strict_types=1);

namespace Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\User\AccessValidatorInterface;
use Meals\Application\Component\Validator\User\HasAccessToChangeDishListValidator;
use Meals\Application\Component\Validator\User\HasAccessToMakePollValidator;
use Meals\Application\Component\Validator\User\HasAccessToViewPollResultsValidator;
use Meals\Application\Component\Validator\User\HasAccessToViewPollsValidator;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;


class UserAccessValidatorsTest extends TestCase
{
    use ProphecyTrait;

    private const VALIDATOR_CLASSES = [
        HasAccessToViewPollsValidator::class,
        HasAccessToViewPollResultsValidator::class,
        HasAccessToMakePollValidator::class,
        HasAccessToChangeDishListValidator::class
    ];

    /**
     * @dataProvider successfulDataProvider
     */
    public function testSuccessful(ObjectProphecy $permissionList): void
    {
        foreach (self::VALIDATOR_CLASSES as $class) {
            $user = $this->prophesize(User::class);
            $user->getPermissions()->willReturn($permissionList->reveal());

            /** @var AccessValidatorInterface $validator */
            $validator = new $class();
            verify($validator->validate($user->reveal()))->null();
        }
    }

    /**
     * @dataProvider failDataProvider
     */
    public function testFail(ObjectProphecy $permissionList): void
    {
        foreach (self::VALIDATOR_CLASSES as $class) {
            $user = $this->prophesize(User::class);
            $user->getPermissions()->willReturn($permissionList->reveal());

            /** @var AccessValidatorInterface $validator */
            $validator = new $class();
            try {
                $validator->validate($user->reveal());
                $this->fail('AccessDeniedException was not thrown');
            } catch (AccessDeniedException $e) {
                $this->assertEmpty($e->getMessage());
            }
        }
    }

    public function successfulDataProvider(): iterable
    {
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::VIEW_ACTIVE_POLLS)->willReturn(true);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(true);
        $permissionList->hasPermission(Permission::CHANGE_DISH_LIST)->willReturn(true);
        $permissionList->hasPermission(Permission::VIEW_POLL_RESULTS)->willReturn(true);

        return [
            [$permissionList],
        ];
    }

    public function failDataProvider(): iterable
    {
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::VIEW_ACTIVE_POLLS)->willReturn(false);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(false);
        $permissionList->hasPermission(Permission::CHANGE_DISH_LIST)->willReturn(false);
        $permissionList->hasPermission(Permission::VIEW_POLL_RESULTS)->willReturn(false);

        return [
            [$permissionList],
        ];
    }
}