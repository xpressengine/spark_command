<?php

namespace XeHub\XePlugin\XeCli\Commands\Model;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Commands\MakePluginClassFileCommand;
use XeHub\XePlugin\XeCli\Commands\Migration\MakeMigrationTableCommand;
use XeHub\XePlugin\XeCli\Traits\DeclarationTrait;
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
    use DeclarationTrait;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:model
                            {plugin}
                            {name}
                            {--tableName=}
                            {--migration} 
                            {--pk=id}
                            {--soft-deletes}
                            {--incrementing}';

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
        $commentName = app(MakeMigrationTableCommand::class)->getCommandName();

        $arguments = [
            'plugin' => $this->getPluginName(),
            'name' => $this->argument('name'),
            '--pk' => $this->option('pk'),
            '--incrementing' => $this->option('incrementing'),
            '--soft-deletes' => $this->option('soft-deletes'),
        ];

        $this->call(
            $commentName,
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

        $softDeletesOption = $this->option('soft-deletes');
        $primaryKeyOption = $this->option('pk');
        $incrementingOption = $this->option('incrementing');

        $incrementingPropertyDeclaration = $this->getPropertyDeclaration(
            'public', 'incrementing', 'bool', $incrementingOption == true ? 'true' : 'false'
        );

        $primaryKeyPropertyDeclaration = $this->getPropertyDeclaration(
            'protected', 'primaryKey', 'string', $primaryKeyOption
        );

        $modelReplaceData = [
            '{{tableName}}' => $this->getTableName(),
            '{{useSoftDeletes}}' => '',
            '{{useSoftDeletesNamespace}}' => '',
            '{{incrementing}}' => $incrementingPropertyDeclaration,
            '{{primaryKey}}' => $primaryKeyPropertyDeclaration
        ];

        if ($softDeletesOption == true) {
            $modelReplaceData['{{useSoftDeletes}}'] = "use SoftDeletes;\n";
            $modelReplaceData['{{useSoftDeletesNamespace}}'] = "use Illuminate\Database\Eloquent\SoftDeletes;\n";
        }

        return array_merge($replaceData, $modelReplaceData);
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
    public function getCommandName(): string
    {
        return 'xe_cli:make:model';
    }
}