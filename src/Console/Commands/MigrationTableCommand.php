<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeMigrationTableCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class MigrationTableCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:migrationTable 
                                {plugin}
                                {name} 
                                {--pk=id}
                                {--incrementing}
                                {--model}
                                {--soft-deletes}
                                {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Migration Table';

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        parent::make($pluginEntity);

        if ($this->modelOption() === true) {
            $this->makeModelFile();
        }

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

        $this->stubFileService->makeFile(
            $stubFilePath,
            $toFilePath,
            $this->replaceData($pluginEntity)
        );
    }

    /**
     * Get Replace Data
     *
     * @param PluginEntity $pluginEntity
     * @return array
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function replaceData(PluginEntity $pluginEntity): array
    {
        $replaceData = array_merge(parent::replaceData($pluginEntity), [
            '{{primaryColumn}}' => "\$table->string('{$this->pkOption()}', 36);",
            '{{primaryIndex}}' => "\$table->primary(['{$this->pkOption()}']);",
            '{{softDeletes}}' => ''
        ]);

        if ($this->softDeletesOption() === true) {
            $replaceData['{{softDeletes}}'] = '$table->softDeletes();';
        }

        if ($this->incrementingOption() === true) {
            $replaceData['{{primaryColumn}}'] = "\$table->unsignedInteger('{$this->pkOption()}');";
        }

        return $replaceData;
    }

    /**
     * Call Model File
     *
     * @return void
     */
    protected function makeModelFile()
    {
        $command = app(ModelCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--pk' => $this->pkOption(),
            '--soft-deletes' => $this->softDeletesOption(),
            '--incrementing' => $this->incrementingOption(),
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
        return __DIR__ . '/../stubs/migration/migrationTable.stub';
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
        $filePath = "Migrations/Table/{$studlyCaseName}Table.php";

        return $this->pluginService->getPluginPath($pluginEntity, $filePath);
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Migration Table');
    }

    /**
     * Output Already Exists
     *
     * @return mixed|void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Migration Table');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:migrationTable';
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
     * Get Pk Option
     *
     * @return string
     */
    protected function pkOption(): string
    {
        return $this->option('pk');
    }

    /**
     * Get Incrementing Option
     *
     * @return bool
     */
    protected function incrementingOption(): bool
    {
        return $this->option('incrementing');
    }

    /**
     * Get Model Option
     *
     * @return bool
     */
    protected function modelOption(): bool
    {
        return $this->option('model');
    }

    /**
     * Get `SoftDelete` Option
     *
     * @return bool
     */
    protected function softDeletesOption(): bool
    {
        return $this->option('soft-deletes');
    }
}