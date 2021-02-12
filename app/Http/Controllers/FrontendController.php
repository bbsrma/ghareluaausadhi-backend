<?php

namespace App\Http\Controllers;
// use Log;
use Illuminate\Http\Request;
use App\Disease;
use DB;
use App\User;
use App\View;

class FrontendController extends Controller
{
    public function diseaseByCategory(){
        $diseases = Disease::paginate(20);
        $groupbycategory = $diseases->mapToGroups(function($disease){
            return [ $disease->category => $disease];
        } );
        $diseasebycategory = $diseases->setCollection($groupbycategory);
        return response()->json($diseasebycategory);
    }
    public function showAllPost(){
        $diseases = Disease::with('view')->paginate(12);
        return response()->json($diseases);
    }
    public function categoryDisease($category){
        $diseases = Disease::where('category' , $category)->with('view')->paginate(12);
        return $diseases;
    }
    public function showTrendingDiseases(){
        $diseases = Disease::all();
        return response()->json($diseases);
    }
    public function showPostByName($name){
        $diseases = Disease::where('name', 'LIKE' ,"%$name%")->with('view')->paginate(12);
        return response()->json($diseases);
    }
    public function showPostDetails($postId){
        $disease = Disease::where('id',$postId)->with('solutions')->with('view')->first();
        if(empty($disease->view)){
                $view = new View;
                $view->view_count = 1;
            }
        else{
                $view = View::where('disease_id' , $disease->id)->first();
                $view->view_count += 1;
            }
        $view->disease_id = $disease->id;        
        $view->view_from = 'np';
        $view->save();
        return response()->json($disease);
    }
    public function getUsers(Request $request){
        return response()->json(User::all());
    }
    public function showTopViewed(){
        $views = View::orderBy('view_count','desc')->with('disease')->take(9)->get();
        return response()->json($views);
    }
}