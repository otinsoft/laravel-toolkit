<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Users\HasRole;
use Otinsoft\Toolkit\Auth\HasVerification;
use Otinsoft\Toolkit\Users\User as Authenticatable;

class User extends Authenticatable
{
    use HasRole,
        HasVerification;

    public $timestamps = false;
}
