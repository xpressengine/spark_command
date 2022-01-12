<?php

namespace XeHub\XePlugin\XeCli\Services;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionException;
use Xpressengine\Plugin\PluginEntity;
use Xpressengine\Plugin\PluginHandler;

/**
 * Class PluginService
 * 
 * 플러그인 서비스
 * 
 * @package XeHub\XePlugin\XeCli\Services
 */
class PluginService
{
    /**
     * @var PluginHandler
     */
    protected $pluginHandler;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var array
     */
    protected static $composerData = [];

    /**
     * 싱글톤 등록
     *
     * @return void
     */
    public static function singleton()
    {
        app()->singleton(__CLASS__, function () {
            $pluginHandler = app(PluginHandler::class);
            $fileSystem = app(Filesystem::class);

            return new self(
                $pluginHandler,
                $fileSystem
            );
        });
    }

    /**
     * @return PluginService
     */
    public static function make(): PluginService
    {
        return app(__CLASS__);
    }

    /**
     * PluginService __construct
     *
     * @param PluginHandler $pluginHandler
     * @param Filesystem $filesystem
     */
    public function __construct(
        PluginHandler $pluginHandler,
        Filesystem $filesystem
    )
    {
        $this->pluginHandler = $pluginHandler;
        $this->fileSystem = $filesystem;
    }

    /**
     * 플러그인에 대한 Path 반환
     * 
     * @param PluginEntity $pluginEntity
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     */
    public function getPluginPath(
        PluginEntity $pluginEntity,
        string $path
    )
    {
        $autoload = $this->getPluginComposerData(
            $pluginEntity,'autoload'
        );

        $psr4AutoloadData = data_get($autoload, 'psr-4', []);
        $prefix = array_first($psr4AutoloadData);

        return $pluginEntity->getPath(
            $prefix ? rtrim($prefix,'/') . '/' . $path : $path
        );
    }

    /**
     * 플러그인에 대한 Namespace 반환
     *
     * @param PluginEntity $pluginEntity
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function getPluginNamespace(
        PluginEntity $pluginEntity,
        string $path
    )
    {
        $namespace = null;
        $autoload = $this->getPluginComposerData($pluginEntity, 'autoload');

        $psr4AutoloadData = data_get($autoload, 'psr-4', []);

        foreach ($psr4AutoloadData as $ns => $head) {
            if (starts_with($path, $head) === false) {
                continue;
            }

            $namespace = $ns;
            $path = substr($path, strlen($head));
        }

        if ($namespace === null) {
            $plugin = $pluginEntity;
            $pluginClass = new ReflectionClass($plugin->getClass());
            $namespace = $pluginClass->getNamespaceName();
        }

        if (empty($path) === true) {
            return rtrim($namespace, '\\');
        }

        $segments = array_map(
            function ($segment) { return studly_case($segment); },
            explode('/', $path)
        );

        return rtrim($namespace, '\\') . '\\' . implode('\\', $segments);
    }

    /**
     * 플러그인에 대한 Composer Data 반환
     *
     * @param string|null $key key for data
     * @return mixed|null
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function getPluginComposerData(
        PluginEntity $pluginEntity,
        string $key = null
    )
    {
        $pluginId = $pluginEntity->getId();
        $pluginComposerPath = $pluginEntity->getPath('composer.json');

        $pluginComposerData = Arr::get(static::$composerData, $pluginId);

        if (is_null($pluginComposerData) === true) {
            $pluginComposerContent = $this->fileSystem->get($pluginComposerPath);
            $pluginComposerData = json_decode($pluginComposerContent);

            static::$composerData[$pluginId] = $pluginComposerData;
        }

        return $key ? data_get($pluginComposerData, $key) : $pluginComposerData;
    }

    /**
     * 이름에 해당하는 Plugin Entity 반환
     *
     * @param string $pluginName
     * @return PluginEntity
     * @throws Exception
     */
    public function getEntity(
        string $pluginName
    )
    {
        /** TODO 특정 플러그인 이름을 통해 플러그인 정보를 가져오는 방법에 대한 가이드 문서 작성합니다. */
        $plugin = $this->pluginHandler->getPlugin(
            $pluginName
        );

        if ($plugin === null) {
            throw new Exception(
                "Unable to find a plugin to locate the skin file. plugin[$pluginName] is not found."
            );
        }

        return $plugin;
    }
}