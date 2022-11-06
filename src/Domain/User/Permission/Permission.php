<?php

declare(strict_types=1);

namespace Meals\Domain\User\Permission;

use MyCLabs\Enum\Enum;

class Permission extends Enum
{
    public const VIEW_ACTIVE_POLLS = 'viewActivePolls'; // возможность видеть активные опросы
    public const PARTICIPATION_IN_POLLS = 'participationInPolls'; // возможность участвовать в опросах
    public const CHANGE_DISH_LIST = 'changeDishList'; // возможность менять состав блюд
    public const VIEW_POLL_RESULTS = 'viewPollResults'; // возможность смотреть результаты опроса
}
