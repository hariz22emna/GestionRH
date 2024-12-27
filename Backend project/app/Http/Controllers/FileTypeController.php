<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileType;

class FileTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fileTypes = FileType::all();
        return response()->json($fileTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required'
        ]);

        $existingFileType = FileType::where('type', $request->type)->first();

        if ($existingFileType) {
            return response()->json(['message' => 'L\'élément existe déjà'], 409);
        }

        $fileType = FileType::create($request->all());

        return response()->json($fileType, 201);
    }

    public function show(FileType $fileType)
    {
        return response()->json($fileType);
    }

    public function update(Request $request, FileType $fileType)
    {
        $request->validate([
            'type' => 'required'
        ]);

        $fileType->update($request->all());

        return response()->json($fileType);
    }

    public function destroy(FileType $fileType)
    {
        $fileType->delete();

        return response()->json(null, 204);
    }
}