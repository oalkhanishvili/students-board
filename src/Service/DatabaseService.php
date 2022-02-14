<?php

namespace Oto\SchoolGrade\Service;

use Faker\Factory;
use Oto\SchoolGrade\Database\Connector;

class DatabaseService
{
    const CSM_BOARD = 'CSM';
    const CSMB_BOARD = 'CSMB';
    /**
     * @var Connector
     */
    private $db;

    public function __construct()
    {
        $this->db = Connector::getInstance();
    }

    public function populate()
    {
        $faker = Factory::create();
        $boards = [self::CSM_BOARD, self::CSMB_BOARD];

        $total = 100;
        while ($total > 0) {
            $a = $this->db->query("INSERT INTO students (name, board) VALUES (:name, :board)")
                ->bind(':name', $faker->name)
                ->bind(':board', $boards[rand(0, 1)])->execute();

            $studentId = $this->db->lastId();

            $gradeCount = rand(1, 4);

            while ($gradeCount > 0) {
                $grade = $this->db->query("INSERT INTO grades (student_id, score) VALUES (:student_id, :score)")
                    ->bind(':student_id', $studentId)
                    ->bind(':score', rand(1, 10))->execute();

                $gradeCount--;
            }

            $total--;
        }


    }
}