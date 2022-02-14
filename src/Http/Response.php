<?php

namespace Oto\SchoolGrade\Http;

class Response
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toJSON(){
        return json_encode(get_object_vars($this));
    }
}