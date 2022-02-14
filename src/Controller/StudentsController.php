<?php

namespace Oto\SchoolGrade\Controller;

use Oto\SchoolGrade\Database\Connector;
use Oto\SchoolGrade\Http\Response;
use Oto\SchoolGrade\Service\StudentService;

class StudentsController implements ControllerInterface {
    /**
     * @var Connector
     */
    private $db;
    /**
     * @var StudentService
     */
    private $studentService;

    public function __construct()
    {
        $this->db = Connector::getInstance();
        $this->studentService = new StudentService();
    }

    public function all()
    {
        return (new Response([]))->toJSON();
    }
    public function get($id)
    {

        $data = $this->studentService->getGradeForStudent($id);

        $format = $this->studentService->getResponseFormat();
        if ($format === 'json') {
            header('Content-Type: application/json; charset=utf-8');
            return (new Response($data))->toJSON();
        }
        if ($format === 'xml') {
            //need to do
        }
    }
}