<?php

use App\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/register', 'api\Auth\AuthController@register');
// Route::post('/login', 'api\Auth\AuthController@login');

Route::post('/categories/upsert', 'CategoryController@upsert');
Route::delete('/categories/{category}', 'CategoryController@destroy');
Route::get('/categories/{category}/items', 'CategoryController@items');

Route::post('/menu-items/add', 'MenuItemController@store');
Route::get('/menu-items/{menuItem}', function(MenuItem $menuItem){
    return $menuItem;
});
Route::post('/menu-items/{menuItem}', 'MenuItemController@update');

Route::post('/add-image', function(Request $request){
    $file = $request->file('file');
    $dir = 'public/images';
    $path = $file->store($dir);
    return str_replace("$dir/", "", $path);
});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });