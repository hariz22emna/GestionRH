<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmailtemplateRepository;

class EmailtemplateController extends Controller
{
    public function __construct(private EmailtemplateRepository $emailtemplateRepository)
    {

    }

    public function addEmailtemplate(Request $request)
    {
        $emailTemplate = $this->emailtemplateRepository->addEmailtemplate($request);

        $responseBody['emailtemplate'] = $emailTemplate;
        $responseBody['message'] = 'Template ajoutée avec succès';
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }

    function updateEmailtemplate(Request $request)
    {
        $emailTemplate  = $this->emailtemplateRepository->updateEmailtemplate($request);

        $responseBody['emailtemplate'] = $emailTemplate;
        $responseBody['message'] = 'Template modifiée avec succès';
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }

    function deleteEmailtemplate(Request $request)
    {
        $emailTemplate  = $this->emailtemplateRepository->deleteEmailtemplate($request);

        $responseBody['emailtemplate'] = $emailTemplate;
        $responseBody['message'] = 'Template supprimée avec succès';
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }

    public function getAllEmailtemplates()
    {
        $emailtemplates = $this->emailtemplateRepository->getAllEmailtemplates();

        $responseBody['emailtemplates'] = $emailtemplates;
        $responseBody['statusCode'] = 200;
        return $responseBody;
        
    }

    public function getEmailtemplateById($id)
    {
        $emailTemplate = $this->emailtemplateRepository->getEmailtemplateById($id);

        $responseBody['emailtemplate'] = $emailTemplate;
        $responseBody['statusCode'] = 200;
        return $responseBody;
    }
}