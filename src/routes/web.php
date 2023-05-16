<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\HomePageController;
use App\Http\Controllers\Main\DarkModePageController;
use App\Http\Controllers\Main\PrivacyPolicyPageController;
use App\Http\Controllers\Main\CaptchaController;
use App\Http\Controllers\Main\AboutPageController;
use App\Http\Controllers\Main\ContactPageController;
use App\Http\Controllers\Main\FAQPageController;
use App\Http\Controllers\Main\Auth\LoginPageController;
use App\Http\Controllers\Main\Auth\LogoutPageController;
use App\Http\Controllers\Main\Auth\RegisterPageController;
use App\Http\Controllers\Main\Auth\ForgotPasswordPageController;
use App\Http\Controllers\Main\Auth\ResetPasswordPageController;
use App\Http\Controllers\Main\Auth\ProfilePageController;
use App\Http\Controllers\Main\Auth\VerifyRegisteredUserPageController;
use App\Http\Controllers\Main\Content\DashboardPageController;
use App\Http\Controllers\Main\Content\ImagePageController;
use App\Http\Controllers\Main\Content\AudioPageController;
use App\Http\Controllers\Main\Content\DocumentPageController;
use App\Http\Controllers\Main\Content\VideoPageController;


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


Route::get('/', [HomePageController::class, 'index', 'as' => 'home.index'])->name('index');
Route::get('/about', [AboutPageController::class, 'index', 'as' => 'about.index'])->name('about');
Route::get('/contact', [ContactPageController::class, 'index', 'as' => 'contact.index'])->name('contact');
Route::post('/contact', [ContactPageController::class, 'contact_ajax', 'as' => 'contact.contact_ajax'])->name('contact_ajax')->middleware('throttle:5,1');
Route::get('/faq', [FAQPageController::class, 'index', 'as' => 'faq.index'])->name('faq');
Route::get('/privacy-policy', [PrivacyPolicyPageController::class, 'index', 'as' => 'privacy_policy.index'])->name('privacy_policy');
Route::get('/captcha-reload', [CaptchaController::class, 'reloadCaptcha', 'as' => 'captcha.reload'])->name('captcha_ajax')->middleware('throttle:5,1');
Route::get('/thumbnail/file/{uuid}', [ImagePageController::class, 'thumbnail', 'as' => 'image.thumbnail'])->name('image_thumbnail');


Route::middleware(['guest'])->group(function () {
    Route::get('/sign-in', [LoginPageController::class, 'index', 'as' => 'login.index'])->name('signin');
    Route::post('/sign-in', [LoginPageController::class, 'authenticate', 'as' => 'login.authenticate'])->name('signin_authenticate')->middleware('throttle:3,1');
    Route::get('/sign-up', [RegisterPageController::class, 'index', 'as' => 'register.index'])->name('signup');
    Route::post('/sign-up', [RegisterPageController::class, 'store', 'as' => 'register.store'])->name('signup_store');
    Route::get('/forgot-password', [ForgotPasswordPageController::class, 'index', 'as' => 'forgot_password.index'])->name('forgot_password');
    Route::post('/forgot-password', [ForgotPasswordPageController::class, 'requestForgotPassword', 'as' => 'forgot_password.requestForgotPassword'])->name('forgot_password_request')->middleware('throttle:3,1');
    Route::get('/reset-password/{id}', [ResetPasswordPageController::class, 'index', 'as' => 'reset_password.index'])->name('resetPassword');
    Route::post('/reset-password/{id}', [ResetPasswordPageController::class, 'requestResetPassword', 'as' => 'reset_password.requestResetPassword'])->name('resetPasswordRequest')->middleware('throttle:3,1');
    Route::get('/verify-user/{id}', [VerifyRegisteredUserPageController::class, 'index', 'as' => 'requestVerifyRegisteredUser.index'])->name('verifyUser');
    Route::post('/verify-user/{id}', [VerifyRegisteredUserPageController::class, 'requestVerifyRegisteredUser', 'as' => 'requestVerifyRegisteredUser.requestVerifyRegisteredUser'])->name('requestVerifyRegisteredUser')->middleware('throttle:3,1');

});


Route::prefix('/')->middleware(['auth'])->group(function () {
    Route::get('/sign-out', [LogoutPageController::class, 'logout', 'as' => 'logout.index'])->name('signout');
    Route::get('/dark-mode', [DarkModePageController::class, 'index', 'as' => 'darkmode.index'])->name('darkmode');
    Route::get('/user-profile', [ProfilePageController::class, 'index', 'as' => 'profile.index'])->name('userprofile');
    Route::post('/update-user-profile', [ProfilePageController::class, 'update', 'as' => 'profile.update'])->name('update_userprofile');
    Route::get('/user-password', [ProfilePageController::class, 'profile_password', 'as' => 'profile.profile_password'])->name('display_profile_password');
    Route::post('/update-user-password', [ProfilePageController::class, 'change_profile_password', 'as' => 'profile.change_profile_password'])->name('change_profile_password');
    Route::get('/search-history', [ProfilePageController::class, 'search_history', 'as' => 'profile.search_history'])->name('search_history');
});
Route::prefix('/content')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardPageController::class, 'index', 'as' => 'content.dashboard'])->name('content_dashboard');
    Route::post('/search-query', [DashboardPageController::class, 'search_query', 'as' => 'content.search_query'])->name('content_search_query');

    Route::prefix('/image')->group(function () {
        Route::get('/', [ImagePageController::class, 'index', 'as' => 'content.image'])->name('content_image');
        Route::get('/{uuid}', [ImagePageController::class, 'view', 'as' => 'content.image_view'])->name('content_image_view');
        Route::get('/{uuid}/make-favourite', [ImagePageController::class, 'makeFavourite', 'as' => 'content.image_makeFavourite'])->name('content_image_makeFavourite');
        Route::post('/{uuid}/request-access', [ImagePageController::class, 'requestAccess', 'as' => 'content.image_requestAccess'])->name('content_image_requestAccess');
        Route::post('/{uuid}/report', [ImagePageController::class, 'report', 'as' => 'content.image_report'])->name('content_image_report');
        Route::post('/search-query', [ImagePageController::class, 'search_query', 'as' => 'content.image_search_query'])->name('content_image_search_query');
        Route::get('/file/{uuid}', [ImagePageController::class, 'imageFile', 'as' => 'image.imageFile'])->name('content_image_file');
        Route::get('/file/{uuid}/thumbnail', [ImagePageController::class, 'thumbnail', 'as' => 'image.thumbnail'])->name('content_image_thumbnail');
    });

    Route::prefix('/document')->group(function () {
        Route::get('/', [DocumentPageController::class, 'index', 'as' => 'content.document'])->name('content_document');
        Route::get('/{uuid}', [DocumentPageController::class, 'view', 'as' => 'content.document_view'])->name('content_document_view');
        Route::get('/{uuid}/make-favourite', [DocumentPageController::class, 'makeFavourite', 'as' => 'content.document_makeFavourite'])->name('content_document_makeFavourite');
        Route::post('/{uuid}/request-access', [DocumentPageController::class, 'requestAccess', 'as' => 'content.document_requestAccess'])->name('content_document_requestAccess');
        Route::post('/{uuid}/report', [DocumentPageController::class, 'report', 'as' => 'content.document_report'])->name('content_document_report');
        Route::post('/search-query', [DocumentPageController::class, 'search_query', 'as' => 'content.document_search_query'])->name('content_document_search_query');
        Route::get('/file/{uuid}', [DocumentPageController::class, 'documentFile', 'as' => 'document.documentFile'])->name('content_document_file');
    });

    Route::prefix('/audio')->group(function () {
        Route::get('/', [AudioPageController::class, 'index', 'as' => 'content.audio'])->name('content_audio');
        Route::get('/{uuid}', [AudioPageController::class, 'view', 'as' => 'content.audio_view'])->name('content_audio_view');
        Route::get('/{uuid}/make-favourite', [AudioPageController::class, 'makeFavourite', 'as' => 'content.audio_makeFavourite'])->name('content_audio_makeFavourite');
        Route::post('/{uuid}/request-access', [AudioPageController::class, 'requestAccess', 'as' => 'content.audio_requestAccess'])->name('content_audio_requestAccess');
        Route::post('/{uuid}/report', [AudioPageController::class, 'report', 'as' => 'content.audio_report'])->name('content_audio_report');
        Route::post('/search-query', [AudioPageController::class, 'search_query', 'as' => 'content.audio_search_query'])->name('content_audio_search_query');
        Route::get('/file/{uuid}', [AudioPageController::class, 'audioFile', 'as' => 'audio.audioFile'])->name('content_audio_file');
    });

    Route::prefix('/video')->group(function () {
        Route::get('/', [VideoPageController::class, 'index', 'as' => 'content.video'])->name('content_video');
        Route::get('/{uuid}', [VideoPageController::class, 'view', 'as' => 'content.video_view'])->name('content_video_view');
        Route::get('/{uuid}/make-favourite', [VideoPageController::class, 'makeFavourite', 'as' => 'content.video_makeFavourite'])->name('content_video_makeFavourite');
        Route::post('/{uuid}/request-access', [VideoPageController::class, 'requestAccess', 'as' => 'content.video_requestAccess'])->name('content_video_requestAccess');
        Route::post('/{uuid}/report', [VideoPageController::class, 'report', 'as' => 'content.video_report'])->name('content_video_report');
        Route::post('/search-query', [VideoPageController::class, 'search_query', 'as' => 'content.video_search_query'])->name('content_video_search_query');
    });

});
