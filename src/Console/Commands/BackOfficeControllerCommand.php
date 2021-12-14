<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeControllerCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class BackOfficeControllerCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeController
                            {plugin}
                            {name} 
                            {--complete}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Controller Command';

    /**
     * Make
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        parent::make($pluginEntity);

        if ($this->completeOption() === true) {
            $this->makeHandlerFile();
            $this->makeMessageHandlerFile();
            $this->makeValidationHandlerFile();

            $this->makeMigrationTableFile();
            $this->makeModelFile();

            $this->makeIndexViewFile();
            $this->makeCreateViewFile();
            $this->makeEditViewFile();
            $this->makeShowViewFile();

            $this->makeRouteFile();
        }
    }

    /**
     * Make Handler File
     *
     * @return void
     */
    protected function makeHandlerFile()
    {
        $command = app(HandlerCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(MessageHandlerCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(ValidationHandlerCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(MigrationTableCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
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
        $command = app(ModelCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
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
        $command = app(BackOfficeIndexViewCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(BackOfficeShowViewCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(BackOfficeEditViewCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
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
        $command = app(BackOfficeCreateViewCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--complete' => true
        ];

        $this->call($command, $arguments);
    }

    /**
     * Make Route File
     *
     * @return void
     */
    protected function makeRouteFile()
    {
        $command = app(BackOfficeRouteCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
        ];

        $this->call($command, $arguments);
    }

    /**
     * Get Plugin's Name
     *
     * @return string
     */
    protected function pluginName(): string
    {
        return $this->argument('plugin');
    }

    /**
     * Get Stub File's Path
     *
     * @return string
     */
    protected function stubFilePath(): string
    {
        if ($this->completeOption() === true) {
            return __DIR__ . '/../stubs/controller/complete/backOfficeController.stub';
        }

        return __DIR__ . '/../stubs/controller/controller.stub';
    }

    /**
     * Get To File's Path
     *
     * @param PluginEntity $pluginEntity
     * @return mixed|string
     * @throws FileNotFoundException
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $studlyCaseName = studly_case($this->argument('name'));
        $filePath = "Controllers/BackOffice/{$studlyCaseName}Controller.php";

        return $this->pluginService->getPluginPath(
            $pluginEntity, $filePath
        );
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Back Office Controller');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Back Office Controller');
    }

    /**
     * Get Command Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:backOfficeController';
    }

    /**
     * Get Force Option
     *
     * @return bool
     */
    protected function forceOption(): bool
    {
        return $this->option('force');
    }

    /**
     * Get Complete Option
     *
     * @return bool
     */
    protected function completeOption(): bool
    {
        return $this->option('complete');
    }
}