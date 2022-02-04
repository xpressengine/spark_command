<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Console\Command;
use Xpressengine\Plugin\PluginEntity;
use Xpressengine\Plugin\PluginHandler;

/**
 * Class PluginStatusCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class PluginStatusCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'plugin:status {plugin}';
    /**
     * @var string
     */
    protected $description = 'Make Controller Command';

    /**
     * @var PluginHandler
     */
    protected $pluginHandler;

    /**
     * PluginStatusCommand constructor.
     */
    public function __construct(PluginHandler $pluginHandler)
    {
        $this->pluginHandler = $pluginHandler;
    }

    /**
     * Handle
     *
     * @return mixed
     */
    public function handle()
    {
        $pluginName = $this->getPluginName();
        $plugin = $this->pluginHandler->getPlugin($pluginName);

        if ($plugin === null) {
            $this->output->not('Plugin is not installed');
            return;
        }

        $this->printPluginStatus($plugin);
    }

    /**
     * Print Plugin's Status
     *
     * @param  PluginEntity  $pluginEntity
     * @return void
     */
    protected function printPluginStatus(PluginEntity $pluginEntity)
    {
        switch ($pluginEntity->getStatus()) {
            case PluginHandler::STATUS_ACTIVATED:
                $this->output->note('Plugin is activated status');
                break;

            case PluginHandler::STATUS_DEACTIVATED:
                $this->output->note('Plugin is deactivated status');
                break;

            default:
                break;
        }
    }

    /**
     * Get Plugin's Name
     *
     * @return mixed
     */
    protected function getPluginName(): string
    {
        return $this->argument('plugin');
    }
}
