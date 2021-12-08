<?php

namespace XeHub\XePlugin\XeCli\Commands\Migration;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeMigrationResourceCommand
 *
 * @package XeHub\XePlugin\XeCli\Commands\Migration
 */
class MakeMigrationResourceCommand extends MakePluginClassFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:migrationResource {plugin}';

    /**
     * @var string
     */
    protected $description = 'Make Migration Resource';

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @return mixed|void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function makePluginFile(PluginEntity $pluginEntity)
    {
        parent::makePluginFile($pluginEntity);

        $stubFileName = 'migrationInterface.stub';
        $controllerDirectoryPath =  $this->pluginService->getPluginPath(
            $pluginEntity, 'Migrations'
        );

        // Stub 복사
        $originControllerStubFilePath = $this->getStubPath() . '/' . $stubFileName;
        $stubControllerFilePath = $controllerDirectoryPath . '/' . $stubFileName;

        // Made Controller
        $madeControllerFilePath = $controllerDirectoryPath . '/Migration.php';

        $this->stubFileService->makeFileByStub(
            $originControllerStubFilePath,
            $stubControllerFilePath,
            $madeControllerFilePath,
            $this->getReplaceData($pluginEntity)
        );
    }

    /**
     * Output Success Message
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Migration Resource');
    }

    /**
     * Get Plugin's Name
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
            $pluginEntity, 'Migrations'
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
            $pluginEntity, 'Migrations'
        );
    }

    /**
     * Get Plugin File Class
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return 'MigrationResource';
    }

    /**
     * Get Plugin File Name
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return 'MigrationResource.php';
    }

    /**
     * Get Controller Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'migrationResource.stub';
    }

    /**
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:migrationResource';
    }
}