<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class HandlerCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class HandlerCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:handler
                            {plugin}
                            {name}
                            {--complete}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Handler Command';

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
            $this->makeModelFile();
            $this->makeMigrationTableFile();
        }
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
        if ($this->completeOption() == true) {
            return __DIR__ . '/../stubs/handler/complete/handler.stub';
        }

        return __DIR__ . '/../stubs/handler/handler.stub';
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
     * Get To File's Path
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $studlyCaseName = studly_case($this->argument('name'));
        $filePath = "Handlers/{$studlyCaseName}/{$studlyCaseName}Handler.php";

        return $this->pluginService->getPluginPath($pluginEntity, $filePath);
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Handler');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Handler');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:handler';
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

    /**
     * Get Force Option
     *
     * @return bool
     */
    protected function forceOption(): bool
    {
        return $this->option('force');
    }
}