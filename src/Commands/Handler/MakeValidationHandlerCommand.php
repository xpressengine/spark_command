<?php

namespace XeHub\XePlugin\XeCli\Commands\Handler;

/**
 * Class MakeValidationHandler
 *
 * Validation Handler 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Handler
 */
class MakeValidationHandlerCommand extends MakeHandlerCommand
{
    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:validationHandler {plugin} {name} {--structure}';

    /**
     * @var string
     */
    protected $description = 'Make Validation Handler Command';

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'validationHandler.stub';
    }

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Validation Handler');
    }

    /**
     * Get Plugin File Class
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')). 'ValidationHandler';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')). 'ValidationHandler.php';
    }

    /**
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:validationHandler';
    }
}