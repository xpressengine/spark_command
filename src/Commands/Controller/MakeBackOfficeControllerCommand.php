<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use Symfony\Component\Console\Input\InputOption;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeMessageHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeValidationHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
use XeHub\XePlugin\XeCli\Commands\Model\MakeModelCommandClass;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeCreateViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeEditViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeIndexViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeShowViewCommand;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeControllerCommand
 *
 * BackOffice Controller 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Controller
 */
class MakeBackOfficeControllerCommand extends MakeControllerCommandClass
{
    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeController {plugin} {name} {--structure}';

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

        if ($this->option('structure') == false) {
            $this->makeHandlerFile();
            $this->makeMessageHandlerFile();
            $this->makeValidationHandlerFile();
            $this->makeMigrationTableFile();
            $this->makeModelFile();

            $this->makeIndexViewFile();
            $this->makeShowViewFile();
            $this->makeEditViewFile();
            $this->makeCreateViewFile();
        }
    }

    /**
     * Make Handler File
     *
     * @return void
     */
    protected function makeHandlerFile()
    {
        $this->call(app(MakeHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Message Handler File
     *
     * @return void
     */
    protected function makeMessageHandlerFile()
    {
        $this->call(app(MakeMessageHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Validation Handler File
     *
     * @return void
     */
    protected function makeValidationHandlerFile()
    {
        $this->call(app(MakeValidationHandlerCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Migration Table File
     *
     * @return void
     */
    protected function makeMigrationTableFile()
    {
        $this->call(app(MakeMigrationTableCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Model File
     *
     * @return void
     */
    protected function makeModelFile()
    {
        $this->call(app(MakeModelCommandClass::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeIndexViewFile()
    {
        $this->call(app(MakeBackOfficeIndexViewCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeShowViewFile()
    {
        $this->call(app(MakeBackOfficeShowViewCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeEditViewFile()
    {
        $this->call(app(MakeBackOfficeEditViewCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ]);
    }

    /**
     * Make Create View File
     *
     * @return void
     */
    protected function makeCreateViewFile()
    {
        $this->call(app(MakeBackOfficeCreateViewCommand::class)->getArtisanCommandName(), [
            'plugin' => $this->getPluginName(),
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
        if ($this->option('structure') == true) {
            return __DIR__ . '/stubs/structure';
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