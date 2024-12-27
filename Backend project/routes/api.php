<?php

use App\Notifications\rappelStarted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmailtemplateController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RappelController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\TechnologyController;
use App\Models\User;
use App\Http\Controllers\FileExigenceController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\StatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login', function(){
    return response()->json("Unauthorized",401);
});

Route::get('/test', function(){
    $user = User::get()->first();
    // $user->notify( new rappelStarted());
    return "test";
});
Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');;
Route::post('/register', [ApiAuthController::class, 'register'])->name('register.api');
Route::post('/refresh', [ApiAuthController::class, 'refresh']);


///////////////////////////////Technology Start //////////////////////////////
Route::get('getAllTechnologies', [TechnologyController::class, 'getAllTechnologies']);

Route::get('getAllEvaluationByParams', [EvaluationController::class, 'getAllEvaluationByParams']);

//////////////////////////////////////Technology End/////////////////////////
Route::get('getAllUsers', [UserController::class, 'getAllUsers']);

Route::post('createUser', action: [UserController::class, 'createUser']);
Route::delete('users/{id}', [UserController::class, 'deleteUser']);
Route::get('getUserById/{id}', [UserController::class, 'getUserById']);
Route::get('getUser', [UserController::class, 'getUser']);
Route::post('updateUserInfo', [UserController::class, 'updateUserInfo']);


 
Route::post('createRappel', [RappelController::class, 'createRappel']);
Route::post('updateRappelInfo', [RappelController::class, 'updateRappelInfo']);
Route::get('getRappelById/{id}', [RappelController::class, 'getRappelById']);
Route::get('getRappel', [RappelController::class, 'getRappel']);
Route::get('getAllRappels', [RappelController::class, 'getAllRappels']);
Route::delete('rappels/{id}', [RappelController::class, 'deleteRappel']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
    ///////////////////////// Users ////////////////////////////////////////////


    // Route::delete('users/{id}', [UserController::class, 'deleteUser']);
    // Route::get('getUserById/{id}', [UserController::class, 'getUserById']);
    // Route::get('getUser', [UserController::class, 'getUser']);
    // Route::post('updateUserInfo', [UserController::class, 'updateUserInfo']);
    // Route::get('getAllUsersByColumn', [UserController::class, 'getAllUsersByColumn']);
    // Route::get('getAllUsersByParams', [UserController::class, 'getAllUsersByParams']);

    ////////////////////////// Rappels ///////////////////////////////////////////////

    // Route::post('createRappel', [RappelController::class, 'createRappel']);
    // Route::post('updateRappelInfo', [RappelController::class, 'updateRappelInfo']);
    // Route::get('getRappelById/{id}', [RappelController::class, 'getRappelById']);
    // Route::get('getRappel', [RappelController::class, 'getRappel']);
    // Route::get('getAllRappels', [RappelController::class, 'getAllRappels']);
    // Route::delete('rappels/{id}', [RappelController::class, 'deleteRappel']);

    /////////////////////////////////Evaluation Begin/////////////////////////////// 
    Route::get('getEvaluationById/{id}', [EvaluationController::class, 'getEvaluationById']);
    Route::get('getAllEvaluations', [EvaluationController::class, 'getAllEvaluations']);
    Route::delete('evaluations/{id}', [EvaluationController::class, 'deleteEvaluation']);
    Route::post('updateEvaluation', [EvaluationController::class, 'updateEvaluation']);
    Route::get('getArchivedEvaluations', [EvaluationController::class, 'getArchivedEvaluations']);
    Route::patch('archiveEvaluation', [EvaluationController::class, 'archiveEvaluation']);
    Route::post('addEvaluation', [EvaluationController::class, 'addEvaluation']);
    Route::post('evaluations/mail/send', [EvaluationController::class, 'sendEmail']);

    ////////////////////////////////Evaluation End ///////////////////////////////

    ///////////////////////////////////////////////////////////emailtemplate/////////////////////////////////////////////////////////////

    Route::post('addEmailtemplate', [EmailtemplateController::class, 'addEmailtemplate'])->name('addEmailtemplate');
    Route::post('updateEmailtemplate', [EmailtemplateController::class, 'updateEmailtemplate'])->name('updateEmailtemplate');
    Route::post('deleteEmailtemplate', [EmailtemplateController::class, 'deleteEmailtemplate'])->name('deleteEmailtemplate');
    Route::get('getAllEmailtemplates', [EmailtemplateController::class, 'getAllEmailtemplates'])->name('getAllEmailtemplates');
    Route::get('getEmailtemplateById/{id}', [EmailtemplateController::class, 'getEmailtemplateById'])->name('getEmailtemplateById');

    ///////////////////////////////////////////////////////////emailtemplate/////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////emails////////////////////////////////////////////////////////////////////
    
    Route::post('sendEmail', [EmailController::class, 'sendEmail'])->name('sendEmail');

    ///////////////////////////////////////////////////////////emails////////////////////////////////////////////////////////////////////
    
    /////////////////////////////////////////////////////// roles //////////////////////////////////////////////////////////////
    
    Route::get('getAllRoles', [RoleController::class, 'getAllRoles'])->name('getAllRoles');

    /////////////////////////////////////////////////////// roles //////////////////////////////////////////////////////////////
});

//----------------------------Files Data ----------------------------//

Route::get('/files', [FileExigenceController::class, 'index']);
Route::post('/files/add', [FileExigenceController::class, 'store']);
Route::get('/files/download/{fileId}', [FileExigenceController::class, 'downloadFile']);
Route::get('/files/users', [FileExigenceController::class, 'getUsersWithDocument']);
Route::get('/files/user/{user_id}', [FileExigenceController::class, 'getFilesByUserId']);
Route::delete('/files/{file_id}', [FileExigenceController::class, 'delete']);




Route::get('/file-types', [FileTypeController::class, 'index']);
Route::post('/file-types', [FileTypeController::class, 'store']);
Route::get('/file-types/{fileType}', [FileTypeController::class, 'show']);
Route::put('/file-types/{fileType}', [FileTypeController::class, 'update']);
Route::delete('/file-types/{fileType}', [FileTypeController::class, 'destroy']);

//----------------------------Files Data ----------------------------//


//----------------------------Mails Endpoints----------------------------//
Route::post('/mail/exigence', [MailController::class, 'sendExigenceFileEmail']);


//----------------------------Mails Endpoints----------------------------//


//------------------------------------------------------------------------Stats
Route::get('stats/getElemntsCounts', [StatController::class, 'getCountsStats']);
Route::get('stats/evaluation/by/tech', [StatController::class, 'getEvaluationByTechnologies']);
Route::get('stats/note-ratio-by-technology', [StatController::class, 'calculateNoteRatioByTechnology']);


//------------------------------------------------------------------------Stats

Route::middleware(['auth:api', 'scope:manage-app'])->group(function () {
});

Route::middleware(['auth:api', 'scope:manage-users'])->group(function () {
    Route::post('giveRole', [RoleController::class, 'giveRoleToUser']);
    
});
