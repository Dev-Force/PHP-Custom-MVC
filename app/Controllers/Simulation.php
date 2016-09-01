<?php

namespace App\Controllers;

use App\Database\DatabaseHelper as DatabaseHelper;
use App\Core\Controller as Controller;

class Simulation extends Controller{

    public function __construct() {
        $this->dbhelper = new DatabaseHelper([
            'password'  => 'okeanosdb',
            'database'  => 'employees',
        ]);
    }

    private function echoResults($result) {
        $output = [];

        if ($result) {
            $array = $this->dbhelper->getResultsAssoc();

            foreach($array as $value1) {
                foreach($value1 as $key => $value) {
                    $output[] = "$key, $value <br/>";
                }
            }

            $this->view('simulation/index', $output);
        }
    }

    public function showAffectedRows($result, $text) {
        if($result) {
            if($this->dbhelper->getAffectedRows() == -1){
                echo "NO RESULTS";
            }
            $this->view('simulation/index', [$text . $this->dbhelper->getAffectedRows() . "<br/>"]);
        }
    }

    // Get Department from Departments
    public function task1($department) {
        // 1o erwtima
        $result = $this->dbhelper->select([
            'table' => 'departments',
            'params' => [
                'dept_name',
            ],
            'var_types' => [
                's',
            ],
            'where' => [
                ['dept_name', '=', $department],
            ],
        ]);

        $this->echoResults($result);
    }

    // Get all Department Managers from Department ID
    public function task2($dept_no) {
        // 2o erwtima
        $result = $this->dbhelper->select([
            'table' => 'dept_manager',
            'params' => [
                'emp_no'
            ],
            'var_types' => [
                's',
                's',
            ], 
            'where' => [
                ['dept_no', '=', $dept_no],
                ['to_date', '>=', date('Y-m-d')],
            ],
        ]);

        $this->echoResults($result);
    }

    // Count all Employees from a Department ID
    public function task3($dept_no) {
        $result = $this->dbhelper->select([
            'table' => 'dept_emp',
            'params' => [
                'COUNT(*) as empcount'
            ],
            'var_types' => [
                's',
                's',
            ],
            'where' => [
                ['dept_no', '=', $dept_no],
                ['to_date', '>=', date('Y-m-d')], 
            ],
        ]);

        $this->echoResults($result);
    }

    // Delete Department manager
    public function task4($dept_no, $emp_no) {

        // first version
        // $result = $this->dbhelper->delete([
        //     'table' => 'dept_manager',
        //     'var_types' => [
        //         's',
        //         's',
        //     ],
        //     'where' => [
        //         ['dept_no', '=', 'd004'],
        //         ['emp_no', '=', '110303'],
        //     ],
        // ]);
        
        // Second Version
        $result = $this->dbhelper->delete([
            'table' => 'employees',
            'var_types' => [
                's',
                's',
            ],
            'where' => [
                ['emp_no', 'IN', 'subquery' => [
                        'type' => 'select',
                        'table' => 'dept_manager',
                        'params' => [
                            'emp_no',
                        ],
                        'where' => [
                            ['dept_no', '=', $dept_no]
                        ],
                    ],
                ],
                ['emp_no', '=', $emp_no],
            ]
        ]);

        $this->showAffectedRows($result, "Employees Deleted: ");

    }

    // Delete all employees from a department and the department
    public function task5($dept_no) {
        ini_set('max_execution_time', 300);

        $array = [];

        // Delete all Employees
        $result = $this->dbhelper->delete([
            'table' => 'employees',
            'var_types' => [
                's',
            ],
            'where' => [
                [
                    'emp_no',
                    'IN',
                    'subquery' => [
                        'type' => 'select',
                        'table' => 'dept_emp',
                        'params' => [
                            'emp_no',
                        ],
                        'where' => [
                            ['dept_no', '=', $dept_no],
                        ],
                    ],
                ],
            ],
        ]);

        $array[] = "Employees deleted: " . $this->dbhelper->getAffectedRows();

        // Delete Department
        $result = $this->dbhelper->delete([
            'table' => 'departments',
            'var_types' => [
                's',
            ],
            'where' => [
                ['dept_no', '=', $dept_no]
            ],
        ]);

        $array[] = "Departments deleted: " . $this->dbhelper->getAffectedRows();

        $this->view('simulation/index', $array);

    }

    // Move all employees to new table !!! WRONG !!!
    // 
    // Move all employees to new department
    public function task6($dept_no, $dept_name, $dept_no_old) {
        $array =[];

        // Insert new Department
        $result = $this->dbhelper->insert([
            'table' => 'departments',
            'values' => [
                $dept_no,
                $dept_name,
            ],
            'var_types' => [
                's',
                's',
            ],
        ]);

        $array[] = "Departments inserted: " . $this->dbhelper->getAffectedRows();

        // update all dept_employees
        $result = $this->dbhelper->update([
            'table' => 'dept_emp',
            'params' => [
                'dept_no',
            ],
            'values' => [
                $dept_no,
            ],
            'where' => [
                ['dept_no', '=', $dept_no_old],
            ],
            'var_types' => [
                's',
                's',
            ],
        ]);

        $array[] = "Employees updated: " . $this->dbhelper->getAffectedRows();

        // Update all dept_managers
        $result = $this->dbhelper->update([
            'table' => 'dept_manager',
            'params' => [
                'dept_no',
            ],
            'values' => [
                $dept_no,
            ],
            'where' => [
                ['dept_no', '=', $dept_no_old],
            ],
            'var_types' => [
                's',
                's',
            ],
        ]);

        $array[] = "Managers updated: " . $this->dbhelper->getAffectedRows();

        $this->view('simulation/index', $array);
    }

}