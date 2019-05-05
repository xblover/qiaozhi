<?php

namespace App\Handlers;

class LoggingHandler
{
    public function logging($message)
    {

        $cFile = fopen ( 'logs/log.txt', 'a' );

        $txt = "$message\n";
        fwrite($cFile, $txt);

        fclose($cFile);
    }
}
