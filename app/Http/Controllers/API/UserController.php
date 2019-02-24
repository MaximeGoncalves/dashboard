<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('isAdmin');
        return User::with('society')->latest()->paginate(50);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:users',
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'required|string|min:6',
            'role' => 'required',
            'society' => 'required',
            'profession' => 'nullable|string|max:191',
            'phone' => 'nullable|numeric|digits:10',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->fullname = $request->get('fullname');
        $user->email = $request->get('email');
        $user->role = $request->get('role');
        $user->password = Hash::make($request->get('password'));
        $user->society_id = $request->get('society');
        $user->profession = $request->profession;
        $user->phone = $request->phone;
        $user->save();
        return response()->json(['success' => 'Done!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $user = User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:users,name,' . $user->id,
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'required|string|min:6',
            'society' => 'required',
            'profession' => 'nullable|string|max:191',
            'phone' => 'nullable|numeric|digits:10',
        ]);

        $user->name = $request->name;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->society()->associate($request->society);
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->profession = $request->profession;
        $user->phone = $request->phone;
        $user->save();
        return response(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        User::destroy($id);
        return ['message' => 'Utilisateur supprimé'];
    }

    public function profile()
    {
        $user = auth('api')->user();
        return $user->load('Society');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'nullable|confirmed|min:6',
            'profession' => 'nullable|string|max:191',
            'phone' => 'nullable|numeric|digits:10',
        ]);

        $user = \auth('api')->user();
        $currentPhoto = $user->photo;
        if ($request->photo != $currentPhoto) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $path = public_path('img/profile/'. $user->name);
            if(!file_exists($path)){
                mkdir($path, 0777, true);
            }
            Image::make($request->photo)->save($path.'/'. $name);
            $request->merge(['photo' => $name]);

            $userPhoto = $path. '/'. $currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->photo = $request->photo;
        if($request->password != ''){
            $user->password = bcrypt($request->password);
        }

        $user->save();
        return response(['message' => 'Success']);

    }
}
