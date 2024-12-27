<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmailRepository;


class EmailController extends Controller
{
    public function __construct(private EmailRepository $emailRepository)
    {

    }

    public function sendEmail(Request $request)
    {
        $email = $this->emailRepository->sendEmail($request);

        $responseBody['email'] = $email;
        $responseBody['message'] = 'Email envoyé avec succès';
        $responseBody['statusCode'] = 200;

        return $responseBody;
    }
}