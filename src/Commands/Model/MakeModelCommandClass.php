<?php

namespace XeHub\XePlugin\XeCli\Commands\Model;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeModelCommand
 *
 * 모델을 생성하는 코멘드
 *
 * @package XeHub\XePlugin\XeCli\Commands\Handler
 */
class MakeModelCommandClass extends MakePluginClassFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:model
                            {plugin}
                            {name}
                            {--tableName=}
                            {--migration} 
                            {--soft-deletes}';

    /**
     * @var string
     */
    protected $description = 'Make Model Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     *
     * @return void
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Model');
    }

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function makePluginFile(
        PluginEntity $pluginEntity
    )
    {
        parent::makePluginFile($pluginEntity);

        if ($this->option('migration') == true) {
           $this->makeMigrationFile();
        }
    }

    /**
     * Make Migration File
     *
     * @return void
     */
    protected function makeMigrationFile()
    {
        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
        ];

        if ($this->option('soft-deletes') == true) {
            $arguments['--soft-deletes'] = true;
        }

        $this->call(
            app(MakeMigrationTableCommand::class)->getArtisanCommandName(),
            $arguments
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
    protected function getReplaceData(
        PluginEntity $pluginEntity
    ): array
    {
        $replaceData = parent::getReplaceData($pluginEntity);

        $modelReplaceData = [
            'tableName' => $this->getTableName(),
        ];

        if ($this->option('soft-deletes') == true) {
            $modelReplaceData['useSoftDeletes'] = "use SoftDeletes;\n";
            $modelReplaceData['useSoftDeletesNamespace'] = "use Illuminate\Database\Eloquent\SoftDeletes;\n";
        }

        return array_merge($replaceData, [
            '{{tableName}}' => Arr::get($modelReplaceData, 'tableName'),
            '{{useSoftDeletes}}' => Arr::get($modelReplaceData, 'useSoftDeletes', ''),
            '{{useSoftDeletesNamespace}}' => Arr::get($modelReplaceData, 'useSoftDeletesNamespace', ''),
        ]);
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
     * Get Table Name
     *
     * @return string
     */
    protected function getTableName(): string
    {
        $tableName = $this->option('tableName');

        if ($tableName !== null) {
            return $tableName;
        }

        return Str::snake($this->argument('name'));
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
    protected function getPluginNamespace(
        PluginEntity $pluginEntity
    ): string
    {
        return $this->pluginService->getPluginNamespace(
            $pluginEntity, 'Models'
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
    protected function getPluginDirectoryPath(
        PluginEntity $pluginEntity
    ): string
    {
        return $this->pluginService->getPluginPath(
            $pluginEntity, 'Models'
        );
    }

    /**
     * Get Plugin File
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')) . 'Model';
    }

    /**
     * Get Plugin File Name
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')) . 'Model.php';
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'model.stub';
    }

    /**
     * Get Artisan Command Name
     *
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:model';
    }
}