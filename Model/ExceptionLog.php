<?php

namespace Creatuity\Monitor\Model;

class ExceptionLog
{
    public static function saveException(\Exception $exception) {

        Collector::incCounter('exceptions');
        $fileName = $exception->getFile();
        $lineNumber = $exception->getLine();
        $message = $exception->getMessage();

        $magentoErrorHandler = '/vendor/magento/framework/App/ErrorHandler.php';
        if ((substr($fileName, -strlen($magentoErrorHandler))===$magentoErrorHandler) && ($lineNumber == 61)) {
            preg_match('/([a-zA-Z0-9\(\)\ ]*):\ (.*)\ in\ (.*)\ on\ line\ ([0-9]*)/', $message, $matches);
            if (count($matches) == 5) {
                $message = $matches[1] . ': ' .  $matches[2];
                $fileName = $matches[3];
                $lineNumber = $matches[4];
            }
        }

        $env = require(BP . '/app/etc/env.php');
        $defaultDb = $env['db']['connection']['default'];
        $connection = new \PDO("mysql:dbname=${defaultDb['dbname']};host=${defaultDb['host']}", $defaultDb['username'], $defaultDb['password']);
        $connection->prepare('INSERT INTO `creatuity_exceptions` (`sapi`, `filename`, `line`, `state`, `current_count`, `total_count`, `message`, `stack`, `request`, `first_time`, `last_time`) ' .
            ' VALUES (?, ?, ?, 1, 1, 1, ?, ?, ?, NOW(), NOW())' .
            ' ON DUPLICATE KEY UPDATE `state` = IF(`state`=3, 1, `state`), `current_count` = `current_count` + 1, `total_count` = `total_count` + 1, `last_time` = NOW(), ' .
            ' `sapi` = VALUES(`sapi`), `message` = VALUES(`message`), `stack` = VALUES(`stack`), `request` = VALUES(`request`)')->execute([
            php_sapi_name(),
            $fileName,
            $lineNumber,
            $message,
            $exception->getTraceAsString(),
            json_encode($_REQUEST, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)
        ]);
    }
}
