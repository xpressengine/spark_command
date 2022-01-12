<?php


namespace XeHub\XePlugin\XeCli\Console\Commands;

use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeCreateViewCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class BackOfficeCreateViewCommand extends PluginFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeCreateView 
                            {plugin}
                            {name}
                            {--complete}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Create View Command';

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
        if ($this->option('complete') == true) {
            return __DIR__ . '/../stubs/view/backOffice/complete/create.blade.stub';
        }

        return __DIR__ . '/../stubs/view/backOffice/create.blade.stub';
    }

    /**
     * Get To File's Path
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $camelcaseName = camel_case($this->argument('name'));

        return $pluginEntity->getPath(
            "views/backOffice/{$camelcaseName}/create.blade.php"
        );
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Back Office Create View');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists Back Office Create View');
    }

    /**
     * Get Command Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:backOfficeCreateView';
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