<?php

require_once "src/MemcacheClient.php";

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    $message = "Случился нежданчик \"$message\" в файле \"$file\" на строчке \"$line\" ";
    throw new ErrorException($message, 0, $severity, $file, $line);
}//function exception_error_handler($severity, $message, $file, $line)

function exception_handler(\Exception $exception) {
    throw $exception;
}//function exception_handler(\Exception $exception)

set_exception_handler('exception_handler');
set_error_handler("exception_error_handler");




$memClient = new MemcacheClient('127.0.0.1', 11211);
$memClient->set('test', [1]);
$memClient->get('test');
exit;

$memClient->aget('test', function($data){echo $data;});
$memClient->aget('test', function($data){var_dump($data);});
$count = 0;
do {

   $memClient->process();
   sleep(1);
   $count++;
   if($count > 4) throw new MemcacheClientException('Превышенно допусттимое кодичество итераций!!!111');

} while ($memClient->hasTasks());