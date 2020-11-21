<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Auth;
use DB, User, Validator, Storage, File;

class API_FireBase extends Controller
{   
    // protected $customer_master_col_array = array('customer_id', 'name', 'postal', 'postal_code', 'address', 'represetative_telephone_number', 'fax_number', 'url', 'internal_memo');
    // protected $image_col_array = array('id', 'url', 'name', 'description');
    // protected $category_col_array = array('id', 'title', 'description');
    // protected $article_col_array = array('id', 'user_id', 'title', 'content', 'status');
    // protected $article_category_col_array = array('article_id', 'category_id');

    protected $article_col = array('article_title', 'author', 'category_title', 'content', 'status', 'description', 'img_name', 'img_url');
    protected $request_history_col = array('user_name', 'token');

    
    public function addDataToFirebase($database, $table_name, $dataArr = []){   // (database_variable, table_name, data)
        switch ($table_name){
            // case 'customer_master':
            //     $colArr = 'customer_master_col_array';
            //     break;
            // case 'images':
            //     $colArr = 'image_col_array';
            //     break;
            // case 'categories':
            //     $colArr = 'category_col_array';
            //     break;   
            // case 'articles':
            //     $colArr = 'article_col_array';
            //     break;   
            // case 'article_category':
            //     $colArr = 'article_category_col_array';
            //     break;  
            
            case 'articles':
                $colArr = 'article_col';
                break;
            case 'request_history':
                $colArr = 'request_history_col';
                break;
            default:
                break;
        }
        
        $itemArr = array();
        foreach($this->$colArr as $item){
            $itemArr[$item] = $dataArr[$item];
        }
        $database->getReference($table_name)->push($itemArr);      
    }

    /* Insert base data */
    public function initializeData(){
        /* Connect to Firebase */
        $serviceAccount = (new Factory)->withServiceAccount(__DIR__.'/agl-task4-api-firebase-adminsdk-l7mhm-1d4f9e9c22.json');
        $database = $serviceAccount->createDatabase();

        /* Table: Images */
        // $this->addDataToFirebase($database, 'images', ['id' => 23, 'url' => 'images/1602656627.png', 'name' => '1602656627.png', 'description' => '']);

        /* Table: Category */
        // $this->addDataToFirebase($database, 'categories', ['id' => 9, 'title' => 'Category 1', 'description' => 'Category 1 Description']);

        /* Table Articles */
        // $this->addDataToFirebase($database, 'articles', ['id' => 22, 'user_id' => '5', 'title' => 'Blog 1', 'content' => 'Content for blog 1', 'status' => '1']);
        // $this->addDataToFirebase($database, 'articles', ['id' => 24, 'user_id' => '6', 'title' => 'Blog 2', 'content' => 'Content for blog 2', 'status' => '1']);
        
        /* Table Article_category */
        // $this->addDataToFirebase($database, 'article_category', ['article_id' => 23, 'category_id' => 9]);
        // $this->addDataToFirebase($database, 'article_category', ['article_id' => 24, 'category_id' => 9]);

        /* [NEW] Table: Articles: */
        $this->addDataToFirebase($database, 'articles', 
                                            [
                                                'article_title'     => 'Post create by API with FireBase',
                                                'author'            => 'TamLNM', 
                                                'category_title'    => 'Training',
                                                'content'           => 'Content of Post create by API with FireBase', 
                                                'status'            => 'Public', 
                                                'description'       => 'No description',
                                                'img_name'          => '1602656627.png',
                                                'img_url'           => 'images/1602656627.png',
                                            ]);
    }
    
    public function getArticlesData($category_title = ''){
        $serviceAccount = (new Factory)->withServiceAccount(__DIR__.'/agl-task4-api-firebase-adminsdk-l7mhm-1d4f9e9c22.json');
        $database = $serviceAccount->createDatabase();
        
        if ($category_title == ''){
            return Array($database->getReference('articles')->getValue())[0];
        }
        
        return Array($database->getReference('articles')->orderByChild('category_title')->equalTo($category_title)->getValue())[0];

        // return Array($database->getReference('articles')->orderByChild('category_title')->equalTo($category_title)->getValue())[0];
        // $articleData = Array($database->getReference('articles')->getValue())[0];
        // $articleCategoryData = Array($database->getReference('article_category')->getValue())[0];
        // $categoryData = Array($database->getReference('categories')->getValue())[0];
        // foreach ($articleData as $item){
        //     foreach($articleCategoryData as $acItem){
        //         if ($acItem['article_id'] == $item['id']){
        //             foreach($categoryData as $cItem){
        //                 if ($category_title == '' || ($category_title && $category_title == $cItem['title']))
        //                 if ($cItem['id'] == $acItem['category_id']){
        //                     $dataArr[] = array(
        //                         'id'        => $item['id'],
        //                         'user_id'   => $item['user_id'],
        //                         'title'     => $item['title'],
        //                         'category_title' => $cItem['title'],
        //                         'content'   => $item['content'],
        //                         'status'    => $item['status'],
        //                         'category_id'   => $cItem['id'],  
        //                         'description'   => $cItem['description']
        //                     );
        //                 }
        //             }
        //         }
        //     }
        // }
    }
    
    public function getAllPost(){
        if (count($this->getArticlesData()) > 0 )
            return response()->json($this->getArticlesData());
        else
            return response()->json(array('error' => 'No data exist!!!'));
    }

    public function getPostByCategoryTitle(Request $request){
        if ($this->getArticlesData($request->category_title))
            return response()->json($this->getArticlesData($request->category_title));
        else
            return response()->json(['error' => 'No data exist!!!']);
    }

    public function requestNewToken(){
        return view('APIForm.RequestNewToken', []);
    }
    public function requestPostForm(){
        return view('APIForm.FireBase_RequestNewPost', []);
    }

    public function requestForPublishnation(Request $request){
        $serviceAccount = (new Factory)->withServiceAccount(__DIR__.'/agl-task4-api-firebase-adminsdk-l7mhm-1d4f9e9c22.json');
        $database = $serviceAccount->createDatabase();

        $request_type = $request->request_type;           

        if ($request_type == 'initialize'){
            $user_name  = $request->user_name;
            $password   = ($request->password)."";

            if (Auth::attempt(['name' => $user_name, 'password' => $password])){
                $token = bin2hex(random_bytes(64));

                //$checkRequestHistoryExist = DB::table('request_history')->where('name', 'like', '%'.$user_name)->get();
                $checkRequestHistoryExist = $database->getReference('request_history')->orderByChild('user_name')->equalTo($user_name)->getValue();
                if (count($checkRequestHistoryExist) > 0){
                    //DB::table('request_history')->where('name', 'like', '%'.$user_name)->update(['key' => $token]);
                    $parentUri = key((array)$checkRequestHistoryExist);
                    $database->getReference('request_history/'.$parentUri)->update(array('user_name' => $user_name, 'token' => $token));
                }
                else{
                    //DB::table('request_history')->insert(['name' => $user_name, 'key' => $token]);
                    $this->addDataToFirebase($database, 'request_history', ['user_name' => $user_name, 'token' => $token]);
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
            //$checkRequestHistoryExist = DB::table('request_history')->where('name', 'like', '%'.$user_name)->where('key', 'like', '%'.$key)->get();
            $checkRequestHistoryExist = $database->getReference('request_history')->orderByChild('user_name')->equalTo($user_name)->getValue();
            if (count($checkRequestHistoryExist) > 0){
                $parentUri = key((array)$checkRequestHistoryExist);
                
                if ($checkRequestHistoryExist[$parentUri]['token'] == $key){
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
                        $file = $request->file('feature_images');
                        $image_name = time().".".$file->getClientOriginalExtension();
                        $extension = $file->getClientOriginalExtension();
                        Storage::disk('public')->put($image_name,  File::get($file));

                        /* Save data to aricles */
                        $this->addDataToFirebase($database, 'articles', 
                                            [
                                                'article_title'     => strval($request->input('title')),
                                                'author'            => $user_name, 
                                                'category_title'    => strval($request->input('category_id')),
                                                'content'           => strval($request->input('content')), 
                                                'description'       => strval($request->input('description')), 
                                                'status'            => strval($request->input('status')) == 1 ? 'Public' : 'Draft',
                                                'img_name'          => $image_name,
                                                'img_url'           => 'images/'.$image_name,
                                            ]);
                                                
                        /* Update new token */
                        $token = bin2hex(random_bytes(64));
                        $database->getReference('request_history/'.$parentUri)->update(array('user_name' => $user_name, 'token' => $token));

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
            else{
                return response()->json(['key_error' => "Key doesn't exist!!!"]);
            }
        }
    }

    public function getLastestPost(){
        $serviceAccount = (new Factory)->withServiceAccount(__DIR__.'/agl-task4-api-firebase-adminsdk-l7mhm-1d4f9e9c22.json');
        $database = $serviceAccount->createDatabase();
        
        return Array($database->getReference('articles')->orderByChild('category_title')->limitToLast(1)->getValue())[0]; 
    }
}
