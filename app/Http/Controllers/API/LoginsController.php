<?php

namespace App\Http\Controllers\API;

use App\login;
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
//        if($request->ajax()){
//            $search = $request->get('search');
//            $logins = Login::whereHas('society', function ($query) use ($search) {
//                $query->where('name', 'like', '%' . $search . '%');
//            })->orWhere('name', 'LIKE', '%' . $search . '%')
//                ->orWhere('username', 'LIKE', '%' . $search . '%')->orderBy('name', 'asc')->get();
//            $data = '';
//            foreach ($logins as $login){
//                $data .= '<tr><td>'. $login->society->name .'</td>
//                                <td>'. $login->name .'</td>
//                                <td>'. $login->url .'</td><td>'. $login->username .'</td>
//                                <td>'. decrypt($login->password) .'</td>
//                                <td><div class="btn-group">
//                            <a href="'.route('login.edit', [ $login->id ] ).'">
//                            <i class="fa fa-pencil" style="color:grey; font-size: 20px;"></i></a>
//                            <form action="'.route('login.destroy', $login->id).'" method="POST">
//                            '.csrf_field().'
//                            <input type="hidden" name="_method" value="delete" />
//                            <button type="submit" style="border: none; background: transparent; cursor: pointer;"
//                                    class="d-inline" onclick="return confirm(\'Etes vous sûr de vouloir supprimer le ticket ?\');">
//                                <i class="fa fa-trash ml-2" style="color:red;font-size: 20px"></i>
//                            </button>
//                            </form>
//                        </div></td>
//                         </tr>';
//            }
//            return json_encode($data);
//        }
//        if ($request->get('search')) {
//            $search = $request->get('search');
//            $logins = Login::whereHas('society', function ($query) use ($search) {
//                $query->where('name', 'like', '%' . $search . '%');
//            })->orWhere('name', 'LIKE', '%' . $search . '%')
//                ->orWhere('username', 'LIKE', '%' . $search . '%')->orderBy('name', 'asc')->get();
////            $logins = Login::with(['society'])->where('name', 'LIKE',  '%'.$search.'%')->orderBy('name')->get();
////            dd($logins);
//            return view('admin.logins.index', ['logins' => $logins]);
//        }
        $logins = Login::with('society')->get();
        foreach($logins as $log){
            $log->password = decrypt($log->password);
        }
        return response()->json(['logins' => $logins]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
