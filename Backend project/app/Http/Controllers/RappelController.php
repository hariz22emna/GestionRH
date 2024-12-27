<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Rappel;
use App\Repositories\RappelRepository;

class RappelController extends Controller
{
    public function __construct(private RappelRepository $rappelRepository)
    {
    }

    public function createRappel(Request $request)
    {
        $data = $request->all();
        $data['userId'] = $request->user()->id;
        $data['resourceId'] = $request->resourceId;
        $rappel = $this->rappelRepository->createRappel($data);

        $responseBody['rappel'] = $rappel;
        $responseBody['message'] = 'Rappel ajoutée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function updateRappelInfo(Request $request)
    {
        $data = $request->all();
        $data['userId'] = $request->user()->id;
        $data['resourceId'] = $request->resourceId;
        $rappel = $this->rappelRepository->updateRappelInfo($data);

        if (!$rappel) {
            $responseBody['message'] = 'Rappel introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['rappel'] = $rappel;
        $responseBody['message'] = 'Rappel modifiée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function deleteRappel(Request $request)
    {
        $rappel = $this->rappelRepository->deleteRappel($request->id);

        if (!$rappel) {
            $responseBody['message'] = 'Rappel introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['rappel'] = $rappel;
        $responseBody['message'] = 'Rappel supprimée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function getAllRappels()
    {
        $rappels = $this->rappelRepository->getAllRappels();

        $responseBody['rappel'] = $rappels;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function getRappelById($id)
    {
        $rappel = $this->rappelRepository->getRappelById($id);

        if (!$rappel) {
            $responseBody['message'] = 'Rappel introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['rappel'] = $rappel;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody, 200);
    }




}