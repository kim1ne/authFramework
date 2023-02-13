<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

require 'functions.php';

require 'routes.php';

require 'dev.php';

$app = new Bootstrap\Kernel();
$app->start();