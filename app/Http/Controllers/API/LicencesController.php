<?php

namespace App\Http\Controllers\API;

use App\Licence;
use App\LicencesCategories;
use App\Society;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicencesController extends Controller
{
    public function index(){
        $users = User::all();
        $categories = LicencesCategories::all();
        $licences = Licence::with('society', 'user', 'category')->get()->groupBy('society.name');
        foreach($licences as $society){
            foreach($society as $licence){
                $licence->password = decrypt($licence->password);
            }
        }
        return response()->json([
            'users' => $users,
            'categories' => $categories,
            'licences' => $licences,
        ]);
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
            'user' => 'required',
            'category' => 'required',
        ]);

        $society = Society::findorFail($request->get('society'));

        $l = new Licence();
        $c = LicencesCategories::where('name', $request->category['name'])->first();
        if($c){
            $l->category()->associate($c);
        }else{
            $c = new LicencesCategories();
            $c->name = $request->category['name'];
            $c->save();
            $l->category()->associate($c);
        }
        $l->user()->associate($request->get('user'));
        $l->society()->associate($society);
        $l->licence = $request->licence;
        $l->email = $request->email;
        $l->password = encrypt($request->password);
        $l->save();
        return response($l);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


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
            'society' => 'required',
            'user' => 'required',
            'licence' => 'required',
            'category' => 'required',
        ]);

        $society = Society::findorFail($request->get('society'));
        $l = Licence::findOrFail($id);
        $l->category()->associate($request->get('category'));
        $l->user()->associate($request->get('user'));
        $l->society()->associate($society);
        $l->licence = $request->licence;
        $l->email = $request->email;
        $l->password = encrypt($request->password);
        $l->save();
        return response('save');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        Licence::destroy($id);
        return ['message' => 'Licence supprimé'];
    }

    public function createCategory(Request $request){

    }
}
