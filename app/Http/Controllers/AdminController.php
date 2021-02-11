<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Disease;
use App\Solution;
use App\User;
use Hash;
use Auth;
use Validator;

class AdminController extends Controller
{
    public function createUser(CreateUserRequest $request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        $accessToken = $user->createToken('authToken')->accessToken;
        $response = ['user' => $user,
                     'accessToken' => $accessToken
                ];
        return response()->json($response);
    }
    public function login(LoginRequest $request){
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            // Authentication passed...
            $objectToken = auth()->user()->createToken('authToken');
            $accessToken = $objectToken->accessToken;
            $expires_at = $objectToken->token->expires_at;
            $response = [
                        'user' => auth()->user(),
                        'accessToken' => $accessToken,
                        'expires_at' => $expires_at,
                    ];
        }
        else{
            $response = [
                "statusText" => 'not Ok',
                "message" => 'mismatch Credentials'
            ];
            
        }
        return response()->json($response);
    }
    public function userDetails($id){
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function getAllUser(Request $request ){
        $user = auth()->user();
        if($user->role == 'admin')
            $users = User::all();
        else
            $users = [$user];
        return response()->json($users);
    }
    public function verifyUser(Request $request , $id){
        $authUser = auth()->user();
        $users = $authUser;
        if($authUser->role == 'admin'){
            $user = User::findOrFail($id);
            $user->isVerified = true;
            $user->save();
            $users = User::all();
        }        
        return response()->json($users);
    }
    public function promoteUser(Request $request, $id){
        $authUser = auth()->user();
        // $users = $authUser;
        if($authUser->role == 'admin'){
            $user = User::findOrFail($id);
            $user->role = 'admin';
            $user->save();
            $users = User::all();
        }        
        return response()->json($users);
    }

    public function postDisease(Request $request){
        $inputdata = $request->all();
        $disease = new Disease;
        $disease->name = $inputdata['postdetails']['name'];
        $disease->category = $inputdata['postdetails']['category'];
        $disease->postedby = $inputdata['created_by'];
        $disease->save();
        foreach( $inputdata['postdetails']['solutions'] as $sol){
            $solution = new Solution;
            $solution->solution= $sol;
            $solution->disease_id = $disease->id;
            $solution->save();
        }
        return response()->json($disease);
    }

    public function getAllDiseases( $userId ){
        $user = User::findOrFail($userId);
        if($user->role == 'admin')
            $diseases = Disease::where('id','>=', 1)->with('solutions')->with('view')->paginate(15);
        else
            $diseases = Disease::where('postedby', $userId)->with('solutions')->with('view')->paginate(15);
        return response()->json($diseases);
    }
    public function getPostDetails( $postId ){
        $disease = Disease::where('id',$postId)->with('solutions')->with('user')->first();
        return $disease;
    }
    public function deletePost( $postId){
        // return $postId .' postid Deleted';
        $response= [];
        $diseases = Disease::where('id',$postId)->with('solutions')->with('view')->get();
        if(count($diseases) <= 0){
        $response = [ "status" => 'fail',
                    "message" => 'Couldnot Delete'];
        return $response;
        }
        $disease = $diseases[0];

        if(count($disease->solutions) >= 1){
            $solutions = $disease->solutions;
            foreach( $solutions as $solution){
                $solution->delete();
            }
        }
        if($disease->view){
            $view = $disease->view;
            $view->delete();
        }
        $disease->delete();
        $response = [ "status" => 'success',
                        "message" => 'successfullly Deleted',
                        "disease" => $disease,
                    ];
        return response()->json($response);
    }

    public function getEmailStatus($email){
        $user = User::where('email', $email)->first();
        // return $user;
        if(!$user)
            return 1;
        else 
            return 0;
        // return $user;
    }
}