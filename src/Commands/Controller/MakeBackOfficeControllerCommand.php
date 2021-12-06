<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeMessageHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeValidationHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
use XeHub\XePlugin\XeCli\Commands\Model\MakeModelCommand;

use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeControllerCommand
 *
 * BackOffice Controller 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Controller
 */
class MakeBackOfficeControllerCommand extends MakeControllerCommand
{
    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeController {plugin} {name} {--empty}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Controller Command';

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws FileNotFoundException|ReflectionException
     */
    public function makePluginFile(
        PluginEntity $pluginEntity
    )
    {
        parent::makePluginFile($pluginEntity);

        if ($this->isActivateEmptyOption() === false) {
            $this->callPluginFileCommands();
        }
    }

    /**
     * Make Class Plugins File
     *
     * @return void
     */
    public function callPluginFileCommands()
    {
        $this->call(app(MakeHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeMessageHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' =>  $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeValidationHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' =>  $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeMigrationTableCommand::class)->getArtisanCommandName(), [
            'plugin' =>  $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeModelCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->argument('plugin'),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Is Activate Empty Option
     * @return bool
     */
    protected function isActivateEmptyOption(): bool
    {
        return $this->option('empty') == true;
    }

    /**
     * Get Controller Directory Path
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
            $pluginEntity, 'Controllers/BackOffice'
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
            $pluginEntity, 'Controllers/BackOffice'
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
        return 'backOfficeController.stub';
    }

    /**
     * Get Stub Path
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        if ($this->isActivateEmptyOption() === true) {
            return __DIR__ . '/stubs/empty';
        }

        return __DIR__ . '/stubs';
    }

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Back Office Controller');
    }

    /**
     * Get Artisan Name
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:backOfficeController';
    }
}