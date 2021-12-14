<?php

namespace XeHub\XePlugin\XeCli\Console\Commands;

/**
 * Class CommandNameInterface
 *
 * @package XeHub\XePlugin\XeCli\Console\Commands
 */
interface CommandNameInterface
{
    /**
     * @return string
     */
    public function commandName(): string;
}