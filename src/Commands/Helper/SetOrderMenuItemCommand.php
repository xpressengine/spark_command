<?php

namespace XeHub\XePlugin\XeCli\Commands\Helper;

use Exception;
use Illuminate\Console\Command;
use XeHub\XePlugin\XeCli\Services\MenuService;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class SetOrderMenuItemCommand
 *
 * 메뉴 아이템에 대한 순서를 변경하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Helper
 */
class SetOrderMenuItemCommand extends Command
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:setOrder:menuItem
        {menuItem}
        {position}';

    /**
     * @var string
     */
    protected $description = "Set Menu Item's Ordering";

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
        $menuItemId = $this->argument('menuItem');
        $position = $this->argument('position');

        $menuItem = $this->menuService->findMenuItemOrFail($menuItemId);
        $this->menuService->menuHandler()->setOrder($menuItem, $position);

        $this->output->success('Successfully Changed Order.');
    }
}