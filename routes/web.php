<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    $users = DB::table('users')->get(); //Select all Users

    // Insert new users
    // $user = DB::table('users')->insert([ 'name'=> 'Kalume', 'email'=> 'Kalume05@gmail.com', 'password'=> '12345678' ]);

    //Update users
    $user = DB::table('users')->where('id', 2)->update([ 'email' => 'abc@update.com']);
    // $users = DB::update("update users set email = ? where id = ?", ['abc@updated.com', 2]);

    //Delet user
    // $users = DB::delete('delete from users where id = 2');
    dd($users);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
