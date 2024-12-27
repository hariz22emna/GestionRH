<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\FileExigence;
use App\Models\Rappel;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{

    public function getCountsStats()
    {
        $statData = [];
        $statData[] = ['label' => 'users', 'count' => User::count(), 'icon' => "fa fa-users"];
        $statData[] = ['label' => 'Technologies', 'count' =>  Technology::count(), 'icon' => "fa fa-code"];
        $statData[] = ['label' => 'Evaluations', 'count' =>  Evaluation::count(), 'icon' => "fa fa-check-square-o"];
        $statData[] = ['label' => 'Documents d\'exigence', 'count' =>  FileExigence::count(), 'icon' => "fa fa-files-o"];
        return response()->json($statData);
    }
    public function getEvaluationByTechnologies()
    {
        $evaluationCounts = DB::table('technologies')
            ->leftJoin('evaluations', 'technologies.id', '=', 'evaluations.technologyId')
            ->select('technologies.id as technology_id', 'technologies.name as technology_name', DB::raw('COUNT(evaluations.id) as evaluation_count'))
            ->groupBy('technologies.id', 'technologies.name')
            ->get();

        return response()->json($evaluationCounts);
    }
    public function calculateNoteRatioByTechnology()
    {
        $technologies = Technology::all();

        $technologyEvaluations = [];
        foreach ($technologies as $technology) {
            $evaluationCount = Evaluation::where('technologyId', $technology->id)->count();
            $technologyEvaluations[$technology->id] = $evaluationCount;
        }

        $totalEvaluations = Evaluation::count();
        $ratios = [];
        foreach ($technologyEvaluations as $technologyId => $evaluationCount) {
            $ratio = ($evaluationCount / $totalEvaluations) * 100;
            $ratios[$technologyId] = $ratio;
        }

        $response = [];
        foreach ($ratios as $technologyId => $ratio) {
            $technology = Technology::find($technologyId);
            $response[] = [
                'technology' => $technology->name,
                'ratio' => number_format($ratio, 2),
            ];
        }

        return response()->json($response);
    }
}
