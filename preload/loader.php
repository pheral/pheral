<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Pheral\Essential\Application(__DIR__ . '/..');

$app->force('templates/home/index.php');