<?php

namespace XeHub\XePlugin\XeCli\Services;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class StubFileService
 * @package XeHub\XePlugin\XeCli\Services
 */
class StubFileService
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * 싱글톤 등록
     *
     * @return void
     */
    public static function singleton()
    {
        app()->singleton(__CLASS__, function () {
            $fileSystem = app(Filesystem::class);

            return new self($fileSystem);
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
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->fileSystem = $filesystem;
    }

    /**
     * Make File By Stub
     *
     * @param string $originStubFilePath
     * @param string $copiedStubHandlerFilePath
     * @param string $madeFilePath
     * @param array $replaceData
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function makeFileByStub(
        string $originStubFilePath,
        string $copiedStubHandlerFilePath,
        string $madeFilePath,
        array $replaceData
    )
    {
        $this->fileSystem->copy(
            $originStubFilePath,
            $copiedStubHandlerFilePath
        );

        /**
         * @TODO File System 사용하는 방법에 대한 가이드 문서 추가.
         */
        if ($this->fileSystem->isFile($madeFilePath) === true) {
            throw new Exception(
                "file [$madeFilePath] already exists."
            );
        }

        $this->buildFile(
            $copiedStubHandlerFilePath,
            array_keys($replaceData),
            $replaceData,
            $madeFilePath
        );
    }

    /**
     * Build the file with given parameters.
     *
     * @param string $file file for build
     * @param array $search searches
     * @param array $replace replaces
     * @param string|null $to location to move
     * @throws FileNotFoundException
     */
    protected function buildFile(
        string $file,
        array $search,
        array $replace,
        string $to = null
    )
    {
        $code = str_replace($search, $replace, $this->fileSystem->get($file));
        $this->fileSystem->put($file, $code);

        if ($to) {
            $this->fileSystem->move($file, $to);
        }
    }
}