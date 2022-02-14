<?php

namespace Oto\SchoolGrade\Http;

use Oto\SchoolGrade\Controller\ControllerInterface;

class Request {
    /**
     * @var array
     */
    private $server;
    /**
     * @var array
     */
    private $post;
    /**
     * @var array
     */
    private $get;


    public function __construct(
        array $server = [],
        array $post = [],
        array $get = []
    ) {
        $this->server = $server;
        $this->post = $post;
        $this->get = $get;
    }

    /**
     * @return mixed
     */
    public function getServer($key = null)
    {
        return !is_null($key) && isset($this->server[$key]) ? $this->server[$key] : $this->server;
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    public function getController()
    {
        $parts = $this->getUrlParts();


        $class = 'Oto\SchoolGrade\Controller\\' . ucfirst(strtolower($parts[0])) . 'Controller';
        if (!class_exists($class) || !isset($parts[0])) {
            throw new \Exception('Controller could not be found', 404);

        }
        /** @var ControllerInterface $controller */
        $controller = new $class();

        if (is_numeric($parts[1])) {
            return $controller->get($parts[1]);
        }

        return $controller->all();
    }


    private function getUrlParts()
    {
        $url = $this->getServer('REQUEST_URI');

        $parts = explode('/', $url);

        return array_values(array_filter($parts));
    }

}