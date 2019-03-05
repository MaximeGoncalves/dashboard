<?php

namespace App\Http\Controllers\API;

use App\Knowledges;
use App\KnowledgesCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KnowledgesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = KnowledgesCategory::with('knowledges')->get();
        $knowledges = Knowledges::with('category')->get();

        return response()->json([
            'knowledges' => $knowledges,
            'categories' => $categories,
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
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        $k = new Knowledges();
        $k->title = $request->get('title');
        $k->content = $request->get('content');
        $k->user()->associate(Auth::user());
        $c = KnowledgesCategory::where('name', $request->category['name'])->first();
        if($c){
            $k->category()->associate($c);
        }else{
            $c = new KnowledgesCategory();
            $c->name = $request->category['name'];
            $c->save();
            $k->category()->associate($c);
        }
        $k->save();

        return response()->json($k);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $knowledge = Knowledges::findOrFail($id);
        $knowledge->load('category');
        $categories = KnowledgesCategory::all();
        return response()->json(['response' => compact('knowledge', 'categories')]);

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
        $k = Knowledges::findOrFail($id);
        $k->title = $request->title;
        $k->content = $request->get('content');
        $c = KnowledgesCategory::where('name', $request->category['name'])->first();
        if($c){
            $k->category()->associate($c);
        }else{
            $c = new KnowledgesCategory();
            $c->name = $request->category['name'];
            $c->save();
            $k->category()->associate($c);
        }
        $k->save();
        return response()->json($k);

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

    public function createCategory(Request $request){
        $category = New KnowledgesCategory();
        $category->name = $request->name;
        $category->save();

        return response()->json(['category' => $category]);
    }

}
