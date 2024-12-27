<?php

namespace App\Repositories;

use App\Models\FileExigence;
use Illuminate\Support\Facades\DB;

class ExigenceRepository
{
    public function getAllDocuments()
    {
        return FileExigence::select('id', 'name', 'file_type_id', 'user_id', 'details', 'full_filename', 'created_at', 'updated_at')->get();
    }

    public function saveOrUpdateDocument($request)
    {
        $fileContent = $request->file('file_content');
        $blob = $fileContent->getContent();

        $file = new FileExigence;
        $file->name = $request->name;
        $file->file_type_id = $request->file_type_id;
        $file->user_id = $request->user_id;
        $file->details = $request->details;
        $file->full_filename = $request->full_filename;
        $file->file_content = $blob;
        $file->save();
    }

    public function getAllUserDocuments($search)
    {
        $query = DB::table('file_exigence')
            ->join('users', 'users.id', '=', 'file_exigence.user_id')
            ->select('users.id', 'users.name')
            ->distinct();

        if ($search) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }

        $results = $query->get();

        return $results;
    }

    public function getFilesByUserId($user_id)
    {
        return FileExigence::where('user_id', $user_id)
            ->select('id', 'name', 'file_type_id', 'user_id', 'details', 'full_filename', 'created_at', 'updated_at')
            ->get();
    }

    public function getAllfilesDetailsByID($fileId)
    {
        return FileExigence::find($fileId);
    }

    public function deleteUserFile($file_id)
    {
        $file = FileExigence::find($file_id);

        if ($file) {
            $file->delete();
            return true;
        }
        return false;
    }
}