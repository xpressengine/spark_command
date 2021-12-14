<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use ReflectionException;
use Throwable;
use XeHub\XePlugin\XeCli\Services\PluginService;
use XeHub\XePlugin\XeCli\Services\StubFileService;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class PluginFileCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
abstract class PluginFileCommand extends Command
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

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
     * @param Filesystem $filesystem Filesystem instance
     * @param PluginService $pluginService
     * @param StubFileService $stubFileService
     */
    public function __construct(
        Filesystem      $filesystem,
        PluginService   $pluginService,
        StubFileService $stubFileService
    )
    {
        $this->filesystem = $filesystem;
        $this->pluginService = $pluginService;
        $this->stubFileService = $stubFileService;

        parent::__construct();
    }

    /**
     * Make Plugin's File Command
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        \Log::info(static::class);

        $pluginName = $this->pluginName();
        $pluginEntity = $this->pluginService->getEntity($pluginName);

        try {
            $this->make($pluginEntity);
        }

        catch (Throwable $e) {
            $this->clean($pluginEntity);
            throw $e;
        }
    }

    /**
     * Make
     *
     * @param PluginEntity $pluginEntity
     * @return void
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function make(PluginEntity $pluginEntity)
    {
        $toFilePath = $this->toFilePath($pluginEntity);

        if ($this->filesystem->exists($toFilePath) === true && $this->forceOption() === false) {
            $this->outputAlreadyExists();
            return;
        }

        $this->stubFileService->makeFile(
            $this->stubFilePath(),
            $toFilePath,
            $this->replaceData($pluginEntity)
        );

        $this->outputSuccess();
    }

    /**
     * Clean
     *
     * @param PluginEntity $pluginEntity
     */
    protected function clean(PluginEntity $pluginEntity)
    {
        $stubFileName = basename($this->stubFilePath());
        $toDirectoryPath = dirname($this->toFilePath($pluginEntity));

        $toStubFilePath = $toDirectoryPath . '/' . $stubFileName;

        if ($this->filesystem->exists($toStubFilePath) === true) {
            $this->filesystem->delete($toStubFilePath);
        }
    }

    /**
     * Get Replace Data
     *
     * @param PluginEntity $pluginEntity
     * @return array
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function replaceData(PluginEntity $pluginEntity): array
    {
        $name = $this->argument('name' ?? '');

        $pluginNamespace = $this->pluginService->getPluginNamespace(
            $pluginEntity, ''
        );

        return [
            '{{pluginId}}' => $pluginEntity->getId(),
            '{{pluginNamespace}}' => $pluginNamespace,
            '{{camelCaseName}}' => Str::camel($name),
            '{{studlyCaseName}}' => Str::studly($name),
            '{{pluralCaseName}}' => Str::plural(Str::camel($name))
        ];
    }

    /**
     * Get Plugin's Name
     *
     * @return string
     */
    abstract protected function pluginName(): string;

    /**
     * Get Force
     *
     * @return bool
     */
    abstract protected function forceOption(): bool;

    /**
     * Get Stub File Path
     *
     * @return string
     */
    abstract protected function stubFilePath(): string;

    /**
     * Get To File Path
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    abstract protected function toFilePath(PluginEntity $pluginEntity): string;

    /**
     * Output Success
     *
     * @return void
     */
    abstract protected function outputSuccess();

    /**
     * Output Already Exits
     *
     * @return mixed
     */
    abstract protected function outputAlreadyExists();
}