<?php

namespace App\Controllers;
use App\Models\{Job, Project};

class IndexController extends BaseController{
    public function indexAction() {
        $jobs = Job::all();

        $project1 = new project('Project 1', 'Description 1');
        $projects = [
          $project1
        ];

        //$limitMonths = 15;
        //$filterFunction = function (array $job) use ($limitMonths) {
          //  return $job['months'] >= $limitMonths;
        //};

        //$jobs = array_filter($jobs->toArray(), $filterFunction);

        $lastname = 'Mejia';
        $name = "Daniel $lastname";

        return $this->renderHTML('index.twig', [
          'name' => $name,
          'jobs' => $jobs
        ]);




    }
}
