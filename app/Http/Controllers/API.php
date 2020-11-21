<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB, User, Validator, Storage, File;

class API extends Controller
{
    public function getAllPost(){
        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->get();
        
        if (count($blog_list) > 0){
            return response()->json($blog_list);
        }
        else{
            return response()->json(array('error' => 'No data exist!!!'));
        }
    }

    public function getPostByCategoryTitle(Request $request){
        $title = $request->category_title;

        $blog_list = DB::table('articles')
                            ->join('article_category', 'articles.id', 'article_category.article_id')
                            ->join('categories', 'categories.id', 'article_category.category_id')
                            ->join('images', 'articles.image_id', 'images.id')
                            ->where('categories.title', 'like', '%'.$title)
                            ->select('articles.id as id', 'articles.title as title', 'content', 'categories.title as category', 'url', 'images.name as image_name')
                            ->get();
                        
        if (count($blog_list) > 0){
            return response()->json($blog_list);
        }
        else{
            return response()->json(['error' => 'No data exist!!!']);
        }
    }

    public function requestNewToken(){
        return view('APIForm.RequestNewToken', []);
    }

    public function requestPostForm(){
        $category_list = DB::table('categories')->get();
        return view('APIForm.RequestNewPost', ['category_list' => $category_list]);
    }

    public function requestForPublishnation(Request $request){
        $request_type = $request->request_type;
        // return response()->json($request_type);


        if ($request_type == 'initialize'){
            $user_name  = $request->user_name;
            $password   = ($request->password)."";
            //$checkAccountExist = DB::table('users')->where('name', 'like', '%'.$user_name)->where('password', 'like', '%'.bcrypt($password))->get()->toArray(); 

            //if (count($checkAccountExist) > 0){
            if (Auth::attempt(['name' => $user_name, 'password' => $password])){
                $token = bin2hex(random_bytes(64));

                $checkRequestHistoryExist = DB::table('request_history')->where('name', 'like', '%'.$user_name)->get();
                if (count($checkRequestHistoryExist) > 0){
                    DB::table('request_history')->where('name', 'like', '%'.$user_name)->update(['key' => $token]);
                }
                else{
                    DB::table('request_history')->insert(['name' => $user_name, 'key' => $token]);
                }

                return response()->json(array('user' => $user_name, 'key' => $token));
            }
            else{
                return response()->json(['error' => 'Username or password is incorrect!']);                
            }
        }
        
        if ($request_type == 'requestPost'){
            $user_name  = $request['user_name'];
            $key = $request['key'];
            $checkRequestHistoryExist = DB::table('request_history')->where('name', 'like', '%'.$user_name)->where('key', 'like', '%'.$key)->get();
            if (count($checkRequestHistoryExist) > 0){
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
                
                // Validation
                if ($validation->passes()){
                    $file = $request->file('feature_images');
                    $image_name = time().".".$file->getClientOriginalExtension();
                    $extension = $file->getClientOriginalExtension();
                    Storage::disk('public')->put($image_name,  File::get($file));

                    /* Get user id by its name */
                    $user_id = DB::table('users')->where('name', 'like', '%'.$user_name)->select('id')->get()[0]->id;

                    /* Insert data to table Image */
                    $image_id = DB::table('images')->insertGetId(
                    [
                        'url'           => 'images/'.$image_name,
                        'name'          => $image_name
                    ]);
 
                    /* Insert data to table Articles */
                    $article_id = DB::table('articles')->insertGetId( 
                    [
                        'title'         => strval($request->input('title')),
                        'content'       => strval($request->input('content')),
                        'image_id'      => $image_id,
                        'status'        => strval($request->input('status')),
                        'user_id'       => $user_id,
                    ]);

                    /* Insert data to article_category */
                    DB::table('article_category')->insert( 
                    [
                        'article_id'    => $article_id,
                        'category_id'   => strval($request->input('category_id')),
                    ]);

                    /* Update token */
                    $token = bin2hex(random_bytes(64));
                    DB::table('request_history')->where('name', 'like', '%'.$user_name)->update(['key' => $token]);

                    /* Return value */
                    return response()->json(['success'=>'Save successfully!!!', 'user' => $user_name, 'key' => $token]);
                }
                else{
                    return response()->json(['error'=>$validation->errors()]);
                }               
            }
            else{
                return response()->json(['key_error' => "Key doesn't exist!!!"]);
            }
        }
    }
}
