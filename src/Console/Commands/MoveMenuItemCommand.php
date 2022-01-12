<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

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
 * @package XeHub\XePlugin\XeCli\Console\Commands\Migration
 */
class MoveMenuItemCommand extends Command implements CommandNameInterface
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
        $menuId = $this->argument('menu');
        $menuItemIds = $this->argument('menuItem');
        $position = $this->option('position');

        foreach ($menuItemIds as $menuItemId) {
            \XeDB::beginTransaction();

            try {
                $this->moveMenuItem($menuId, $menuItemId, $position);
                $this->outputSuccess($menuItemId);

                \XeDB::commit();
            }

            catch (Exception $exception) {
                \XeDB::rollback();
                throw $exception;
            }
        }
    }

    /**
     * Move Menu Item
     *
     * @param string $menuId
     * @param string $menuItemId
     * @param $position
     */
    protected function moveMenuItem(
        string $menuId,
        string $menuItemId,
               $position
    )
    {
        $menu = $this->menuService->findMenuOrFail($menuId);
        $menuItem = $this->menuService->findMenuItemOrFail($menuItemId);

        $this->menuService->moveMenuItem(
            $menu, $menuItem, $position
        );
    }

    /**
     * Output Success
     *
     * @param string $menuItemId
     * @return void
     */
    protected function outputSuccess(string $menuItemId)
    {
        /**
         * @todo Command Line Output 기능을 더욱 강화하는 방법에 대해 문서화하기.
         * @todo 라라벨에 Command Output Method 기능에 대해 문서화하기.
         */
        $this->output->success(
            "MenuItem ($menuItemId) Moved Successfully"
        );
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:move:menuItem';
    }
}


