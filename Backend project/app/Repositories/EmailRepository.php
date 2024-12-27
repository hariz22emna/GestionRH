<?php

namespace App\Repositories;

use App\Models\Email;
use App\Mail\EmailTemplateMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class EmailRepository
{
    public function sendEmail($request)
    {
        if($request->user_id){
            $currentUser = $request->user_id;
        }else{
            $currentUser = auth()->user();
        }
        $userEmail = $currentUser->email;
        $request['senderEmail'] = $userEmail;
        
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $directory = Str::random(10);
            File::makeDirectory(public_path($directory), 0777, true, true);
            
            foreach ($attachments as $attachment) {
                $fileName = time().rand(1, 99).'.'.$attachment->extension();
                $attachment->move(public_path($directory), $fileName);
            } 
        }
        $email = Email::create($request->all());
        
        Mail::to($request->recipientEmail)->send(new EmailTemplateMail($request->object, $request->mailContent, $userEmail, isset($attachments) ? $directory : ""));

        return $email; 
    }
 
}
