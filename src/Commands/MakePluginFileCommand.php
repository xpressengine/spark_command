<?php

namespace XeHub\XePlugin\XeCli\Commands;

use App\Console\Commands\MakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use ReflectionException;
use Throwable;
use XeHub\XePlugin\XeCli\Services\PluginService;
use XeHub\XePlugin\XeCli\Services\StubFileService;
use Xpressengine\Foundation\Operator;
use Xpressengine\Plugin\PluginEntity;
use Xpressengine\Plugin\PluginHandler;
use Xpressengine\Plugin\PluginProvider;

/**
 * Class MakePluginFileCommand
 *
 * @package XeHub\XePlugin\XeCli\Commands
 */
abstract class MakePluginFileCommand extends MakeCommand
{
    /**
     * @var PluginService
     */
    protected $pluginService;

    /**
     * @var StubFileService
     */
    protected $stubFileService;

    /**
     * Create a new component creator command instance.
     *
     * @param Filesystem $files Filesystem instance
     * @param Operator $operator Operator instance
     * @param PluginHandler $handler PluginHandler
     * @param PluginProvider $provider PluginProvider
     */
    public function __construct(
        Filesystem      $files,
        Operator        $operator,
        PluginHandler   $handler,
        PluginProvider  $provider,
        PluginService   $pluginService,
        StubFileService $stubFileService
    )
    {
        $this->pluginService = $pluginService;
        $this->stubFileService = $stubFileService;

        parent::__construct($files, $operator, $handler, $provider);
    }

    /**
     * Make Controller Command
     *
     * @throws FileNotFoundException
     * @throws Exception|Throwable
     */
    public function handle()
    {
        $pluginEntity = $this->pluginService->getPluginEntity(
            $this->getPluginName()
        );

        $controllerDirectoryPath = $this->getPluginDirectoryPath(
            $pluginEntity
        );

        try {
            if ($this->files->isDirectory($controllerDirectoryPath) === false) {
                $this->files->makeDirectory($controllerDirectoryPath, 0777, true);
            }

            $this->makePluginFile($pluginEntity);
        } catch (Exception | Throwable $e) {
            $this->cleanPluginFile($pluginEntity);
            throw $e;
        }

        $this->outputSuccessMessage();
    }

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    abstract protected function outputSuccessMessage();

    /**
     * Make Plugin File
     *
     * @param PluginEntity $pluginEntity
     * @return mixed
     * @throws FileNotFoundException|ReflectionException
     */
    public function makePluginFile(PluginEntity $pluginEntity)
    {
        $stubFileName = $this->getStubFileName();
        $controllerDirectoryPath = $this->getPluginDirectoryPath($pluginEntity);

        // Stub 복사
        $originControllerStubFilePath = $this->getStubPath() . '/' . $stubFileName;
        $stubControllerFilePath = $controllerDirectoryPath . '/' . $stubFileName;

        // Made Controller
        $madeControllerFilePath = $controllerDirectoryPath . '/' . $this->getPluginFileClass() . '.php';

        $this->stubFileService->makeFileByStub(
            $originControllerStubFilePath,
            $stubControllerFilePath,
            $madeControllerFilePath,
            $this->getReplaceData($pluginEntity)
        );
    }

    /**
     * Clean Controller
     *
     * @param PluginEntity $pluginEntity
     */
    public function cleanPluginFile(PluginEntity $pluginEntity)
    {
        $stubFileName = $this->getStubFileName();
        $controllerDirectoryPath = $this->getPluginDirectoryPath($pluginEntity);

        $stubControllerFilePath = $controllerDirectoryPath . '/' . $stubFileName;

        if ($this->files->exists($stubControllerFilePath) === true) {
            $this->files->delete($stubControllerFilePath);
        }
    }

    /**
     * Get Replace Data
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return array
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function getReplaceData(
        PluginEntity $pluginEntity
    ): array
    {
        $madeControllerClassName = $this->getPluginFileClass();

        $madeControllerNamespace = $this->getPluginNamespace($pluginEntity);
        $baseNamespace = $this->getPluginNamespace($pluginEntity, '');

        return [
            'DummyArgumentCamelCaseName' => camel_case($this->argument('name')),
            'DummyArgumentStudlyCaseName' => studly_case($this->argument('name')),
            'DummyClass' => $madeControllerClassName,
            'DummyNamespace' => $madeControllerNamespace,
            'DummyPluginId' => $pluginEntity->getId(),
            'DummyBaseNamespace' => $baseNamespace
        ];
    }

    /**
     * Plugin's Name
     *
     * @return string
     */
    abstract protected function getPluginName(): string;

    /**
     * Get Stub Path
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getStubPath(): string;

    /**
     * Get Plugin Namespace
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    abstract protected function getPluginNamespace(PluginEntity $pluginEntity): string;

    /**
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    abstract protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string;

    /**
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getPluginFileClass(): string;


    /**
     * Get Controller Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getStubFileName(): string;
}