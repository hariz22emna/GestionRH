<?php

namespace App\Http\Controllers;
use App\Repositories\TechnologyRepository;

use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    // 
    public function __construct(private TechnologyRepository $technologyRepository)
    {
        //
    }
    public function getAllTechnologies()
    {
        $technologies = $this->technologyRepository->getAllTechnologies();
        $responseBody['technologies'] = $technologies;
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }
}
