<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use App\Console\Commands\SkinMake;
use Exception;
use Symfony\Component\Finder\SplFileInfo;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class UserSettingsSkinCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
class UserSettingsSkinCommand extends SkinMake implements CommandNameInterface
{
    use RegisterArtisan;

    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:userSettingsSkin
                            {plugin}
                            {name}
                            {--id=}
                            {--path=}
                            {--class=}
                            {--skip}';

    /**
     * @var string
     */
    protected $description = "Create A New User's Settings Skin Of XpressEngine";

    /**
     * Get Skin Target's Id
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'user/settings';
    }

    /**
     * Get Stub Directory's Path
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/../stubs/skin/user/settings';
    }

    /**
     * Make file for plugin by stub.
     *
     * @param array $attr
     * @return void
     * @throws Exception
     */
    protected function makeUsable($attr)
    {
        $plugin = $attr['plugin'];
        $pluginPath = $plugin->getPath($attr['path']);

        $this->makeSkinClass($attr);
        $this->stubToPhp($pluginPath . '/info.stub');
        $this->makeViewFiles($pluginPath);
    }

    /**
     * Make View Files
     *
     * @param string $pluginPath
     */
    protected function makeViewFiles(string $pluginPath)
    {
        $viewPaths = [$pluginPath . '/views'];

        collect($viewPaths)->each(
            function (string $viewPath) {
                $views = $this->files->files($viewPath, false);

                collect($views)->each(function (SplFileInfo $view) {
                    $this->stubToPhp($view->getPathname());
                });
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
        return 'xe_cli:make:userSettingsSkin';
    }
}
