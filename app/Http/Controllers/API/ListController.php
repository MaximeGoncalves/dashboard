<?php

namespace App\Http\Controllers\API;

use App\Lists;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    public function index()
    {
        return $lists = Lists::orderBy('order', 'asc')->get();
    }

    public function store(Request $request)
    {
        $list = new Lists();
        $list->name = $request->name;
        $list->order = $request->order;
        $list->board()->associate($request->board);
        $list->save();
        $list->load('tasks');
        return $list;
    }

    public function destroy(Request $request, $id)
    {
        $list = Lists::findOrFail($id);
        $list->delete();
        $list->tasks()->delete();
        return $list;
    }

    public function update(Request $request, $id)
    {
        $list = Lists::FindOrFail($id);
        $list->name = $request->get('name');
        $list->save();
        return $list;
    }

    public function order(Request $request)
    {
        $newLists = $request->lists;
        $lists = Lists::where('board_id', $request->board)->get();

        foreach ($lists as $list) {
            foreach ($newLists as $newList) {
                if ($newList['id'] == $list->id) {
                    $list->order = $newList['order'];
                    $list->save();
                }
            }
        }
    }


}
