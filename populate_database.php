<?php
require_once __DIR__.'/app/config.php';
require_once __DIR__.'/src/autoload.php';

$service = new \Oto\SchoolGrade\Service\DatabaseService();

$service->populate();