<?php
namespace App\Repositories;
use App\Models\Technology;
class TechnologyRepository
{
    public function getAllTechnologies()
    {
        $technologies = Technology::select('id', 'name')->distinct("name")->get();
        return $technologies;
    }
}