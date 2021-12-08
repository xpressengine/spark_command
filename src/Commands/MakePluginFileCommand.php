<?php

namespace XeHub\XePlugin\XeCli\Commands;

use App\Console\Commands\MakeCommand;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
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
 * 플러그인 내 파일을 생성하는 코멘드
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
     * @throws Exception|Throwable|ReflectionException
     */
    public function handle()
    {
        $pluginEntity = $this->pluginService->getPluginEntity(
            $this->getPluginName()
        );

        $pluginDirectoryPath = $this->getPluginDirectoryPath(
            $pluginEntity
        );

        try {
            if ($this->files->isDirectory($pluginDirectoryPath) === false) {
                $this->files->makeDirectory($pluginDirectoryPath, 0777, true);
            }

            $this->makePluginFile($pluginEntity);
        } catch (Exception $e) {
            $this->cleanPluginFile($pluginEntity);
            throw $e;
        } catch (Throwable $e) {
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
     * @throws FileNotFoundException|ReflectionException
     */
    public function makePluginFile(
        PluginEntity $pluginEntity
    )
    {
        $stubFileName = $this->getStubFileName();
        $controllerDirectoryPath = $this->getPluginDirectoryPath($pluginEntity);

        // Stub 복사
        $originControllerStubFilePath = $this->getStubPath() . '/' . $stubFileName;
        $stubControllerFilePath = $controllerDirectoryPath . '/' . $stubFileName;

        // Made Controller
        $madeControllerFilePath = $controllerDirectoryPath . '/' . $this->getPluginFileName();

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
    public function cleanPluginFile(
        PluginEntity $pluginEntity
    )
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
        $pluginNamespace = $this->pluginService->getPluginNamespace($pluginEntity, '');;

        $replaceData = [
            '{{pluginId}}' => $pluginEntity->getId(),
            '{{pluginNamespace}}' => $pluginNamespace
        ];

        if ($this->hasArgument('name') === true) {
            $name = $this->argument('name');

            $replaceData = array_merge($replaceData, [
                '{{camelCaseName}}' =>  Str::camel($name),
                '{{studlyCaseName}}' =>  Str::studly($name),
                '{{pluralCaseName}}' =>  Str::plural(Str::camel($name))
            ]);
        }

        return $replaceData;
    }

    /**
     * Plugin's Name
     * (상속으로 재정의)
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
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    abstract protected function getPluginDirectoryPath(PluginEntity $pluginEntity): string;

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getPluginFileName(): string;

    /**
     * Get Controller Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getStubFileName(): string;

    /**
     * Get Artisan Name
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract public function getArtisanCommandName(): string;
}