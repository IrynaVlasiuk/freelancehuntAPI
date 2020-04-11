<?php

require_once 'DB.php';

class Project
{
    public function saveInDatabase($project)
    {
        $name = $project->name;
        $link =  $project->self->web;
        $budget = $project->budget->amount ? $project->budget->amount : null;
        $currency = $project->budget->currency ? $project->budget->currency : null;
        $status_id = $project->status->id;

        $ifExist = DB::query("SELECT COUNT(*) FROM projects WHERE name = '$name'");

        if($ifExist == 0) {
            DB::query("INSERT INTO projects(name, link, budget, currency, status_id) 
                        VALUES ('$name', '$link' , '$budget', '$currency' , '$status_id' )");
        }
    }
}

