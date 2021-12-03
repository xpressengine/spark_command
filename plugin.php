<?php

namespace XeHub\XePlugin\XeCli;

use Xpressengine\Plugin\AbstractPlugin;

/**
 * Class Plugin
 *
 * Xe Cli Plugin
 *
 * @package XeHub\XePlugin\XeCli
 */
final class Plugin extends AbstractPlugin
{
    /**
     * @return void
     */
    public function boot()
    {
        Services\MenuService::singleton();

        // Widget Commands
        Commands\Widget\MakeWidget::register();

        // Skin Commands
        Commands\Skin\MakeErrorSkinCommand::register();
        Commands\Skin\MakeUserAuthSkinCommand::register();
        Commands\Skin\MakeUserProfileSkin::register();
        Commands\Skin\MakeUserSettingsSkin::register();

        // Migration Commands
        Commands\Migration\MigrateQueueDatabaseCommand::register();
        Commands\Migration\MigrateSessionDatabaseCommend::register();

        // Helper Commands
        Commands\Helper\MoveMenuItemCommand::register();
        Commands\Helper\SetOrderMenuItemCommand::register();
    }
}
