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
    protected $signature = '
        xe_cli:make:messageHandler {plugin} {name} 
            {--structure}
    ';

    /**
     * @var string
     */
    protected $description = 'Make Message Handler Command';

    /**
     * Get Plugin File Class
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileClass(): string
    {
        return studly_case($this->argument('name')). 'MessageHandler';
    }

    /**
     * Get Plugin File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getPluginFileName(): string
    {
        return studly_case($this->argument('name')). 'MessageHandler.php';
    }

    /**
     * Get Handler Stub File Name
     * (상속으로 재정의)
     *
     * @return string
     */
    protected function getStubFileName(): string
    {
        if ($this->option('structure') == true) {
            return 'handler.stub';
        }

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
    public function getCommandName(): string
    {
        return 'xe_cli:make:messageHandler';
    }
}