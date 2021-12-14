<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use Xpressengine\Plugin\PluginEntity;

/**
 * Class PluginClassFileCommand
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
abstract class PluginClassFileCommand extends PluginFileCommand
{
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
        $replaceData = parent::replaceData($pluginEntity);

        $toClassName = $this->toClassName($pluginEntity);
        $toClassNamespace = $this->toClassNameSpace($pluginEntity);

        return array_merge($replaceData, [
            '{{targetClassName}}' => $toClassName,
            '{{targetNamespace}}' => $toClassNamespace,
        ]);
    }

    /**
     * Get To Class Name
     *
     * @param PluginEntity $pluginEntity
     * @return string
     */
    protected function toClassName(PluginEntity $pluginEntity): string
    {
        return basename($this->toFilePath($pluginEntity), '.php');
    }

    /**
     * Get To Class Name Space
     *
     * @param PluginEntity $pluginEntity
     * @return string
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    protected function toClassNameSpace(PluginEntity $pluginEntity): string
    {
        $toFilePath = $this->toFilePath($pluginEntity);
        $pluginBasePath = $this->pluginService->getPluginPath($pluginEntity, '') . '/';

        $toFilePath = str_replace($pluginBasePath, '', $toFilePath);

        return $this->pluginService->getPluginNamespace($pluginEntity,dirname($toFilePath));
    }
}