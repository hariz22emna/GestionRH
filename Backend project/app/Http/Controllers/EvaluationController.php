<?php

namespace App\Http\Controllers;

use App\Mail\EvaluationMail;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Repositories\EvaluationRepository;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{
    public function __construct(private EvaluationRepository $evaluationRepository)
    {
    }

    public function addEvaluation(Request $request)
    {
        $data = $request->all();
        $data['userId'] = $request->user()->id; // Ajouter le champ "userId" avec la valeur de l'utilisateur authentifié
        // $data['resourceId'] = $request->user()->id; // Remplacer 1 par la valeur appropriée du champ "resourceId"

        $evaluation = $this->evaluationRepository->addEvaluation($data);

        $responseBody['evaluation'] = $evaluation;
        $responseBody['message'] = 'Evaluation ajoutée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function updateEvaluation(Request $request)
    {
        $evaluation = $this->evaluationRepository->updateEvaluation($request);

        if (!$evaluation) {
            $responseBody['message'] = 'Evaluation introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['evaluation'] = $evaluation;
        $responseBody['message'] = 'Evaluation modifiée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function deleteEvaluation(Request $request)
    {
        $evaluation = $this->evaluationRepository->deleteEvaluation($request->id);

        if (!$evaluation) {
            $responseBody['message'] = 'Evaluation introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['evaluation'] = $evaluation;
        $responseBody['message'] = 'Evaluation supprimée avec succès';
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function getAllEvaluations()
    {
        $evaluations = $this->evaluationRepository->getAllEvaluations();

        $responseBody['evaluation'] = $evaluations;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function getEvaluationById($id)
    {
        $evaluation = Evaluation::with('user')->findOrFail($id);

        if (!$evaluation) {
            $responseBody['message'] = 'Evaluation introuvable';
            $responseBody['statusCode'] = 404;
            return response()->json($responseBody, 404);
        }

        $responseBody['evaluation'] = $evaluation;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody, 200);
    }

    function getArchivedEvaluations(Request $request)
    {
        $evaluations = $this->evaluationRepository->getArchivedEvaluations($request);

        $responseBody['evaluation'] = $evaluations;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }
    function archiveEvaluation(Request $request)
    {
        $evaluations = $this->evaluationRepository->archiveEvaluation($request->ids);
        $responseBody['evaluations'] = $evaluations;
        $responseBody['message'] = 'archivée avec succées';
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }
    public function getAllEvaluationByParams(Request $request)
    {
        $evaluations = $this->evaluationRepository->getAllEvaluationByParams($request);
        
        $responseBody['evaluations'] = $evaluations;
        $responseBody['statusCode'] = 200;
        return response()->json($responseBody);
    }

    public function sendEmail(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'immat' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
            ]);
            $userEvaluations = $this->evaluationRepository->getEvaluatuionByUser($request->immat)[0];
            if (!isset($userEvaluations)) {
                return response()->json([
                    'message' => "Aucune évaluation liée à l'utilisateur introuvable !"
                ], 404);
            }
            $data = [
                'username' => $userEvaluations->name,
                'year' => date('Y'),
                'evaluations' => $userEvaluations->evaluations
            ];

            $to = $userEvaluations->email;

            $email = new EvaluationMail($request->subject, $data);

            Mail::to($to)->send($email);

            return response()->json([
                'message' => 'Email sent successfully!'
            ]);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->getMessageBag()->all();

            return response()->json([
                'message' => "Veuillez vérifier les champs d'entrée de l'évaluation.",
                'errors' => $errors,
            ], 422);
        } catch (Exception $exception) {
            return response()->json([
                'message' => "Aucune évaluation liée à l'utilisateur introuvable !"
            ], 404);
        }
    }


}