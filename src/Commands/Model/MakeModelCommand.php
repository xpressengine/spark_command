<?php

namespace XeHub\XePlugin\XeCli\Commands\Model;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeModelCommand
 * 
 * 모델을 생성하는 코멘드
 * 
 * @package XeHub\XePlugin\XeCli\Commands\Handler
 */
class MakeModelCommand extends MakePluginFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:model {plugin} {name} {tableName}';

    /**
     * @var string
     */
    protected $description = 'Make Model Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     *
     * @return void
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Model');
    }

    /**
     * Get Replace Data
     *
     * @param PluginEntity $pluginEntity
     * @return array
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function getReplaceData(PluginEntity $pluginEntity): array
    {
        $replaceData = parent::getReplaceData($pluginEntity);

        return array_merge($replaceData, [
            'DummyTableName' => $this->argument('tableName')
        ]);
    }

    /**
     * Get Plugin Name
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
        return __DIR__ . '/stubs';
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
            $pluginEntity, 'Models'
        );
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
            $pluginEntity, 'Models'
        );
    }

    /**
     * Get Plugin File
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')). 'Model';
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'model.stub';
    }

    /**
     * Get Artisan Command Name
     *
     * @return string
     */
    public function getArtisanCommandName()
    {
        return 'xe_cli:make:model';
    }
}