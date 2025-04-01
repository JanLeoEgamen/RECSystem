<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventAnnouncementController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\MainCarouselController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupporterController;
use App\Http\Controllers\UserController;
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




});

Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Page not found.');
});



require __DIR__.'/auth.php';
