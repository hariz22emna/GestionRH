<?php

namespace App\Http\Controllers;

use App\Mail\ExigenceMail;
use App\Models\FileExigence;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendExigenceFileEmail(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'file_exigenceId' => 'required',
        ]);

        $subject = $request->input('subject');
        $fileId = $request->input('file_exigenceId');

        $file = FileExigence::find($fileId);

        $filePath = storage_path('app\\temp_mail\\' . $file->full_filename);
        file_put_contents($filePath, $file->file_content);

        if (!$file) {
            return response()->json([
                'message' => 'File not Found!'
            ], 404);
        }
        $data = [
            'username' => $file->user->name,
            'filename' => $file->full_filename
        ];
        $to = $file->user->email;

        $email = new ExigenceMail($subject, $data, $filePath);

        Mail::to($to)->send($email);

        unlink($filePath);
        return response()->json([
            'message' => 'Email sent successfully!'
        ]);
    }
}
