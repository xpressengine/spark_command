<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use App\Console\Commands\SkinMake;
use Exception;
use Illuminate\Support\Str;
use Throwable;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class ErrorSkinCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class ErrorSkinCommand extends SkinMake implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * Command
     *
     * @var string
     */
    protected $signature = 'xe_cli:make:errorSkin
                            {plugin}
                            {name}
                            {--id=}
                            {--path=}
                            {--class=}
                            {--skip}';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Create A Error Skin Of XpressEngine';

    /**
     *  Stub Directory Paths
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/error';
    }

    /**
     * Get Default Path For Component.
     *
     * @return string
     */
    protected function getDefaultPath()
    {
        return '../views/error/' . Str::camel($this->argument('name'));
    }

    /**
     * Get Skin Target's Id
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return '';
    }

    /**
     * Handle
     *
     * @return bool|null
     * @throws Throwable
     */
    public function handle()
    {
        // get plugin info
        $plugin = $this->getPlugin();
        $skinPath = $this->getPath($this->option('path'));
        $skinId = $this->getSkinId();

        $attr = [
            'plugin' => $plugin,
            'path' => $skinPath,
            'id' => $skinId
        ];

        $this->copyStubDirectory(
            $plugin->getPath($skinPath)
        );

        try {
            $this->makeUsable($attr);
            $this->info('Generate The Error Skin');
        }

        catch (Throwable $e) {
            $this->clean($skinPath);
            throw $e;
        }

        $this->output->success(
            'Error Skin Is Created Successfully.'
        );

        return true;
    }

    /**
     *  Make file for plugin by stub.
     *
     * @param $attr
     * @return void
     * @throws Exception
     */
    protected function makeUsable($attr)
    {
        $plugin = $attr['plugin'];
        $pluginPath = $plugin->getPath($attr['path']);

        $stubs = [
            '401.blade.stub',
            '403.blade.stub',
            '404.blade.stub',
            '500.blade.stub',
            '503.blade.stub',
            '_frame.blade.stub',
            'error.blade.stub'
        ];

        collect($stubs)->each(
            function (string $stub) use ($pluginPath) {
                $stubFilePathName = $pluginPath . '/' . $stub;
                $this->stubToPhp($stubFilePathName);
            }
        );
    }

    /**
     * Stub To Php File
     *
     * @param string $stubFilePathName
     */
    protected function stubToPhp(string $stubFilePathName)
    {
        $phpFilePathName = str_replace(
            'stub', 'php', $stubFilePathName
        );

        rename($stubFilePathName, $phpFilePathName);
    }

    /**
     * Get Command's Name
     *
     * @return string
     */
    public function commandName(): string
    {
        return 'xe_cli:make:errorSkin';
    }
}