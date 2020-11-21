<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB, User, Validator, Storage, File;

class PersonalBlogController extends Controller
{
    /* Show data screen: Blogs, Categories, Users */
    public function showLoginScreen(){
        return view('ContentView.Login', [ 'error' => "" ]);
    }

    public function showBlogList(){
        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name')
                            ->get();
        
        $role = DB::table('role_user')
                            ->join('roles', 'role_user.role_id', 'roles.id')
                            ->where('role_user.user_id', Auth::user()->id)
                            ->get();
        return view('ContentView.BlogList', [ 'blog_list' => $blog_list, 'role' => $role ]);
    }

    public function showCategoryList(){
        $category_list = DB::table('categories')
                        ->select('id', 'title', 'description', 'updated_at as last_update_date')
                        ->get();

        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();

        return view('ContentView.CategoryList', [ 'category_list' => $category_list, 'role' => $role ]);
    }

    public function showCategoryListPublic(){
        $category_list = DB::table('categories')->get();
        return view('ContentView.CategoryList_Public', [ 'category_list' => $category_list ]);
    }
    
    public function showAllBlog(){
        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->leftJoin('users', 'articles.user_id', 'users.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                            ->where('status', 1)
                            ->get();
        return view('ContentView.AllBlog', [ 'blog_list' => $blog_list ]);
    }

    public function showUserList(){
        $user_list = DB::table('users')
                        ->join('role_user', 'users.id', 'role_user.user_id')
                        ->join('roles', 'roles.id', 'role_user.role_id')
                        ->select('users.id as id', 'users.name as name', 'users.email', 'roles.name as role', 'users.updated_at as last_update_date')
                        ->get();
        
        $category_list = DB::table('categories')
                        ->select('id', 'title', 'description', 'updated_at as last_update_date')
                        ->get();

        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();
        return view('ContentView.UserList', [ 'user_list' => $user_list, 'role' => $role ]);
    }

    /* Add user */
    public function addNewUser(){
        $role_list = DB::table('roles')->get();
        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();
						
        return view('ContentView.CreateUser', ['role_list' => $role_list, 'role' => $role ]);
    }

    public function addNewUserPost(Request $request){
        $validator = Validator::make($request->all(), 
            [
                'role_id' => 'required',
                'user_name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6'
            ],
            [
                'role_id.required'      => 'Please choose role of user!',
                'user_name.required'    => 'Please input user name!',  
                'email.required'        => 'Please input email!',  
                'email.email'           => 'Please input correct email address format!',  
                'password.required'     => 'Please input password!',
                'password.min:6'        => 'Please input password with at least 6 characters!',
            ]
        );

        if ($validator->passes()) {
            /* Insert data to Users */
            DB::table('users')->insert( 
            [
                'name'          => strval($request->input('user_name')),
                'email'         => strval($request->input('email')),
                'password'      => bcrypt(strval($request->input('password')))
            ]);
            
            /* Insert data to Roll_User */
            DB::table('role_user')->insert( 
            [
                'user_id'       => DB::getPdo()->lastInsertId(),
                'role_id'       => strval($request->input('role_id'))
            ]);

			return response()->json(['success'=>'Save successfully!!!']);
        }

    	return response()->json(['error'=>$validator->errors()]);
    }

    /* Add category */
    public function addNewCategory(){
        $category_list = DB::table('categories')->get();
        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();

        return view('ContentView.CreateCategory', ['category_list' => $category_list , 'role' => $role ]);
    }

    public function addNewCategoryPost(Request $request){
        $validator = Validator::make($request->all(), 
            [
                'title' => 'required',
            ],
            [
                'title.required'      => 'Please input title!',
            ]
        );

        if ($validator->passes()) {
            /* Insert data to Category */
            DB::table('categories')->insert( 
            [
                'title'          => strval($request->input('title')),
                'description'    => strval($request->input('description')),
            ]);
            
			return response()->json(['success'=>'Save successfully!!!']);
        }


    	return response()->json(['error'=>$validator->errors()]);
    }
    
    /* Edit user */
    public function editUserByID(){
        $id = $_GET['id'];
        
        if (!$id) return;

        $user_info = DB::table('users')
            ->select('role_user.role_id as role_id', 'users.id as id', 'users.name as user_name', 'email')
            ->join('role_user', 'users.id', 'role_user.user_id')
            ->where('id', $id)
            ->get();

        $role_list = DB::table('roles')->get();

        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();
                        
        return view('ContentView.EditUser', ['user_info' => $user_info, 'role_list' => $role_list, 'role' => $role ]);
    }

    public function editUserByIDPost(Request $request){
        if ($request->input('password') || $request->input('password_confirmation')){
            $validator = Validator::make($request->all(), 
            [
                'role_id'                => 'required',
                'user_name'             => 'required',
                'email'                 => 'required|email',
                'password'              => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ],
            [
                'role_id.required'                  => 'Please choose role of user!',
                'user_name.required'                => 'Please input user name!',  
                'email.required'                    => 'Please input email!',  
                'email.email'                       => 'Please input correct email address format!',  
                'password.required'                 => 'Please input new password!',
                'password.min:6'                    => 'Please input new password with as least 6 characters!',
                'password.confirmed'                => "Please input Password confirmation match the password",
                'password_confirmation.required'    => 'Please input new password confirmation!',
                'password_confirmation.min:6'       => 'Please input new password confirmation swith as least 6 characters!',
            ]);
            
            if ($validator->passes()) {
                /* Update the database */
                /* Table: Users */
                DB::table('users')->where('id', strval($request->input('id'))) 
                    ->update([
                        'name'    => strval($request->input('user_name')),
                        'email'   => strval($request->input('email')),
                        'password'=> bcrypt($request->input('password')),
                    ]);
                
                /* Table: Role_User */
                DB::table('role_user')->where('user_id', strval($request->input('id')))->update(['role_id' => strval($request->input('role_id'))]);

                /* Return the message Success! */
                return response()->json(['success'=>'Save successfully!!!']);
            }
            return response()->json(['error'=>$validator->errors()]);
        }
        else{
            $validator = Validator::make($request->all(), 
            [
                'role_id'                => 'required',
                'user_name'             => 'required',
                'email'                 => 'required|email',
            ],
            [
                'role_id.required'                  => 'Please choose role of user!',
                'user_name.required'                => 'Please input user name!',  
                'email.required'                    => 'Please input email!',  
                'email.email'                       => 'Please input correct email address format!',  
            ]);
            
            if ($validator->passes()) {
                /* Update the database */
                /* Table: Users */
                DB::table('users')->where('id', strval($request->input('id'))) 
                    ->update([
                        'name'    => strval($request->input('user_name')),
                        'email'   => strval($request->input('email'))
                    ]);

                /* Table: Role_User */
                DB::table('role_user')->where('user_id', strval($request->input('id')))->update(['role_id' => strval($request->input('role_id'))]);

                /* Return the message Success! */
                return response()->json(['success'=>'Save successfully!!!']);
            }
            return response()->json(['error'=>$validator->errors()]);
        }        
    }

    /* Edit Categories */
    public function editCategoryByID(){
        $id = $_GET['id'];

        $category_list = DB::table('categories')
            ->where('id', $id)
            ->get();

        $role = DB::table('role_user')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->where('role_user.user_id', Auth::user()->id)
            ->get();
								
        return view('ContentView.EditCategory', ['category_list' => $category_list, 'role' => $role ]);
    }

    public function editCategoryByIDPost(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'title'             => 'required',
        ],
        [
            'title.required'    => 'Please input title!',
        ]);
        
        if ($validator->passes()) {
            /* Update the database */
            /* Table: Categories */
            DB::table('categories')->where('id', strval($request->input('id'))) 
            ->update([
                'title'         => strval($request->input('title')),
                'description'   => strval($request->input('description'))
            ]);


            /* Return the message Success! */
            return response()->json(['success'=>'Save successfully!!!']);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    /* Add blog */
    public function addNewBlog(){
        $category_list = DB::table('categories')->get();
        $role = DB::table('role_user')
                        ->join('roles', 'role_user.role_id', 'roles.id')
                        ->where('role_user.user_id', Auth::user()->id)
                        ->get();

        return view('ContentView.CreateBlog', [ 'category_list' => $category_list , 'role' => $role ]);
    }

    public function addNewBlogPost(Request $request){
        $validation = Validator::make($request->all(), 
        [   
            'title'          => 'required',
            'content'        => 'required',
            'feature_images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id'    => 'required',
        ],[
            'title.required'    => 'Please input title!',
            'content.required'    => 'Please input content!',
            'feature_images.required'    => 'Please choose a image!',
            'feature_images.image'    => 'Please choose a image!',
            'category_id.required'    => 'Please choose a category!',
        ]);

        if ($validation->passes()){
            //$file = $request->file('feature_images');
            //$file->storeAs('images', $image_name);
            
            $file = $request->file('feature_images');
            $image_name = time().".".$file->getClientOriginalExtension();
            $extension = $file->getClientOriginalExtension();
            Storage::disk('public')->put($image_name,  File::get($file));
            
            /* Insert data to table Image */
            DB::table('images')->insert(
            [
                'url'           => 'images/'.$image_name,
                'name'          => $image_name
            ]);

            /* Insert data to table Articles */
            DB::table('articles')->insert( 
            [
                'title'         => strval($request->input('title')),
                'content'       => strval($request->input('content')),
                'image_id'      => DB::getPdo()->lastInsertId(),
                'status'        => strval($request->input('status')),
                'user_id'       => Auth::user()->id,
            ]);

            /* Insert data to article_category */
            DB::table('article_category')->insert( 
            [
                'article_id'    => DB::getPdo()->lastInsertId(),
                'category_id'  => strval($request->input('category_id')),
            ]);

            return response()->json(['success'=>'Save successfully!!!']);
        }
        else{
            return response()->json(['error'=>$validation->errors()]);
        }
    }

    /* Edit blog */
    public function editBlogByID(){
        $id = $_GET['id'];

        $category_list = DB::table('categories')->get();
        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status')
                            ->where('articles.id', $id)
                            ->get();
        $role = DB::table('role_user')
                            ->join('roles', 'role_user.role_id', 'roles.id')
                            ->where('role_user.user_id', Auth::user()->id)
                            ->get();
						
        return view('ContentView.EditBlog', [ 'category_list' => $category_list, 'blog_list' => $blog_list, 'role' => $role ]);
    }

    public function editBlogByIDPost(Request $request){
        $id = $request['id'];
        
        $validation = Validator::make($request->all(), 
        [   
            'title'          => 'required',
            'content'        => 'required',
            'category_id'    => 'required',
        ],[
            'title.required'            => 'Please input title!',
            'content.required'          => 'Please input content!',
            'category_id.required'      => 'Please choose a category!',
        ]);

        if ($validation->passes()){
            if ($request->file('feature_images')){
                $file = $request->file('feature_images');
                $image_name = time().".".$file->getClientOriginalExtension();
                $extension = $file->getClientOriginalExtension();
                Storage::disk('public')->put($image_name,  File::get($file));

                /* Insert data to table Image */
                DB::table('images')->insert(
                [
                    'url'           => 'images/'.$image_name,
                    'name'          => $image_name
                ]);

                /* Insert data to table Articles */
                DB::table('articles')->where('id', $id)
                ->insert([
                    'title'         => strval($request->input('title')),
                    'content'       => strval($request->input('content')),
                    'image_id'      => DB::getPdo()->lastInsertId(),
                    'status'        => strval($request->input('status')),
                    'user_id'       => strval(Auth::user()->name),
                ]);

                /* Insert data to article_category */
                DB::table('article_category')->insert( 
                [
                    'article_id'    => DB::getPdo()->lastInsertId(),
                    'category_id'  => strval($request->input('category_id')),
                ]);
            }
            else{
                /* Insert data to table Articles */
                DB::table('articles')->where('id', $id)
                ->update([
                    'title'         => strval($request->input('title')),
                    'content'       => strval($request->input('content')),
                    'status'        => strval($request->input('status')),
                    'user_id'       => strval(Auth::user()->id),
                ]);

                /* Insert data to article_category */
                DB::table('article_category')->where('article_id', $id)
                ->update([
                    'category_id'  => strval($request->input('category_id')),
                ]);
            }
           
            return response()->json(['success'=>'Save successfully!!!']);
        }
        else{
            return response()->json(['error'=>$validation->errors()]);
        }
    }

    /* Show the blog detail */
    public function showBlogDetails(){
        $screen_name = $_GET['screen_name'];
        $blog_id     = $_GET['id'];

        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->leftJoin('users', 'articles.user_id', 'users.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'users.name as author')
                            ->where('articles.id', $blog_id)
                            ->where('status', 1)
                            ->get();

        $max_id = DB::table('articles')->where('status', 1)->max('id');
        $min_id = DB::table('articles')->where('status', 1)->min('id');

        return view('ContentView.BlogDetails', [ 'screen_name'  => $screen_name, 
                                                'blog_list'     => $blog_list, 
                                                'current_id'    => $blog_id,
                                                'max_id'        => $max_id,
                                                'min_id'        => $min_id                                                
        ]);
    }

    /* Show the blog by category */    
    public function showBlogByCategory(){
        $category_id = $_GET['category_id'];
        $category_list = DB::table('categories')->get();

        if ($category_id == 'All'){            
            $blog_list = DB::table('articles')
                ->join('article_category', 'articles.id', 'article_category.article_id')
                ->join('categories', 'categories.id', 'article_category.category_id')
                ->join('images', 'articles.image_id', 'images.id')
                ->leftJoin('users', 'articles.user_id', 'users.id')
                ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                ->where('status', 1)
                ->get();
        }
        else{
            $blog_list = DB::table('articles')
                ->join('article_category', 'articles.id', 'article_category.article_id')
                ->join('categories', 'categories.id', 'article_category.category_id')
                ->join('images', 'articles.image_id', 'images.id')
                ->leftJoin('users', 'articles.user_id', 'users.id')
                ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                ->where('status', 1)
                ->where('categories.id', $category_id)
                ->get();
        }

        return view('ContentView.BlogByCategory', [ 'blog_list'  => $blog_list, 'category_list' => $category_list, 'category_id' => $category_id]);
    }

    /* Login handle */
    public function login(Request $request){
        /*
        $user_name = $_POST['user_name'];
        $password  = $_POST['password'];


        $check_login = DB::table('users')
                ->join('role_user', 'users.id', 'role_user.user_id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->select('users.id as id', 'users.name as name', 'users.email', 'roles.name as role', 'users.updated_at as last_update_date')
                ->where('users.name', $user_name)
                ->where('users.password', $password)
                ->get();

        if (count($check_login) > 0){
            $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name')
                            ->get();
            return  view('ContentView.BlogList', [ 'blog_list' => $blog_list, 'user_info' => $check_login ]);
        }
        else{
            return redirect('https://www.google.com');
        }
        */

        $user_name  = $request['user_name'];
        $password   = $request['password'];
        
        if (Auth::attempt(['name'=>$user_name, 'password'=>$password])){
            $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name')
                            ->get();

            $role = DB::table('role_user')
                            ->join('roles', 'role_user.role_id', 'roles.id')
                            ->where('role_user.user_id', Auth::user()->id)
                            ->get();

            return  view('ContentView.BlogList', [ 'blog_list' => $blog_list, 'user_id' => Auth::user()->id, 'role' => $role ]);
        }
        else   
            return view('ContentView.Login', [ 'error' => 'Username/ Password is incorrect!!!' ]);
    }

    public function Logout(){
        Auth::logout();
        return view('ContentView.HomePage');
    }

    /* Delete Category */
    public function deleteCategoryByIDPost(Request $request){
        $id = $request['id'];

        $checkExist = DB::table('categories')
                ->where('id', $id)
                ->get();

        if (count($checkExist) > 0){
            DB::table('categories')->where('id', $id)->delete();
            
            return response()->json(['success'=>'Delete successfully!!!']);
        }
        else{
            return response()->json(['error'=> 'Data does not exist!!!']);
        }
    }
    
    /* Delete User */
    public function deleteUserByIDPost(Request $request){
        $id = $request['id'];

        $checkExist = DB::table('users')
                ->where('id', $id)
                ->get();

        if (count($checkExist) > 0){
            DB::table('role_user')->where('user_id', $id)->delete();
            DB::table('users')->where('id', $id)->delete();
            
            return response()->json(['success'=>'Delete successfully!!!']);
        }
        else{
            return response()->json(['error'=> 'Data does not exist!!!']);
        }
    }

    /* Delete Blog */
    public function deleteBlogByIDPost(Request $request){
        $id = $request['id'];

        $checkExist = DB::table('articles')
                ->where('id', $id)
                ->get();

        if (count($checkExist) > 0){
            DB::table('article_category')->where('article_id', $id)->delete();
            DB::table('articles')->where('id', $id)->delete();
            
            return response()->json(['success'=>'Delete successfully!!!']);
        }
        else{
            return response()->json(['error'=> 'Data does not exist!!!']);
        }
    }

    /* Get blog data by its content */
    public function searchBlogByContent(Request $request){
        $search_content = $request['searchBlogInput'];
               
        if ($search_content != ""){
            $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->where('articles.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('articles.content', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('categories.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('users.name', 'LIKE', '%'.$search_content.'%')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->get();
        }
        else{
            
            $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->where('articles.title', 'LIKE', '%'.$search_content.'%')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->get();
        }
    
        return  view('ContentView.AllBlog', [ 'blog_list' => $blog_list ]);
    }

    public function searchBlogByCategoryAndContent(Request $request){
        $search_content = $request['searchBlogInput'];
        $category_id = $request['category_id'];

        $category_list = DB::table('categories')->get();

        if ($category_id == 'All'){     
            if ($search_content == ""){       
                $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->where('status', 1)
                    ->get();
            }
            else{
                $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->where('status', 1)
                    ->where('articles.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('articles.content', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('categories.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('users.name', 'LIKE', '%'.$search_content.'%')
                    ->get();
            }
        }
        else{
            if ($search_content == ""){       
                $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->where('status', 1)
                    ->where('categories.id', $category_id)
                    ->get();
            }
            else{
                $blog_list = DB::table('articles')
                    ->join('article_category', 'articles.id', 'article_category.article_id')
                    ->join('categories', 'categories.id', 'article_category.category_id')
                    ->join('images', 'articles.image_id', 'images.id')
                    ->leftJoin('users', 'articles.user_id', 'users.id')
                    ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name', 'article_category.category_id as category_id', 'articles.status as status', 'articles.updated_at as update_time', 'users.name as author')
                    ->where('status', 1)
                    ->where('categories.id', $category_id)
                    ->where('articles.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('articles.content', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('categories.title', 'LIKE', '%'.$search_content.'%')
                    ->orWhere('users.name', 'LIKE', '%'.$search_content.'%')
                    ->get();
            }
        }

        return view('ContentView.BlogByCategory', [ 'blog_list'  => $blog_list, 'category_list' => $category_list, 'category_id' => $category_id]);

    }
}