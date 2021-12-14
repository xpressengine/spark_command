<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class ClientControllerCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ClientControllerCommand extends PluginFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:clientController
                            {plugin}
                            {name}
                            {--resource}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Client Controller Command';

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
            return __DIR__ . '/stubs/controller/resource/clientController.stub';
        }

        return __DIR__ . '/stubs/controller/controller.stub';
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
        $filePath = "Controllers/Client/{$studlyCaseName}Controller.php";

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
        $this->output->success('Generate The Client Controller');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Client Controller');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:clientController';
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