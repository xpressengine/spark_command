<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class MakeApiControllerCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ApiControllerCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:apiController
                            {plugin}
                            {name}
                            {--complete}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make API Controller Command';

    /**
     * Get Plugin Name
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
        if ($this->option('complete') == true) {
            return __DIR__ . '/../stubs/controller/complete/apiController.stub';
        }

        return __DIR__ . '/../stubs/controller/controller.stub';
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
        $filePath = "Controllers/Api/{$studlyCaseName}Controller.php";

        return $this->pluginService->getPluginPath($pluginEntity, $filePath);
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The API Controller');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The API Controller');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:apiController';
    }

    /**
     * GEt Force Option
     *
     * @return bool
     */
    protected function forceOption(): bool
    {
        return $this->option('force');
    }
}