<?php

namespace XeHub\XePlugin\XeCli\Commands\Migration;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Commands\Model\MakeModelCommandClass;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeMigrationTableCommand
 * @package XeHub\XePlugin\XeCli\Commands\Migration
 */
class MakeMigrationTableCommandClass extends MakePluginClassFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:migrationTable {plugin} {name} {--model}';

    /**
     * @var string
     */
    protected $description = 'Make Migration Table';

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @return mixed|void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function makePluginFile(
        PluginEntity $pluginEntity
    )
    {
        parent::makePluginFile($pluginEntity);
        $this->makeMigrationInterfaceFile($pluginEntity);

        if ($this->option('model') == true) {
            $this->makeModelFile();
        }
    }

    /**
     * Mak Migration Interface File
     *
     * @param PluginEntity $pluginEntity
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function makeMigrationInterfaceFile(
        PluginEntity $pluginEntity
    )
    {
        $stubFileName = 'migrationInterface.stub';
        $controllerDirectoryPath = $this->pluginService->getPluginPath(
            $pluginEntity, 'Migrations'
        );

        // Migration Interface Migration Stub 복사
        $originControllerStubFilePath = $this->getStubPath() . '/' . $stubFileName;
        $stubControllerFilePath = $controllerDirectoryPath . '/' . $stubFileName;

        // Made Interface Migration
        $madeControllerFilePath = $controllerDirectoryPath . '/Migration.php';

        $this->stubFileService->makeFileByStub(
            $originControllerStubFilePath,
            $stubControllerFilePath,
            $madeControllerFilePath,
            $this->getReplaceData($pluginEntity)
        );
    }

    /**
     * Make Model File
     * @return void
     */
    protected function makeModelFile()
    {
        $this->call(app(MakeModelCommandClass::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name')
        ]);
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
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function getPluginDirectoryPath(
        PluginEntity $pluginEntity
    ): string
    {
        return $this->pluginService->getPluginPath(
            $pluginEntity, 'Migrations/Table'
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
    protected function getPluginNamespace(
        PluginEntity $pluginEntity
    ): string
    {
        return $this->pluginService->getPluginNamespace(
            $pluginEntity, 'Migrations/Table'
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
        return 'migrationTable.stub';
    }

    /**
     * Output Success Message
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Migration Table');
    }

    /**
     * Get Plugin File Class
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')) . 'Table';
    }

    /**
     * Get Plugin File Name
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')) . 'Table.php';
    }

    /**
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:migrationTable';
    }
}