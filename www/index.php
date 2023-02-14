<?php

$startScriptTime=microtime(true);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

require 'functions.php';

require 'routes.php';

require 'dev.php';

$app = new Bootstrap\Kernel();
$app->start();

$endScriptTime = microtime(true);
$totalScriptTime = $endScriptTime-$startScriptTime;
echo 'Load time: '.number_format($totalScriptTime, 4).' seconds';