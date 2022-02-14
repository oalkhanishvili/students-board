<?php
require_once __DIR__.'/app/config.php';
require_once __DIR__.'/src/autoload.php';

use Oto\SchoolGrade\Http\Request;


$request = new Request($_SERVER, $_POST, $_GET);

try {
    $controller = $request->getController();

    echo $controller;

} catch (Exception $e) {
    echo sprintf(
        '<h3>%s</h3><h4>%s</h4><h5>%s:%s</h5>',
        $e->getCode(),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}