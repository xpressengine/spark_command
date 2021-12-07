<?php


namespace XeHub\XePlugin\XeCli\Commands\View;

use XeHub\XePlugin\XeCli\Commands\MakePluginFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeBackOfficeEditViewCommand
 *
 * Back Office Edit 뷰 생성 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\View
 */
class MakeBackOfficeEditViewCommand extends MakePluginFileCommand
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:backOfficeEditView {plugin} {name} {--structure}';

    /**
     * @var string
     */
    protected $description = 'Make Back Office Edit View Command';

    /**
     * Output Success Message
     * (상속으로 재정의)
     *
     * @return void
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Back Office Edit View');
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
        return 'edit.blade.stub';
    }

    /**
     * Get Artisan Command Name
     *
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:backOfficeEditView';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return 'edit.blade.php';
    }
}