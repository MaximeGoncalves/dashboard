<?php

namespace App\Http\Controllers;

use App\Message;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageTaskController extends Controller
{

    public function index()
    {

    }

    public function store(Request $request, $id)
    {
        $task = Task::find($id);
        $message = new Message();
        $message->content = $request->get('content');
        $message->commentable_type = Task::class;
        $message->commentable_id = $task->id;
        $message->from_id = Auth::user()->id;
        $message->save();

        return $message;
    }
}
