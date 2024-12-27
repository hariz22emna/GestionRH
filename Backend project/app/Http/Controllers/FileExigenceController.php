<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileExigence;
use App\Repositories\ExigenceRepository;
use Illuminate\Validation\ValidationException;

class FileExigenceController extends Controller
{
    public function __construct(private ExigenceRepository $exigenceRepository)
    {
    }
    public function index()
    {
        return response()->json($this->exigenceRepository->getAllDocuments());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'file_type_id' => 'required|exists:file_types,id',
                'user_id' => 'required|exists:users,id',
                'details' => 'required',
                'full_filename' => 'required',
                'file_content' => 'required|file',
            ]);

            $this->exigenceRepository->saveOrUpdateDocument($request);
            return response()->json(['message' => 'Exigence ajouter avex succées'], 201);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->getMessageBag()->all();
            return response()->json([
                'message' => "Veuillez vérifier les champs des documents d'éxigence.",
                'errors' => $errors,
            ], 422);
        }
    }

    public function show(FileExigence $file)
    {
        return response()->json($file);
    }

    public function update(Request $request, FileExigence $file)
    {
        try {
            $request->validate([
                'name' => 'required',
                'file_type_id' => 'required|exists:file_types,id',
                'user_id' => 'required|exists:users,id',
                'details' => 'required',
                'full_filename' => 'required',
                'file_content' => 'required|file',
            ]);

            $this->exigenceRepository->saveOrUpdateDocument($request);
            return response()->json(['message' => 'Exigence modifier avex succées'], 201);
        } catch (ValidationException $exception) {
            $errors = $exception->validator->getMessageBag()->all();
            return response()->json([
                'message' => "Veuillez vérifier les champs des documents d'éxigence.",
                'errors' => $errors,
            ], 422);
        }
        return response()->json(['message' => 'File updated successfully'], 201);
    }

    public function getUsersWithDocument(Request $request)
    {
        return response()->json($this->exigenceRepository->getAllUserDocuments($request->search));
    }

    public function getFilesByUserId($user_id)
    {
        return response()->json($this->exigenceRepository->getFilesByUserId($user_id));
    }

    public function delete($file_id)
    {
        if ($this->exigenceRepository->deleteUserFile($file_id))
            return response()->json(['message' => 'Exigence supprimé avec succes'], 204);

        return response()->json(['message' => 'Aucune exigence trouvée'], 404);
    }

    public function downloadFile($fileId)
    {
        $file = $this->exigenceRepository->getAllfilesDetailsByID($fileId);

        if (!$file) {
            return response()->json(['message' => 'Acun ficher trouvée'], 204);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $file->full_filename . '"',
        ];

        return response()->streamDownload(function () use ($file) {
            echo $file->file_content;
        }, $file->full_filename, $headers);
    }
}
