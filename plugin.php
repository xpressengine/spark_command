<?php

namespace SparkWeb\XePlugin\SparkCommand;

use SparkWeb\XePlugin\SparkCommand\Commands\Error\ErrorSkinMake;
use SparkWeb\XePlugin\SparkCommand\Commands\Queue\QueueDatabaseMigrate;
use SparkWeb\XePlugin\SparkCommand\Commands\Session\SessionDatabaseMigrate;
use SparkWeb\XePlugin\SparkCommand\Commands\User\UserAuthSkinMake;
use SparkWeb\XePlugin\SparkCommand\Commands\User\UserProfileSkinMake;
use SparkWeb\XePlugin\SparkCommand\Commands\User\UserSettingsSkinMake;
use SparkWeb\XePlugin\SparkCommand\Commands\Widget\WidgetMake;
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
