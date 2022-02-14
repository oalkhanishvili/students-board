<?php

namespace Oto\SchoolGrade\Service;

use Faker\Factory;
use Oto\SchoolGrade\Database\Connector;

class StudentService
{
    const MINIMAL_SCORE_CSM = 7;
    /**
     * @var Connector
     */
    private $db;
    private string $responseFormat;

    public function __construct()
    {
        $this->db = Connector::getInstance();
    }

    public function getGradeForStudent($id)
    {
        $student = $this->db->query('SELECT * FROM students WHERE id=:id')
            ->bind(':id', $id)
            ->first();


        if (empty($student)) {
            throw new \Exception('Student not found');
        }

        $grades = $this->db->query('SELECT score FROM grades WHERE student_id=:student_id')
            ->bind(':student_id', $student['id'])
            ->all();


        if ($student['board'] == DatabaseService::CSM_BOARD) {
            $this->setResponseFormat('json');

            $scoreList = array_map(function ($grade) {
                return $grade['score'];
            }, $grades);

            $avg = $this->calculateAvg($scoreList);
            $passed = $this->checkTestForCSMS($avg);
        }


        return [
            'id' => $student['id'],
            'name' => $student['name'],
            'grades' => $scoreList,
            'average' => $avg,
            'passed' => $passed
        ];
    }

    private function checkTestForCSMS($avg)
    {
        return $avg >= self::MINIMAL_SCORE_CSM;
    }

    private function calculateAvg(array $values)
    {
        return array_sum($values) / count($values);
    }

    public function setResponseFormat(string $format)
    {
        $this->responseFormat = $format;
    }

    public function getResponseFormat()
    {
        return $this->responseFormat;
    }

}