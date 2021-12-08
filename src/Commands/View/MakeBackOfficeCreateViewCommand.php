<?php


namespace XeHub\XePlugin\XeCli\Commands\View;

use XeHub\XePlugin\XeCli\Commands\MakePluginFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeCreateViewCommand
 *
 * Back Office Create 뷰 생성 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\View
 */
class MakeBackOfficeCreateViewCommand extends MakePluginFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeCreateView {plugin} {name} {--structure}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Create View Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     *
     * @return void
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Back Office Create View');
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
     * Get Stub Path
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        if ($this->option('structure') == true) {
            return __DIR__ . '/stubs/backOffice/structure';
        }

        return __DIR__ . '/stubs/backOffice';
    }

    /**
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string
    {
        return $pluginEntity->getPath(
            'views/backOffice/' . camel_case($this->argument('name'))
        );
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'create.blade.stub';
    }

    /**
     * Get Artisan Command Name
     *
     * @return string
     */
    public function getCommandName(): string
    {
        return 'xe_cli:make:backOfficeCreateView';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return 'create.blade.php';
    }
}