<?php

namespace Oto\SchoolGrade\Controller;

class StudentsController implements ControllerInterface {
    public function all()
    {
        return 'all';
    }
    public function get($id)
    {
        return $id;
    }
}