<?php

namespace App\Http\Controllers\API;

use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    public function store(Request $request)
    {
        $type = new Type();
        $type->name = $request->name;
        $type->save();
        return $type;
    }

    public function destroy(Request $request, $id){
        $type = Type::findOrFail($id);
        $type->delete();
        return $type;
    }
}
