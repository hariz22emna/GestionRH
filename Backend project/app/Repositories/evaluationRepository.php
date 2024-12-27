<?php

namespace App\Repositories;

use App\Models\Evaluation;
use App\Models\User;


class evaluationRepository
{
    public function addEvaluation(array $data)
    {
        $evaluation = Evaluation::create($data);

        return $evaluation;
    }
    public function updateEvaluation($request)
    {
        $evaluation = Evaluation::findOrFail($request->id);
        $input = $request->all();
        $evaluation->fill($input)->save();

        return $evaluation;
    }

    public function deleteEvaluation($id)
    {
        $evaluation = Evaluation::find($id);
        if ($evaluation) {
            $evaluation->isDeleted = 1;
            $evaluation->save();
        }
        return $evaluation;
    }


    public function getAllEvaluations()
    {
        $evaluations = User::with('evaluations.technology')
            ->whereHas('evaluations', function ($q) {
                $q->where('isDeleted', '=', 0);
                $q->where('isArchived', '=', 0);
            })
            ->withCount([
                'evaluations as evaluationsNumber' => function ($query) {
                    $query->where('isDeleted', '=', 0);
                }
            ])
            ->get();
    
        // Count the number of evaluations per technology : ala baraaaaa
        $evaluations->each(function ($user) {
            $user->evaluations->groupBy('technologyId')->each(function ($evaluations, $technologyId) use ($user) {
                $evaluationsCount = count($evaluations);
                $user->evaluations->where('technologyId', $technologyId)->first()->technology->evaluationsNumber = $evaluationsCount;
            });
        });
    
        return $evaluations;
    }
    

    public function getArchivedEvaluations($request)
    {
        $search = $request->search;

        $evaluations = User::with('evaluations.technology')
        ->whereHas('evaluations', function ($q) {
            $q->where('isDeleted', '=', 0);
            $q->where('isArchived', '=', 1);
        })
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->where('email', 'like', '%' . $search . '%');
        })
        ->withCount([
            'evaluations as evaluationsNumber' => function ($query) {
                $query->where('isDeleted', '=', 0);
            }
        ])
        ->get();

        return $evaluations;

    }

    public function getEvaluatuionByUser($user_immat)
    {
        $evaluation = User::whereHas('evaluations', function ($q) use ($user_immat) {
            $q->where('isDeleted', 0)
                ->where('isArchived', 0)
                ->where('immat', $user_immat);
        })
            ->with(['evaluations' => function ($q) {
                $q->where('isDeleted', 0)
                    ->where('isArchived', 0);
            }, 'evaluations.technology'])
            ->get();

        return $evaluation;
    }

    public function getEvaluationById($id)
    {
        $evaluation = Evaluation::with('user')->findOrFail($id);

        return $evaluation;
    }
    public function archiveEvaluation($ids)
    {
        $evaluations = Evaluation::whereIn('id', $ids)->get();
        foreach ($evaluations as $evaluation) {
            $evaluation->isArchived = 1;
            $evaluation->save();
        }
        return $evaluations;
    }
    public function getAllEvaluationByParams($request)
    {
        $search = $request->search;
        $sortField = $request->sort ?? 'created_at';
        $sortOrder = $request->ascSort === 'asc' ? 'asc' : 'desc';

        $evaluations = User::whereHas('evaluations', function ($q) {
            $q->where('isDeleted', 0)
                ->where('isArchived', 0);
        })
            ->with(['evaluations' => function ($q) {
                $q->where('isDeleted', 0)
                    ->where('isArchived', 0);
            }, 'evaluations.technology'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%') 
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortField, $sortOrder)
            ->paginate((int) $request->perPage ?? 10);

        return $evaluations;
  
    }
    
}