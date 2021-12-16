<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeMigrationResourceCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class MigrationResourceCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:migrationResource {plugin}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Migration Resource';

    /**
     * Make File
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        parent::make($pluginEntity);

        $this->makeInterfaceFile($pluginEntity);
    }

    /**
     * Mak Migration Interface File
     *
     * @param PluginEntity $pluginEntity
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function makeInterfaceFile(PluginEntity $pluginEntity)
    {
        $stubFileName = 'migrationInterface';
        $stubFilePath = dirname($this->stubFilePath()) . '/' . $stubFileName . '.stub';

        $toFilePath = $this->pluginService->getPluginPath(
            $pluginEntity, 'Migrations/' . Str::studly($stubFileName) . '.php'
        );

        if ($this->filesystem->exists($toFilePath) === false) {
            $replaceData = $this->replaceData($pluginEntity);

            $this->stubFileService->makeFile(
                $stubFilePath,
                $toFilePath,
                $replaceData
            );
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
        return __DIR__ . '/stubs/migrationResource.stub';
    }

    /**
     * Get To File's Path
     *
     * @param PluginEntity $pluginEntity
     * @return mixed|void
     * @throws FileNotFoundException
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $filePath = 'Migrations/MigrationResource.php';

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
        $this->output->success('Generate The Migration Resource');
    }

    /**
     * Output Already Exists
     *
     * @return mixed|void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists Migration Resource');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:migrationResource';
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