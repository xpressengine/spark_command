<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use XeHub\XePlugin\XeCli\Services\MenuService;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class SetPositionMenuItemCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class SetPositionMenuItemCommand extends Command implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:setPosition:menuItem
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

        $this->outputSuccess();
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Successfully Changed Order.');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:setPosition:menuItem';
    }
}