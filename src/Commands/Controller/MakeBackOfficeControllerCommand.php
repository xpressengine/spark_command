<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeMessageHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeValidationHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
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
    protected $signature = 'xe_cli:make:backOfficeController {plugin} {name}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Controller Command';

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @throws FileNotFoundException|ReflectionException
     */
    public function makePluginFile(PluginEntity $pluginEntity)
    {
        parent::makePluginFile($pluginEntity);

        $this->makeMigrations();
        $this->makeHandlers();
    }

    /**
     * Make Handlers
     */
    protected function makeHandlers()
    {
        $this->call(app(MakeHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->argument('plugin'),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeMessageHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->argument('plugin'),
            'name' => $this->argument('name'),
        ]);

        $this->call(app(MakeValidationHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->argument('plugin'),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Migrations
     */
    protected function makeMigrations()
    {
        $this->call(app(MakeMigrationTableCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->argument('plugin'),
            'name' => $this->argument('name'),
        ]);
    }

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
    protected function getPluginNamespace(PluginEntity $pluginEntity): string
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