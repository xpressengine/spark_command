<?php

namespace XeHub\XePlugin\XeCli\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Xpressengine\Menu\MenuHandler;
use Xpressengine\Menu\Models\Menu;
use Xpressengine\Menu\Models\MenuItem;
use Xpressengine\Permission\PermissionHandler;

/**
 * Class MenuService
 *
 * 메뉴 서비스
 *
 * @package XeHub\XePlugin\XeCli\Services
 */
class MenuService
{
    /**
     * @var PermissionHandler
     */
    protected $permissionHandler;

    /**
     * @var MenuHandler
     */
    protected $menuHandler;

    /**
     * 싱글톤 등록
     *
     * @return void
     */
    public static function singleton()
    {
        app()->singleton(__CLASS__, function () {
            $permissionHandler = app(PermissionHandler::class);
            $menuHandler = app(MenuHandler::class);

            return new self(
                $permissionHandler,
                $menuHandler
            );
        });
    }

    /**
     * @return MenuService
     */
    public static function make(): MenuService
    {
        return app(__CLASS__);
    }

    /**
     * MoveMenuItemCommand __construct
     */
    public function __construct(
        PermissionHandler $permissionHandler,
        MenuHandler       $menuHandler
    )
    {
        $this->permissionHandler = $permissionHandler;
        $this->menuHandler = $menuHandler;
    }

    /**
     * @return MenuHandler
     */
    public function menuHandler()
    {
        return $this->menuHandler;
    }

    /**
     * Find Menu Or Fail
     *
     * @param string $menu
     * @return Menu
     * @throws ModelNotFoundException
     */
    public function findMenuOrFail(
        string $menu
    )
    {
        return $this->menuHandler->menus()->findOrFail($menu);
    }

    /**
     * Find Menu Item Or Fail
     *
     * @param string $menuItem
     * @return MenuItem
     * @throws ModelNotFoundException
     */
    public function findMenuItemOrFail(
        string $menuItem
    )
    {
        return $this->menuHandler->items()->findOrFail($menuItem);
    }

    /**
     * Move Menu Item
     *
     * @param Menu $menu
     * @param MenuItem $menuItem
     * @param int $ordering
     */
    public function moveMenuItem(
        Menu     $menu,
        MenuItem $menuItem,
        int      $ordering
    )
    {
        $oldMenuItem = clone $menuItem;
        $oldMenuItem->load('ancestors');

        $item = $this->menuHandler->moveItem(
            $menu, $menuItem
        );

        $this->menuHandler->setOrder(
            $item, $ordering
        );

        $this->menuHandler->moveItemConfig(
            $oldMenuItem, $menuItem
        );

        $toKey = $this->menuHandler->permKeyString($menuItem);
        $toKey = substr($toKey, 0, strrpos($toKey, '.'));

        $from = $this->menuHandler->permKeyString($oldMenuItem);

        $permission = $this->permissionHandler->get(
            $from, \XeSite::getCurrentSiteKey()
        );

        $this->permissionHandler->move($permission, $toKey);
    }
}