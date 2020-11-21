<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalBlogController;
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

Route::get('/', function () {
    return view('ContentView.HomePage');
});

Route::get('/Home', function () {
    return view('ContentView.HomePage');
});

Route::get('/About', function () {
    return view('ContentView.AboutPage');
});

Route::get('/Contact', function () {
    return view('ContentView.Contact');
});

Route::get('/CategoryList_Public', [PersonalBlogController::class, 'showCategoryListPublic']);

Route::get('/Login', [PersonalBlogController::class, 'showLoginScreen']);

// Route::get('/AllBlog', function () {
//     return view('ContentView.AllBlog');
// });
Route::get('/AllBlog', [PersonalBlogController::class, 'showAllBlog']);

Route::get('/BlogByCategory', [PersonalBlogController::class, 'showBlogByCategory']);

/*
Route::get('/BlogList', function () {
    return view('ContentView.BlogList');
});

Route::get('/CategoryList', function () {
    return view('ContentView.CategoryList');
});


Route::get('/UserList', function () {
    return view('ContentView.UserList');
});
*/

/* Controller */
/* Screen show list data */
Route::get('/BlogList', [PersonalBlogController::class, 'showBlogList']);
Route::get('/CategoryList', [PersonalBlogController::class, 'showCategoryList']);
Route::get('/UserList', [PersonalBlogController::class, 'showUserList']);

/* Screen insert blog */
Route::get('/AddBlog', [PersonalBlogController::class, 'addNewBlog']);
Route::post('/AddBlog', [PersonalBlogController::class, 'addNewBlogPost']);

/* Screen edit blog */
Route::get('/EditBlog/{id?}', [PersonalBlogController::class, 'editBlogByID']);
Route::post('/EditBlog/{id?}', [PersonalBlogController::class, 'editBlogByIDPost']);

/* Screen delete user */
Route::post('/DeleteBlog', [PersonalBlogController::class, 'deleteBlogByIDPost']);

/* Screen insert user */
Route::get('/AddNewUser', [PersonalBlogController::class, 'addNewUser']);
Route::post('/AddNewUser', [PersonalBlogController::class, 'addNewUserPost']);

/* Screen update user info */
Route::get('/EditUser/{id?}', [PersonalBlogController::class, 'editUserByID']);
Route::post('/EditUser/{id?}', [PersonalBlogController::class, 'editUserByIDPost']);

/* Screen delete user */
Route::post('/DeleteUser', [PersonalBlogController::class, 'deleteUserByIDPost']);

/* Screen insert categories */
Route::get('/AddCategory', [PersonalBlogController::class, 'addNewCategory']);
Route::post('/AddCategory', [PersonalBlogController::class, 'addNewCategoryPost']);

/* Screen update categories */
Route::get('/EditCategory/{id?}', [PersonalBlogController::class, 'editCategoryByID']);
Route::post('/EditCategory/{id?}', [PersonalBlogController::class, 'editCategoryByIDPost']);

/* Screen delete categories */
Route::post('/DeleteCategory', [PersonalBlogController::class, 'deleteCategoryByIDPost']);

/* Blog delete */
Route::get('/BlogDetails/{screen_name?}', [PersonalBlogController::class, 'showBlogDetails']);

/* Login */
Route::post('/Login', [PersonalBlogController::class, 'login']);
Route::get('/Logout', [PersonalBlogController::class, 'logout']);

/* Blog search by content */
Route::get('/SearchBlog', [PersonalBlogController::class, 'showAllBlog']);
Route::post('/SearchBlog', [PersonalBlogController::class, 'searchBlogByContent']);

/* Blog search by category and content */
Route::get('/SearchBlogByCategoryAndContent', [PersonalBlogController::class, 'showBlogByCategory']);
Route::post('/SearchBlogByCategoryAndContent', [PersonalBlogController::class, 'searchBlogByCategoryAndContent']);