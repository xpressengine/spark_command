<?php

namespace XeHub\XePlugin\XeCli\Commands\Skin;

use App\Console\Commands\SkinMake;
use Symfony\Component\Finder\SplFileInfo;
use XeHub\XePlugin\XeCli\Traits\RegisterArtisan;

/**
 * Class MakeUserProfileSkin
 *
 * Make User Profile Skin Command
 * 시용자에 대한 프로필페이지 스킨을 생성하는 커멘드
 * Command => xe_cli:make:userProfileSkin
 *
 * @package XeHub\XePlugin\XeCli\Commands\Skin
 */
class MakeUserProfileSkin extends SkinMake
{
    use RegisterArtisan;

    /**
     * Command
     *
     * @var string
     */
    protected $signature = 'xe_cli:make:userProfileSkin
        {plugin : The plugin where the skin will be located}
        {name : The name of skin to create}

        {--id= : The identifier of skin. default "<plugin>@<name>"}
        {--path= : The path of skin. Enter the path to under the plugin. ex) SomeDir/SkinDir}
        {--class= : The class name of skin. default "<name>Skin"}';

    /**
     * Description
     *
     * @var string
     */
    protected $description = "Create A New User's Profile Skin Of XpressEngine";

    /**
     * Skin Target's Id
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'user/profile';
    }

    /**
     * Stub Directory Paths
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/user/profile';
    }

    /**
     * Make file for plugin by stub.
     *
     * @param $attr
     * @return void
     * @throws \Exception
     */
    protected function makeUsable(
        $attr
    )
    {
        $plugin = $attr['plugin'];
        $pluginPath = $plugin->getPath($attr['path']);

        $this->makeSkinClass($attr);

        /** Info */
        $this->stubToPhp($pluginPath . '/info.stub');

        /** View Paths */
        $viewPaths = [$pluginPath . '/views'];

        collect($viewPaths)->each(function(string $viewPath) {
            $views = $this->files->files($viewPath, false);

            collect($views)->each(function(SplFileInfo $view) {
                $this->stubToPhp($view->getPathname());
            });
        });
    }

    /**
     * Stub To Php File
     *
     * @param string $stubFilePathName
     */
    protected function stubToPhp(
        string $stubFilePathName
    )
    {
        $phpFilePathName = str_replace(
            'stub', 'php', $stubFilePathName
        );

        rename(
            $stubFilePathName,
            $phpFilePathName
        );
    }
}