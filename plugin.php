<?php

namespace SparkWeb\XePlugin\SparkCommand;

use SparkWeb\XePlugin\SparkCommand\Commands\Widget\WidgetMakeCommand;
use Xpressengine\Plugin\AbstractPlugin;

final class Plugin extends AbstractPlugin
{
    /**
     * @return void
     */
    public function boot()
    {
        WidgetMakeCommand::register();
    }
}
