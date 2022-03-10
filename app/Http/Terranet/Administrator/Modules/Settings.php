<?php

namespace App\Http\Terranet\Administrator\Modules;

use Terranet\Administrator\Contracts\Module\Editable;
use Terranet\Administrator\Contracts\Module\Navigable;
use Terranet\Administrator\Contracts\Module\Validable;
use Terranet\Administrator\Scaffolding;
use Terranet\Administrator\Traits\Module\HasOptions;
use Terranet\Administrator\Traits\Module\ValidatesOptions;
use Terranet\Options\Option;

class Settings extends Scaffolding implements Navigable, Editable, Validable
{
    use HasOptions, ValidatesOptions;

    protected $model = Option::class;

    /**
     * The module url
     *
     * @return string
     */
    public function url(): string
    {
        return 'settings';
    }

    /**
     * The module title
     *
     * @return string
     */
    public function title(): string
    {
        return trans("administrator::module.resources.settings");
    }

    /**
     * Navigation group which Resource belongs to
     *
     * @return string
     */
    public function group()
    {
        return null;
    }

    /**
     * Navigation container which Resource belongs to
     * Available: sidebar, tools
     *
     * @return mixed
     */
    public function navigableIn()
    {
        return Navigable::MENU_TOOLS;
    }

    public function linkAttributes()
    {
        return ['icon' => 'fa fa-cog'];
    }

    public function showIf()
	{
		return auth()->user()->is_admin_roles;
	}

	/**
	 * config/menus.php => 'ordering' => true // Ordering need to activate
	 *
	 * @return int
	 */
	public function order():int
	{
		return 0;
	}
}
