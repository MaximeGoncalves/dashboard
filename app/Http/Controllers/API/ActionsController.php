<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Http\Controllers\Controller;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionsController extends Controller
{
    public function index (Request $request){

        $actions = Action::where('ticket_id', $request->get('ticket'))->get();
        return response()->json(['actions' => $actions]);
    }

    public function store(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $action = new Action();
        $action->content = $request->get('content');
        $action->from_id = Auth::user()->id;
        $action->ticket()->associate($ticket);
        $action->save();
        return response()->json(['action' => $action ]);
//        return redirect(route('ticket.show', ['id' => $ticket->id]));
    }

    public function destroy(int $action, int $ticket)
    {
        $ticket = Ticket::findOrFail($ticket);
        Message::destroy($action);
        return redirect(route('ticket.show', ['id' => $ticket->id]));
    }
}
