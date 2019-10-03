<?php

namespace App\Http\Controllers\API;

use App\Board;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::all();
        $userBoard = [];
        foreach ($boards as $board){
            if($board->user_id === Auth::user()->id){
                $userBoard[] = $board;
            }
            else if($board->members){
                foreach ($board->members as $member){
                    if($member->id === Auth::user()->id){
                        $userBoard[] = $board;
                    }
                }
            }
        }
        return $userBoard;
    }

    public function store(Request $request)
    {
        $board = new Board();
        $board->name = $request->get('name');
        $board->user()->associate(Auth::user()) ;
        $board->save();
        return $board;
    }

    public function show(Request $request, $id){
        return $board = Board::with([
            'lists' => function ($query){
                $query->orderBy('order','ASC');
            },
            'tasks' => function ($query){
                $query->onlyTrashed()->get();
            }
        ])->findOrFail($id);
    }

    public function addMembers(Request $request, $id){
        $members = $request->get('members');
        $membersObject = (object) $members;
        $board = Board::findOrFail($id);
        $board->members = json_encode($membersObject);
        $board->save();
        return $members;
    }
}
