<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\DeclarationTrait;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class ModelCommand
 *
 * 모델을 생성하는 코멘드
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ModelCommand extends PluginClassFileCommand implements CommandNameInterface
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
                            {--incrementing}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Model Command';

    /**
     * Make
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws ReflectionException
     * @throws FileNotFoundException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        parent::make($pluginEntity);

        if ($this->option('migration') == true) {
            $this->makeMigrationFile();
        }
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
        $replaceData = parent::replaceData($pluginEntity);
        return array_merge($replaceData, $this->modelReplaceData());
    }

    /**
     * Get Model's Replace Data
     *
     * @return array
     */
    protected function modelReplaceData(): array
    {
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
            '{{tableName}}' => $this->tableName(),
            '{{useSoftDeletes}}' => '',
            '{{useSoftDeletesNamespace}}' => '',
            '{{incrementing}}' => $incrementingPropertyDeclaration,
            '{{primaryKey}}' => $primaryKeyPropertyDeclaration
        ];

        if ($softDeletesOption == true) {
            $modelReplaceData['{{useSoftDeletes}}'] = "use SoftDeletes;\n";
            $modelReplaceData['{{useSoftDeletesNamespace}}'] = "use Illuminate\Database\Eloquent\SoftDeletes;\n";
        }

        return $modelReplaceData;
    }

    /**
     * Make Migration File
     *
     * @return void
     */
    protected function makeMigrationFile()
    {
        $commentName = app(MigrationTableCommand::class)->commandName();

        $arguments = [
            'plugin' => $this->pluginName(),
            'name' => $this->argument('name'),
            '--pk' => $this->option('pk'),
            '--incrementing' => $this->option('incrementing'),
            '--soft-deletes' => $this->option('soft-deletes'),
        ];

        $this->call($commentName, $arguments);
    }

    /**
     * Get Table Name
     *
     * @return string
     */
    protected function tableName(): string
    {
        $tableName = $this->option('tableName');

        if ($tableName !== null) {
            return $tableName;
        }

        return Str::snake($this->argument('name'));
    }

    /**
     * Get Plugin Name
     *
     * @return string
     */
    protected function pluginName(): string
    {
        return $this->argument('plugin');
    }

    /**
     * Get Stub File Path
     *
     * @return string
     */
    protected function stubFilePath(): string
    {
        return __DIR__ . '/../stubs/model/model.stub';
    }

    /**
     * Get To File Path
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $studlyCaseName = studly_case($this->argument('name'));
        $filePath = "Models/{$studlyCaseName}Model.php";

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
        $this->output->success('Generate The Model');
    }

    /**
     * Output Already Exists
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Model');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:model';
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