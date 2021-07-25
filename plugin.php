<?php

namespace XeHub\XePlugin\XeCli;

use XeHub\XePlugin\XeCli\Commands\Error\ErrorSkinMake;
use XeHub\XePlugin\XeCli\Commands\Queue\QueueDatabaseMigrate;
use XeHub\XePlugin\XeCli\Commands\Session\SessionDatabaseMigrate;
use XeHub\XePlugin\XeCli\Commands\User\UserAuthSkinMake;
use XeHub\XePlugin\XeCli\Commands\User\UserProfileSkinMake;
use XeHub\XePlugin\XeCli\Commands\User\UserSettingsSkinMake;
use XeHub\XePlugin\XeCli\Commands\Widget\WidgetMake;
use Xpressengine\Plugin\AbstractPlugin;

final class Plugin extends AbstractPlugin
{
    /**
     * @return void
     */
    public function boot()
    {
        /** Widgets */
        WidgetMake::register();

        /** Users */
        UserAuthSkinMake::register();
        UserProfileSkinMake::register();
        UserSettingsSkinMake::register();

        /** Errors */
        ErrorSkinMake::register();

        /** Migrate */
        SessionDatabaseMigrate::register();
        QueueDatabaseMigrate::register();
    }
}
