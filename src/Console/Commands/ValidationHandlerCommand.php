<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class ValidationHandlerCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ValidationHandlerCommand extends PluginClassFileCommand implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:validationHandler
                            {plugin}
                            {name}
                            {--complete}
                            {--force}';

    /**
     * @var string
     */
    protected $description = 'Make Validation Handler Command';

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
            return __DIR__ . '/../stubs/handler/complete/validationHandler.stub';
        }

        return __DIR__ . '/../stubs/handler/handler.stub';
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
        $filePath = "Handlers/{$studlyCaseName}/{$studlyCaseName}ValidationHandler.php";

        return $this->pluginService->getPluginPath($pluginEntity, $filePath);
    }

    /**
     * Output Success
     *
     * @return void
     */
    protected function outputSuccess()
    {
        $this->output->success('Generate The Validation Handler');
    }

    /**
     * Output Already Exits
     *
     * @return void
     */
    protected function outputAlreadyExists()
    {
        $this->output->note('Already Exists The Validation Handler');
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:validationHandler';
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
