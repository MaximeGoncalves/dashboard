<?php

namespace App\Http\Controllers\API;

use App\Login;
use App\Society;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $logins = Login::with('society')->get();
        foreach ($logins as $log) {
            $log->password = decrypt($log->password);
        }
        $societies = Society::all();
        return response()->json(compact(['logins', 'societies']));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'society' => 'required',
            'name' => 'required',
            'url' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $login = new Login;
        $society = $request->society;
        $login->name = $request->name;
        $login->url = $request->url;
        $login->username = $request->username;
        $login->password = encrypt($request->password);
        $login->society()->associate($society['id']);
        $login->save();

        return response()->json(['login' => $login]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $login = Login::findOrFail($id);
        $login->password = decrypt($login->password);
        $login->load('society');
        return response()->json(compact(['login']));
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
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required',
            'username' => 'required',
            'password' => 'required',
            'society' => 'required'
        ]);
        $login = Login::findOrFail($id);
        $society = $request->society;
        $login->society()->associate($society['id']);
        $login->name = $request->name;
        $login->url = $request->url;
        $login->username = $request->username;
        $login->password = encrypt($request->password);
        $login->save();
        return response()->json(['login' => $login]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Login::destroy($id);
        return response('Ok');
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $logins = Login::with('society')
            ->where('name', 'like', "%$search%")
            ->orWhere('username', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")->get();
        foreach ($logins as $log) {
            $log->password = decrypt($log->password);
        }
        return $logins;

    }
}
