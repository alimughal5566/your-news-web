<?php

namespace App\Http\Controllers;
use App\Category;
use App\Models\Auth\User;
use App\News;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Firestore;
use App\Http\Controllers\Controller;


/**
 * Class Controller.
 */
class ApiController extends Controller
{
   protected $db;

   public function __construct(Firestore $firestore){
    $this->db = $firestore;
   }

   public function cloneData(){
       $db = $this->db;
       $database = $db->database();
       $docRef = $database->collection('Profile');
       $documents = $docRef->documents();
       foreach ($documents as $document){
        if($document->exists()){
            $data = $document->data();
            $user = User::where('email' , $data['Email'])->first();
            if(!$user){
                $user = new User;
                $user->email = $data['Email'];
                $user->first_name = $data['FullName'];
                $user->last_name = $data['FullName'];
                $user->password = bcrypt('1234');
                $user->save();
            }
            Category::where('user_id',$user->id)->delete();
            foreach ($data['Categories'] as $categories) {
                $category = new Category;
                $category->user_id = $user->id;
                $category->category_name = $categories;
                $category->save();
            }
            News::where('user_id',$user->id)->delete();
            foreach ($data['NewsChannel'] as $news){
                $newsChannel = new News;
                $newsChannel->user_id = $user->id;
                $newsChannel->channel_name = $news;
                $newsChannel->save();
            }
        }
       }
   }
}
