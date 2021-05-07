<?php

namespace SparkWeb\XePlugin\SparkCommand\Commands\User;

use App\Console\Commands\SkinMake;
use SparkWeb\XePlugin\SparkCommand\Traits\RegisterArtisan;

final class UserAuthSkinMakeCommand extends SkinMake
{
    use RegisterArtisan;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user-auth-skin
                        {plugin : The plugin where the skin will be located}
                        {name : The name of skin to create}

                        {--id= : The identifier of skin. default "<plugin>@<name>"}
                        {--path= : The path of skin. Enter the path to under the plugin. ex) SomeDir/SkinDir}
                        {--class= : The class name of skin. default "<name>Skin"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new user settings skin of XpressEngine";

    /**
     * Get target.
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'user/auth/skin';
    }
}
