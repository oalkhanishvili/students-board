<?php

namespace Oto\SchoolGrade\Service;

use Oto\SchoolGrade\Database\Connector;
use Oto\SchoolGrade\Exception\StudentException;

class StudentService
{
    const MINIMAL_SCORE_CSM = 7;
    const MINIMAL_SCORE_CSMB = 8;
    /**
     * @var Connector
     */
    private $db;
    private $responseFormat = null;

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
            throw new StudentException('Student not found', 404);
        }

        $grades = $this->db->query('SELECT score FROM grades WHERE student_id=:student_id ORDER BY score DESC')
            ->bind(':student_id', $student['id'])
            ->all();


        if ($student['board'] == DatabaseService::CSM_BOARD) {
            $this->setResponseFormat('json');

            $scoreList = array_map(function ($grade) {
                return $grade['score'];
            }, $grades);

            $avg = $this->calculateAvg($scoreList);
            $passed = $this->checkTestForCSMS($avg);
        } else if ($student['board'] == DatabaseService::CSMB_BOARD) {
            $this->setResponseFormat('xml');
            $scoreList = array_map(function ($grade) {
                return $grade['score'];
            }, $grades);

            $avg = $this->calculateAvg($scoreList);
            $passed = $this->checkTestForCSMSM($scoreList);
        } else {
            throw new StudentException('Not supported board', 403);
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

    private function checkTestForCSMSM(array $scoreList)
    {
        return count($scoreList) > 2 && $scoreList[0] > self::MINIMAL_SCORE_CSMB;
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