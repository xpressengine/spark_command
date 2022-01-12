<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use XeHub\XePlugin\XeCli\Console\Commands\PluginClassFileCommand;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class ControllerCommand
 *
 * Controller 를 생성하는 커멘드
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ControllerCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:controller 
                            {plugin} 
                            {name} 
                            {--resource}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Controller Command';

    /**
     * Get Plugin's Name
     *
     * @return mixed
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
        if ($this->option('resource') == true) {
            return __DIR__ . '/../stubs/controller/resource/controller.stub';
        }

        return __DIR__ . '/../stubs/controller/controller.stub';
    }

    /**
     * Get To File's Path
     *
     * @param PluginEntity $pluginEntity
     * @return mixed
     * @throws FileNotFoundException
     */
    protected function toFilePath(PluginEntity $pluginEntity): string
    {
        $studlyCaseName = studly_case($this->argument('name'));
        $filePath = "Controllers/{$studlyCaseName}Controller.php";

        return $this->pluginService->getPluginPath($pluginEntity, $filePath);
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Controller');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Controller');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:controller';
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