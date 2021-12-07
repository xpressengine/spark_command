<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use ReflectionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeClientControllerCommand
 *
 * Client Controller 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Controller
 */
class MakeClientControllerCommand extends MakeControllerCommandClass
{
    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:clientController {plugin} {name}';

    /**
     * @var string
     */
    protected $description = 'Make Client Controller Command';

    /**
     * Get Controller Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string
    {
        return $this->pluginService->getPluginPath(
            $pluginEntity, 'Controllers/Client'
        );
    }

    /**
     * Get Plugin Namespace
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function getPluginNamespace(PluginEntity $pluginEntity): string
    {
        return $this->pluginService->getPluginNamespace(
            $pluginEntity, 'Controllers/Client'
        );
    }

    /**
     * Get Controller Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'clientController.stub';
    }

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Client Controller');
    }

    /**
     * Get Artisan Name
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:clientController';
    }
}