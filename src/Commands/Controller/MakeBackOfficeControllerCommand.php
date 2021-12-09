<?php

namespace XeHub\XePlugin\XeCli\Commands\Controller;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use Symfony\Component\Console\Input\InputOption;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeMessageHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\Handler\MakeValidationHandlerCommand;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
use XeHub\XePlugin\XeCli\Commands\Model\MakeModelCommandClass;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeCreateViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeEditViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeIndexViewCommand;
use XeHub\XePlugin\XeCli\Commands\View\MakeBackOfficeShowViewCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeControllerCommand
 *
 * BackOffice Controller 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Controller
 */
class MakeBackOfficeControllerCommand extends MakePluginClassFileCommand
{
    use RegisterArtisan;

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
        $command = app(MakeHandlerCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Message Handler File
     *
     * @return void
     */
    protected function makeMessageHandlerFile()
    {
        $command = app(MakeMessageHandlerCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Validation Handler File
     *
     * @return void
     */
    protected function makeValidationHandlerFile()
    {
        $command = app(MakeValidationHandlerCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Migration Table File
     *
     * @return void
     */
    protected function makeMigrationTableFile()
    {
        $command =app(MakeMigrationTableCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Model File
     *
     * @return void
     */
    protected function makeModelFile()
    {
        $command = app(MakeModelCommandClass::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeIndexViewFile()
    {
        $command = app(MakeBackOfficeIndexViewCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeShowViewFile()
    {
        $command = app(MakeBackOfficeShowViewCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Index View File
     *
     * @return void
     */
    protected function makeEditViewFile()
    {
        $command = app(MakeBackOfficeEditViewCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Create View File
     *
     * @return void
     */
    protected function makeCreateViewFile()
    {
        $command = app(MakeBackOfficeCreateViewCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
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
    public function getCommandName(): string
    {
        return 'xe_cli:make:backOfficeController';
    }

    /**
     * Get Plugin File
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')) . 'Controller';
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
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')) . 'Controller.php';
    }
}