<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
    // $users = User::get(); //Get all Users

    // Insert new users
    // $user = User::create([ 'name'=> 'Kalume', 'email'=> 'Kalume06@gmail.com', 'password'=> '12345678' ]);

    //Update users
    // $user = User::find(2); //First find the user u want to update, then update it,
    // $user->update([ 'email' => 'abcd@update.com', 'password' => bcrypt('password')]); //Then update that value in user,

    //Delet user
    // $user = User::find(1); //First find the user to delete,
    // $user->delete(); //Then delete it,
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Social Auth
Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user = User::firstOrCreate(['email' => $user->email], [
        'name' => $user->name,
        'password' => 'Kalume@21'
    ]);
    Auth::login($user);
    return redirect('/dashboard');
});

//Make the router only accessblee if the user is logged in
Route::middleware('auth')->group(function () {
    // Rooute::resource will autocreate: class for create, index, update, delete, id/item
    Route::resource('/ticket', TicketController::class);
});
