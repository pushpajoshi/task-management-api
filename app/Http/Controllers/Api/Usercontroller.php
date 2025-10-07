<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class Usercontroller extends Controller
{
    //user listing
    public function index(Request $request)
    {
        $authId = Auth::user()->id;
        $query = User::where('id','!=',$authId);

        // Optional search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Exclude soft-deleted users by default
        $users = $query->SimpleDetails()
                       ->paginate($request->get('per_page', 10));
            return send_response(200, __('api.succ'), $users);   

    }

    //update user role
    public function updateRole(Request $request, $id)
    {
        $request->validate(['role'=>'required|in:admin,user',]);
        $user = User::SimpleDetails()->find($id);
        if (!$user) {
            return send_error(__('api.err_user_not_found'),null,404, false);
        }

        $user->update(['role' => $request->role]);
        return send_response(200, __('api.succ_userr_role_update'), $user);   
    } 

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return send_error(__('api.err_user_not_found'),null,404, false);
        }

        $user->tokens()->delete();
        $user->delete();
        return send_response(200, __('api.succ_user_deleted'));   
    }
}
