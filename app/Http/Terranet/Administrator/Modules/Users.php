<?php

namespace App\Http\Terranet\Administrator\Modules;


use Terranet\Administrator\Collection\Mutable as MutableCollection;
use Terranet\Administrator\Field\Enum;
use Terranet\Administrator\Modules\Users as CoreUsersModule;

/**
 * Administrator Users Module.
 */
class Users extends CoreUsersModule
{
    protected $showActions = false;

}
