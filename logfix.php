<?php
# Hotfix for log file running in docker
# should run as root
chdir(__DIR__);
$root = __DIR__;
$date = date('Y-m-d');

$logDir = $root . '/storage/logs';
$publicDir = $root . '/public';
exec("chmod -R 0777 $logDir");
exec("chmod -R 0777 $publicDir");
$names = ["laravel-$date.log", "request-$date.log"];
foreach ($names as $name) {
    $filepath = $logDir . '/' . $name;
    if (!is_file($filepath)) {
        $fp = fopen($filepath, 'w');
        fwrite($fp, '#File created at: ' . date('Y-m-d H:i:s'));
        fclose($fp);
        chmod($filepath, 0777);
        echo "Created and chmod 777 for: $filepath\n";
    }
}
