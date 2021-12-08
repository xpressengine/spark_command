<?php

namespace XeHub\XePlugin\XeCli\Commands\Helper;

use Exception;
use Illuminate\Console\Command;
use XeHub\XePlugin\XeCli\Services\MenuService;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class MoveMenuItemCommand
 *
 * Move Menu Item Command
 * 메뉴 아이템 순서를 변경하는 커멘드
 * Command => xe_cli:move:menuItem
 *
 * @package XeHub\XePlugin\XeCli\Commands\Migration
 */
class MoveMenuItemCommand extends Command
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:move:menuItem
        {menu}
        {menuItem*}
        {--position=}';

    /**
     * @var string
     */
    protected $description = 'Move Menu Item';

    /**
     * @var MenuService
     */
    protected $menuService;

    /**
     * MoveMenuItemCommand __construct
     */
    public function __construct()
    {
        $this->menuService = MenuService::make();
        parent::__construct();
    }

    /**
     * Move Menu Item Handle
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        \XeDB::beginTransaction();

        $menuId = $this->argument('menu');
        $menuItemIds = $this->argument('menuItem');
        $position = $this->option('position');

        foreach ($menuItemIds as $menuItemId) {
            try {
                $menu = $this->menuService->findMenuOrFail($menuId);
                $menuItem = $this->menuService->findMenuItemOrFail($menuItemId);

                $this->menuService->moveMenuItem(
                    $menu, $menuItem, $position
                );

                /**
                 * @todo Command Line Output 기능을 더욱 강화하는 방법에 대해 문서화하기.
                 * @todo 라라벨에 Command Output Method 기능에 대해 문서화하기.
                 */
                $this->output->success(
                    "MenuItem ($menuItemId) Moved Successfully"
                );

                \XeDB::commit();
            }

            catch (Exception $exception) {
                \XeDB::rollback();
                throw $exception;
            }
        }
    }
}


