<?php

namespace XeHub\XePlugin\XeCli\Commands\Handler;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;;

/**
 * Class MakeHandlerCommand
 *
 * Handler 를 생성하는 커멘드
 *
 * @package XeHub\XePlugin\XeCli\Commands\Handler
 */
class MakeHandlerCommand extends MakePluginClassFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:handler {plugin} {name} {--structure}';

    /**
     * @var string
     */
    protected $description = 'Make Handler Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Handler');
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
            $pluginEntity, 'Handlers/' . studly_case($this->argument('name'))
        );
    }

    /**
     * Plugin's Name
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
        if ($this->option('structure') == true) {
            return __DIR__ . '/stubs/structure';
        }

        return __DIR__ . '/stubs';
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'handler.stub';
    }

    /**
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string
    {
        return $this->pluginService->getPluginPath(
            $pluginEntity, 'Handlers/' . studly_case($this->argument('name'))
        );
    }

    /**
     * Get Plugin File Class
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')). 'Handler';
    }

    /**
     * Get Artisan Name
     * (상속으로 재정의)
     *
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:handler';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')). 'Handler.php';
    }
}