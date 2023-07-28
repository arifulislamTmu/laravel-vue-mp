<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

     $search = request('query');

    $users = User::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('created_at', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(3);


       return  $users ;

    }

    public function store(){

        request()->validate([
            'email'=>'email|unique:users,email'
        ]);

      return  User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password'),
      ]);
    }

    public function updateUser()
    {
        $id = request('id');
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ];
        request()->validate($rules);
        $user = User::findOrFail($id);
        $user->name = request('name');
        $user->email = request('email');
        $user->save();
        return $user;
    }

    public function roleChange(User $user){

        $user->update([
            'role' => request('role'),
        ]);
        return response()->json('success', 200);
    }


//    public function searchQuery(){

//     $search = request('query');

//     $users = User::query()
//         ->where(function ($query) use ($search) {
//             $query->where('name', 'LIKE', "%{$search}%")
//                   ->orWhere('email', 'LIKE', "%{$search}%")
//                   ->orWhere('created_at', 'LIKE', "%{$search}%");
//         })->paginate(3);

//         return response()->json($users);

//     }


public function logout(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();
}
}
