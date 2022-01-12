<?php

namespace XeHub\XePlugin\XeCli\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class StubFileService
 *
 * @package XeHub\XePlugin\XeCli\Services
 */
class StubFileService
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var PluginService
     */
    protected $pluginService;

    /**
     * 싱글톤 등록
     *
     * @return void
     */
    public static function singleton()
    {
        app()->singleton(__CLASS__, function () {
            $fileSystem = app(Filesystem::class);
            $pluginService = app(PluginService::class);

            return new self($fileSystem, $pluginService);
        });
    }

    /**
     * @return StubFileService
     */
    public static function make(): StubFileService
    {
        return app(__CLASS__);
    }

    /**
     * StubFileService __construct
     *
     * @param Filesystem $filesystem
     * @param PluginService $pluginService
     */
    public function __construct(
        Filesystem    $filesystem,
        PluginService $pluginService
    )
    {
        $this->fileSystem = $filesystem;
        $this->pluginService = $pluginService;
    }

    /**
     * Make File
     *
     * @param string $stubFilePath
     * @param string $toFilePath
     * @param array $replaceData
     * @throws FileNotFoundException
     */
    public function makeFile(
        string $stubFilePath,
        string $toFilePath,
        array  $replaceData
    )
    {
        $search = array_keys($replaceData);

        $toFileDirectoryPath = dirname($toFilePath);
        $toStubFilePath = $toFileDirectoryPath . '/' . basename($stubFilePath);

        if ($this->fileSystem->isDirectory($toFileDirectoryPath) === false) {
            $this->fileSystem->makeDirectory($toFileDirectoryPath, 0777, true);
        }

        $this->fileSystem->copy(
            $stubFilePath, $toStubFilePath
        );

        $this->buildFile(
            $toStubFilePath, $search, $replaceData, $toFilePath
        );
    }

    /**
     * Check Exists Content
     *
     * @param string $toFilePath
     * @param string $keyword
     * @return bool
     * @throws FileNotFoundException
     */
    public function checkExistsContent(
        string $toFilePath,
        string $keyword
    )
    {
        $toFileContent = $this->fileSystem->get($toFilePath);
        return strpos($toFileContent, $keyword) !== false;
    }

    /**
     * Add Content File By Stub
     *
     * @param string $originStubFilePath
     * @param string $toFilePath
     * @param array $replaceData
     * @throws FileNotFoundException
     */
    public function appendContent(
        string $originStubFilePath,
        string $toFilePath,
        array  $replaceData
    )
    {
        $stubFileContent = $this->fileSystem->get($originStubFilePath);

        $replacedContent = str_replace(
            array_keys($replaceData),
            $replaceData,
            $stubFileContent
        );

        $this->fileSystem->append(
            $toFilePath, "\n" . trim($replacedContent)
        );
    }

    /**
     * Build File
     *
     * @param string $filePath
     * @param array $search searches
     * @param array $replace replaces
     * @param string|null $toFilePath
     * @throws FileNotFoundException
     */
    protected function buildFile(
        string $filePath,
        array  $search,
        array  $replace,
        string $toFilePath = null
    )
    {
        $fileContent = $this->fileSystem->get($filePath);

        $code = str_replace(
            $search, $replace, $fileContent
        );

        $this->fileSystem->put($filePath, $code);

        if ($toFilePath !== null) {
            $this->fileSystem->move($filePath, $toFilePath);
        }
    }
}