<?php

namespace XeHub\XePlugin\XeCli\Commands;

use ReflectionException;
use Xpressengine\Plugin\PluginEntity;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Class MakePluginClassFileCommand
 * 
 * 플러그인 내 클래스 파일을 생성하는 코멘드
 *
 * @package XeHub\XePlugin\XeCli\Commands
 */
abstract class MakePluginClassFileCommand extends MakePluginFileCommand
{
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
        $replaceData = parent::getReplaceData($pluginEntity);

        $madeControllerClassName = $this->getPluginFileClass();
        $madeControllerNamespace = $this->getPluginNamespace($pluginEntity);

        return array_merge($replaceData, [
            'DummyClass' => $madeControllerClassName,
            'DummyNamespace' => $madeControllerNamespace,
        ]);
    }

    /**
     * Get Plugin Directory Path
     * (상속으로 재정의)
     *
     * @return string
     */
    abstract protected function getPluginFileClass(): string;

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
}