<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Role;
use Illuminate\Http\Request;
use App\User;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        // None
    }

    public function lawyers(Request $request)
    {
        $lawyers = User::lawyers();
        $order = empty($request->order) ? 'name' : $request->order;
        
        if (!empty($request->search) && $request->search != 'null') {    ;
            $lawyers = User::lawyers()
            ->where('name', 'like', '%' . ($request->search . '%'));
        }

        if (in_array(strtolower($request->sort), ['asc', 'desc'])) {
            $lawyers->orderBy($order, $request->sort);
        }

        return UserResource::collection(
            $lawyers->paginate(6)
        );
    }

    public function show($id)
    {
        try {
            return new UserResource(User::lawyers()->findOrFail($id));
        } catch (Exception $e) {
            return response(['message' => 'Not found!']);
        }
    }
}
