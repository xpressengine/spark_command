<?php

namespace XeHub\XePlugin\XeCli;

use Illuminate\Filesystem\Filesystem;
use Xpressengine\Plugin\AbstractPlugin;

/**
 * Class Plugin
 *
 * Xe Cli Plugin
 *
 * @package XeHub\XePlugin\XeCli
 */
final class Plugin extends AbstractPlugin
{
    /** @var Filesystem */
    protected $fileSystem;

    /**
     * Plugin __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileSystem = app(Filesystem::class);
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerConsoleCommands(__DIR__ . '/src/Console/Commands');
    }

    /**
     * Register Console Commands
     *
     * @param string $commandsDirPath
     */
    public function registerConsoleCommands(string $commandsDirPath)
    {
        $commandFiles = $this->fileSystem->files($commandsDirPath);

        foreach ($commandFiles as $commandFile) {
            $className = str_replace('.php', '', $commandFile->getFilename());
            $commandClass = sprintf('%s\\Console\\Commands\\%s', __NAMESPACE__, $className);

            if (method_exists($commandClass, 'register') === false) {
                continue;
            }

            $commandClass::register();
        }
    }
}
