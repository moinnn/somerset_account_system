<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route for login page
Route::get('/', function () {
    //return view('journal.journal_create');
    return Redirect::to('auth/login');
});
// Authentication routes...
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/login', ['as'=>'login','uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', ['as'=>'logout','uses' => 'Auth\AuthController@getLogout']);
//Verification of newly created user
Route::get('auth/verify/{token}', ['as'=>'verify','uses' => 'registerverifier\RegisterVerifierController@getVerifier']);
Route::post('auth/verify', ['as'=>'verify','uses' => 'registerverifier\RegisterVerifierController@postVerifier']);

// Password reset link request routes...
Route::get('password/email', ['as'=>'resetpassword','uses'=>'Auth\PasswordController@getEmail']);
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}','Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Uses authentication middleware, to avoid uneccessary access if not login
Route::group(['middleware' => 'auth' , 'web'], function () {
    //Users routes
    Route::resource('users','user\UserController');
    Route::resource('usertypes','usertype\UserTypeController');

    //Homeowner routes
    Route::resource('homeowners','homeownerinformation\HomeOwnerInformationController');

    //Homeowner member routes
    Route::resource('homeownermembers','homeownermember\HomeOwnerMemberController');
    Route::get('homeownermembers/create/{id}','homeownermember\HomeOwnerMemberController@create');

    //Invoice routes
    Route::resource('invoice','invoice\InvoiceController');

    //Receipt routes
    Route::resource('receipt','receipt\ReceiptController');
    Route::get('receipt/create/{id}','receipt\ReceiptController@create');

    //Expense routes
    Route::resource('expense','expense\ExpenseController');

    //Journal Entry Routes
    Route::get('journal/create' ,['as'=>'journal','uses'=>'journal\JournalEntryController@getJournalEntry']);
    Route::post('journal/create' ,'journal\JournalEntryController@postJournalEntry');
    
    //Account info routes
    Route::resource('account','accountInformation\AccountInformationController');

    //Account title routes
    Route::resource('accounttitle','accountTitle\AccountTitleController');
    Route::get('accounttitle/create/{id}','accountTitle\AccountTitleController@createWithParent');
    Route::get('accounttitle/create/group/{id}','accountTitle\AccountTitleController@createWithGroupParent');

    //PDF Generation
    Route::post('pdf','pdf\PDFGeneratorController@postGeneratePDF');

    //Report viewing
    Route::get('reports/incomestatement',['as'=>'incomestatement','uses'=>'reports\ReportController@getGenerateIncomeStatement']);
    Route::post('reports/incomestatement','reports\ReportController@postGenerateIncomeStatement');
    

});
