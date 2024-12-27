<?php

namespace App\Repositories;

use App\Models\Emailtemplate;


class EmailtemplateRepository
{
    public function addEmailtemplate($request)
    {
        $emailTemplate = Emailtemplate::create($request->all());
        
        return $emailTemplate;
    }

    public function updateEmailtemplate($request)
    {
        $emailTemplate  = Emailtemplate::findOrFail($request->id);
        $input = $request->all();
        $emailTemplate->fill($input)->save();

        return $emailTemplate;
    }

    public function deleteEmailtemplate($id)
    {
        $emailTemplate  = Emailtemplate::findOrFail($id);
        $emailTemplate->isDeleted = 1;
        $emailTemplate->save();
    
        return $emailTemplate;
    }

    public function getAllEmailtemplates()
    {
        $templates = Emailtemplate::where("isDeleted", "=", 0)->get();
        
        return $templates;
    }

    public function getEmailtemplateById($id)
    {
        $emailTemplate = Emailtemplate::findOrFail($id);
        
        return $emailTemplate;
    }
 
}
