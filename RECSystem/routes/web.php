<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventAnnouncementController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\MainCarouselController;
use App\Http\Controllers\MarkeeController;
use App\Http\Controllers\MembershipTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SupporterController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles', [RoleController::class, 'destroy'])->name('roles.destroy');

    //users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users', [UserController::class, 'destroy'])->name('users.destroy');


        //faqs
    Route::get('/faqs', [FAQController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create', [FAQController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [FAQController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{id}/edit', [FAQController::class, 'edit'])->name('faqs.edit');
    Route::post('/faqs/{id}', [FAQController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs', [FAQController::class, 'destroy'])->name('faqs.destroy');
    
    
    //main carousels
    Route::get('/main-carousels', [MainCarouselController::class, 'index'])->name('main-carousels.index');
    Route::get('/main-carousels/create', [MainCarouselController::class, 'create'])->name('main-carousels.create');
    Route::post('/main-carousels', [MainCarouselController::class, 'store'])->name('main-carousels.store');
    Route::get('/main-carousels/{id}/edit', [MainCarouselController::class, 'edit'])->name('main-carousels.edit');
    Route::post('/main-carousels/{id}', [MainCarouselController::class, 'update'])->name('main-carousels.update');
    Route::delete('/main-carousels', [MainCarouselController::class, 'destroy'])->name('main-carousels.destroy');

    //event announcements
    Route::get('/event-announcements', [EventAnnouncementController::class, 'index'])->name('event-announcements.index');
    Route::get('/event-announcements/create', [EventAnnouncementController::class, 'create'])->name('event-announcements.create');
    Route::post('/event-announcements', [EventAnnouncementController::class, 'store'])->name('event-announcements.store');
    Route::get('/event-announcements/{id}/edit', [EventAnnouncementController::class, 'edit'])->name('event-announcements.edit');
    Route::post('/event-announcements/{id}', [EventAnnouncementController::class, 'update'])->name('event-announcements.update');
    Route::delete('/event-announcements', [EventAnnouncementController::class, 'destroy'])->name('event-announcements.destroy');

    //communities
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/{id}/edit', [CommunityController::class, 'edit'])->name('communities.edit');
    Route::post('/communities/{id}', [CommunityController::class, 'update'])->name('communities.update');
    Route::delete('/communities', [CommunityController::class, 'destroy'])->name('communities.destroy');


    //supporters
    Route::get('/supporters', [SupporterController::class, 'index'])->name('supporters.index');
    Route::get('/supporters/create', [SupporterController::class, 'create'])->name('supporters.create');
    Route::post('/supporters', [SupporterController::class, 'store'])->name('supporters.store');
    Route::get('/supporters/{id}/edit', [SupporterController::class, 'edit'])->name('supporters.edit');
    Route::post('/supporters/{id}', [SupporterController::class, 'update'])->name('supporters.update');
    Route::delete('/supporters', [SupporterController::class, 'destroy'])->name('supporters.destroy');

    //articles
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // Markees
    Route::get('/markees', [MarkeeController::class, 'index'])->name('markees.index');
    Route::get('/markees/create', [MarkeeController::class, 'create'])->name('markees.create');
    Route::post('/markees', [MarkeeController::class, 'store'])->name('markees.store');
    Route::get('/markees/{id}/edit', [MarkeeController::class, 'edit'])->name('markees.edit');
    Route::post('/markees/{id}', [MarkeeController::class, 'update'])->name('markees.update');
    Route::delete('/markees', [MarkeeController::class, 'destroy'])->name('markees.destroy');


    // Membership Types
    Route::get('/membership-types', [MembershipTypeController::class, 'index'])->name('membership-types.index');
    Route::get('/membership-types/create', [MembershipTypeController::class, 'create'])->name('membership-types.create');
    Route::post('/membership-types', [MembershipTypeController::class, 'store'])->name('membership-types.store');
    Route::get('/membership-types/{id}/edit', [MembershipTypeController::class, 'edit'])->name('membership-types.edit');
    Route::post('/membership-types/{id}', [MembershipTypeController::class, 'update'])->name('membership-types.update');
    Route::delete('/membership-types', [MembershipTypeController::class, 'destroy'])->name('membership-types.destroy');

    // Bureaus
    Route::get('/bureaus', [BureauController::class, 'index'])->name('bureaus.index');
    Route::get('/bureaus/create', [BureauController::class, 'create'])->name('bureaus.create');
    Route::post('/bureaus', [BureauController::class, 'store'])->name('bureaus.store');
    Route::get('/bureaus/{id}/edit', [BureauController::class, 'edit'])->name('bureaus.edit');
    Route::post('/bureaus/{id}', [BureauController::class, 'update'])->name('bureaus.update');
    Route::delete('/bureaus', [BureauController::class, 'destroy'])->name('bureaus.destroy');

    // Sections
    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/create', [SectionController::class, 'create'])->name('sections.create');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::get('/sections/{id}/edit', [SectionController::class, 'edit'])->name('sections.edit');
    Route::post('/sections/{id}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections', [SectionController::class, 'destroy'])->name('sections.destroy');

});

Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Page not found.');
});



require __DIR__.'/auth.php';


//For email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
