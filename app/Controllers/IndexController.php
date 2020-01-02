<?php

namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController{
    public function indexAction() {
        $jobs = Job::all();

        $project1 = new project('Project 1', 'Description 1');
        $projects = [
          $project1
        ];

        $lastname = 'Mejia';
        $name = "Daniel $lastname";
        $limitMonths = 2000;

        include '../views/index.php';
  

 

    }
}