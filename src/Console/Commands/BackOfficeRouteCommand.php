<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeRouteCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class BackOfficeRouteCommand extends PluginFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeRoute 
                            {plugin}
                            {name}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Route Command';

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
     * Make
     *
     * @param PluginEntity $pluginEntity
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        parent::make($pluginEntity);

        $toFilePath = $this->toFilePath($pluginEntity);

        $camelName = Str::camel($this->argument('name'));
        $search = "{$pluginEntity->getId()}::backOffice.{$camelName}.";
        
        $checkExistsContent = $this->stubFileService->checkExistsContent(
            $toFilePath, $search
        );

        if ($checkExistsContent === true) {
            return;
        }

        $replaceData = $this->replaceData($pluginEntity);
        $replaceData['<?php'] = '';

        $this->stubFileService->appendContent(
            $this->stubFilePath(), $toFilePath, $replaceData
        );
    }

    /**
     * Get Stub File Path
     *
     * @return string
     */
    protected function stubFilePath(): string
    {
        return __DIR__ . '/../stubs/route/backOffice/web.stub';
    }

    /**
     * Get To File Path
     *
     * @param PluginEntity $pluginEntity
     * @return mixed|string
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        return $pluginEntity->getPath('routes/web.php');
    }

    /**
     * Output Success Message
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Back Office Route');
    }

    /**
     * Output Already Exists
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Back Office Route');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:backOfficeRoute';
    }

    /**
     * Get Force Option
     *
     * @return bool
     */
    protected function forceOption(): bool
    {
        return false;
    }
}