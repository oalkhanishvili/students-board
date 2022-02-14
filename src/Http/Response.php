<?php

namespace Oto\SchoolGrade\Http;

use Spatie\ArrayToXml\ArrayToXml;

class Response
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toJSON()
    {
        return json_encode(get_object_vars($this));
    }

    public function toXml()
    {

        $arr = get_object_vars($this);
        return ArrayToXml::convert($arr);


    }
}