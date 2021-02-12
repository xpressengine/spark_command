<?php

namespace SparkWeb\XePlugin\SparkCommand;

use Illuminate\Console\Application as Artisan;
use Route;
use SparkWeb\XePlugin\SparkCommand\Commands\SparkTest;
use SparkWeb\XePlugin\SparkCommand\Commands\SparkWidgetMake;
use Xpressengine\Plugin\AbstractPlugin;

final class Plugin extends AbstractPlugin
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * @param string|null $installedVersion
     * @return void
     */
    public function activate($installedVersion = null)
    {
    }

    /**
     * @return void
     */
    public function install()
    {
    }

    /**
     * @return boolean
     */
    public function checkInstalled()
    {
        return parent::checkInstalled();
    }

    /**
     * @return void
     */
    public function update()
    {
    }

    /**
     * @return boolean
     */
    public function checkUpdated()
    {
        return parent::checkUpdated();
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $commands = [
            SparkWidgetMake::class,
        ];

        Artisan::starting(function ($artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}
