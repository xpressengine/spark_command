<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeControllerCommand
 *
 * Controller 를 생성하는 커멘드
 *
 * @package XeHub\XePlugin\XeCli\Commands\Controller
 */
class MakeControllerCommandClass extends MakePluginClassFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:controller {plugin} {name} {--resource}';

    /**
     * @var string
     */
    protected $description = 'Make Controller Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Controller');
    }

    /**
     * Get Plugin Directory Path
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string
    {
        return $this->pluginService->getPluginPath(
            $pluginEntity, 'Controllers'
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
            $pluginEntity, 'Controllers'
        );
    }

    /**
     * Plugin's Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginName(): string
    {
        return $this->argument('plugin');
    }

    /**
     * Get Stub Path
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        if ($this->option('resource') == true) {
            return __DIR__ . '/stubs/resource';
        }

        return __DIR__ . '/stubs';
    }

    /**
     * Get Controller Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'controller.stub';
    }

    /**
     * Get Plugin File Class
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')) . 'Controller';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')) . 'Controller.php';
    }

    /**
     * Get Artisan Name
     */
    public function getCommandName(): string
    {
        return 'xe_cli:make:controller';
    }
}