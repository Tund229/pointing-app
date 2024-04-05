<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/home{id?}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/restore-account', [HomeController::class, 'restore_account_view'])->name('restore_account_view');
Route::post('/restore-account', [HomeController::class, 'restore_account'])->name('restore_account');
Route::post('/profile/{id}', [HomeController::class, 'profile_update'])->name('profile_update');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/restore-account', [HomeController::class, 'restore_account_view'])->name('restore_account_view');
Route::post('/restore-account', [HomeController::class, 'restore_account'])->name('restore_account');

Route::get('/callback', [App\Http\Controllers\HomeController::class, 'handleCallback'])->name('callback');
Route::get('/failure', [App\Http\Controllers\HomeController::class, 'handleFailure'])->name('failure');
Route::get('/success', [App\Http\Controllers\HomeController::class, 'handleSuccess'])->name('success');

//Admin
Route::namespace('App\\Http\\Controllers\\Admin')->prefix('admin')->name('admin.')->middleware("is_admin")->group(function () {
    Route::resources([
        'teacher' => "TeacherController",
        'courses' => "CourseController",
        'course-deposit' => "CourseDepositController",
        'pointing' => "PointingController",
        'promotion' => "PromotionController",
        'admin' => "AdminController",
        'pay-slips' => "PaySlipsController",
        'reclamations' => "ReclamationController",
        'restore-account' => "RestoreAccountController",
        'tuteurs-fixe' => "TuteurFixeController",
        'paiements' => 'PaiementController',
    ]);

    Route::get('paiement-admin/', 'PaiementController@paiement_admin')->name('paiement.admin');
    Route::post('paiement-admin/', 'PaiementController@paiement_admin_store')->name('paiement.admin.store');
    Route::get('paiement-employes/', 'PaiementController@paiement_employes')->name('paiement.employes');
    Route::post('paiement-employes/', 'PaiementController@paiement_employes_store')->name('paiement.employes.store');
    Route::get('paiement/download/{paiement}', 'PaiementController@downloadPaySlips')->name('paiement.downloadPaySlips');
    Route::delete('paiement/{paiement}', 'PaiementController@destroyPaiement')->name('paiement.destroy');



    Route::post('pointing/{id}/valider', 'PointingController@valider')->name('pointing.valider');
    Route::post('pointing/{id}/refuser', 'PointingController@refuser')->name('pointing.refuser');
    Route::get('pointings/delete-all', 'PointingController@deleteAll')->name('pointings.delete-all');
    Route::get('course-deposit/delete-all', 'CourseDepositController@deleteAll')->name('course-deposit.delete-all');
    Route::get('course-deposit/download/{id}', 'CourseDepositController@download')->name('course-deposit.download');
    Route::get('pay-slips/downloadPaySlips/{id}', 'PaySlipsController@downloadPaySlips')->name('pay-slips.downloadPaySlips');

    Route::get('reclamations/{id}/valide', 'ReclamationController@valide')->name('reclamation.valide');
    Route::get('reclamations/{id}/refuse', 'ReclamationController@refuse')->name('reclamation.refuse');

    Route::get('course-deposit/{id}/valide', 'CourseDepositController@valide')->name('course-deposit.valide');
    Route::get('course-deposit/{id}/refuse', 'CourseDepositController@refuse')->name('course-deposit.refuse');
    Route::get('/restore-account-valide/{id}', 'RestoreAccountController@restore_account_valide')->name('restore_account_valide');

    Route::get('/generate-code', 'PaiementController@generate')->name('generate.code');
    Route::get('/verification', 'PaiementController@showVerificationPage')->name('verification.page');
    Route::post('/verification/check', 'PaiementController@check')->name('verification.check');

    Route::get('/pay-slips/{id}', [PaySlipController::class, 'show'])->name('admin.pay-slips.show');


    


});

//Teacher
Route::namespace('App\\Http\\Controllers\\Teacher')->prefix('teacher')->name('teacher.')->middleware("is_teacher")->group(function () {
    Route::resources([
        'course-deposit' => "CourseDepositController",
        'pointing' => "PointingController",
        'pay-slips' => "PaySlipsController",
        'reclamations' => "ReclamationController",
    ]);
    // Ajoutez la route de téléchargement ici
    Route::get('course-deposit/download/{id}', 'CourseDepositController@download')->name('course-deposit.download');
    Route::get('pay-slips/downloadPaySlips/{id}', 'PaySlipsController@downloadPaySlips')->name('pay-slips.downloadPaySlips');

});
