<?php

$app = new \Pheral\Essential\Basement\Application(__DIR__ . '/../');

$app->set('console', \Pheral\Essential\Console\Core::class);

$app->set('network', \Pheral\Essential\Network\Core::class);

return true;