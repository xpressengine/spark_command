<?php

namespace XeHub\XePlugin\XeCli\Traits;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

trait RunChmodAws
{
    /**
     * @return void
     */
    protected function chmodAws()
    {
        $commend = 'sudo chmod -R 0707 ./vendor ./plugins ./bootstrap ./storage | echo "permission override!" | sudo chown -R ubuntu:ubuntu ./bootstrap ./privates | echo "ubuntu user group change!"';

        $process = new Process($commend);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('ubuntu user group change!');
    }
}
