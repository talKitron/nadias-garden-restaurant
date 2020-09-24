<?php

use App\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function App\deleteImageFromDisk;

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
Route::group([
    'middleware' => 'auth:api'
  ], function() {
    Route::post('/categories/upsert', 'CategoryController@upsert');
    Route::delete('/categories/{category}', 'CategoryController@destroy');
    Route::get('/categories/{category}/items', 'CategoryController@items');
    
    // Route::post('/menu-items/add', 'MenuItemController@store');
    // Route::post('/menu-items/{menuItem}', 'MenuItemController@update');
    Route::post('/menu-items/upsert', 'MenuItemController@upsert');
    Route::post('/menu-items/delete-image', 'MenuItemController@deleteImage');
    Route::delete('/menu-items/{menuItem}', 'MenuItemController@destroy');
    Route::get('/menu-items/{menuItem}', function(MenuItem $menuItem){
        return $menuItem;
    });
    Route::post('/menu-items/{menuItem}', 'MenuItemController@upsert');
  });

Route::post('/delete-image', function(Request $request){
  deleteImageFromDisk('public', $request->image);
  dd($request);
  $file = $request->file('file');
  $dir = 'public/images';
  $path = $file->store($dir);
  return str_replace("$dir/", "", $path);
});
Route::post('/add-image', function(Request $request){
  $file = $request->file('file');
  $dir = 'public/images';
  $path = $file->store($dir);
  return str_replace("$dir/", "", $path);
});

Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('/login', 'api\AuthController@login');
    Route::post('/register', 'api\AuthController@register');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('/logout', 'api\AuthController@logout');
        Route::get('/user', 'api\AuthController@user');
    });
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });