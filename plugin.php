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
        // Service singletons
        Services\MenuService::singleton();
        Services\PluginService::singleton();
        Services\StubFileService::singleton();

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
        Commands\Migration\MakeMigrationTableCommandClass::register();
        Commands\Migration\MakeMigrationResourceCommandClass::register();

        // Helper Commands
        Commands\Helper\MoveMenuItemCommand::register();
        Commands\Helper\SetOrderMenuItemCommand::register();

        // Controller Commands
        Commands\Controller\MakeControllerCommandClass::register();
        Commands\Controller\MakeBackOfficeControllerCommand::register();
        Commands\Controller\MakeClientControllerCommand::register();

        // Handler Commands
        Commands\Handler\MakeHandlerCommandClass::register();
        Commands\Handler\MakeValidationHandlerCommand::register();
        Commands\Handler\MakeMessageHandlerCommand::register();

        // Model Commands
        Commands\Model\MakeModelCommandClass::register();

        // View Commands
        Commands\View\MakeBackOfficeIndexViewCommand::register();
        Commands\View\MakeBackOfficeShowViewCommand::register();
        Commands\View\MakeBackOfficeCreateViewCommand::register();
        Commands\View\MakeBackOfficeEditViewCommand::register();
    }
}
