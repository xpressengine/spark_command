<?php

namespace XeHub\XePlugin\XeCli\Commands\Handler;

/**
 * Class MakeMessageHandlerCommand
 *
 * Message Handler 생성하는 Command
 *
 * @package XeHub\XePlugin\XeCli\Commands\Handler
 */
class MakeMessageHandlerCommand extends MakeHandlerCommand
{
    /**
     * @var string
     */
    protected $signature = 'xe_cli:make:messageHandler {plugin} {name} {--structure}';

    /**
     * @var string
     */
    protected $description = 'Make Message Handler Command';

    /**
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')). 'MessageHandler';
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        return 'messageHandler.stub';
    }

    /**
     * Output Success Message
     * (상속으로 재정의)
     */
    protected function outputSuccessMessage()
    {
        $this->output->success('Generate The Message Handler');
    }

    /**
     * @return string
     */
    public function getArtisanCommandName(): string
    {
        return 'xe_cli:make:messageHandler';
    }
}