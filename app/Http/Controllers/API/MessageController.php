<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Http\Controllers\Controller;
use App\Message;
use App\Notifications\NewMessage;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index (Request $request){

        $messages = Message::where('ticket_id', $request->get('ticket'))->whereNull('to_id')->get();
        return response()->json(['messages' => $messages]);
    }
    /**
     * @param Request $request
     * @param $ticket
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $ticket)
    {

        $this->validate($request, [
            'content' => 'required',
        ]);

        $ticket = Ticket::findOrFail($ticket);
        $message = new Message();
        $message->content = $request->get('content');
        $message->from_id = Auth::user()->id;
        $message->ticket()->associate($ticket);

        // Si c'est une response à un message :
        if ($request->to_id):
            $message->to_id = $request->to_id;
            $toId = Message::with('from')->findOrFail($request->to_id);
            $action = new Action();
            $action->content = 'à répondu à ' . $toId->from->fullname;
            $action->from_id = Auth::user()->id;
            $action->ticket()->associate($ticket);
            $action->save();
        else:
            $action = new Action();
            $action->content = 'à poster un message';
            $action->from_id = Auth::user()->id;
            $action->ticket()->associate($ticket);
            $action->save();
        endif;
        $message->save();

        $message->load('from');
//        $user = User::find($message->to_id);
        //send message
//        if($user->id !== 1){
//        $softease = User::find(1);
//        $softease->notify(new NewMessage($message, $ticket));
//        }
//        $user->notify(new NewMessage($message, $ticket));

        return response()->json(['messages' => $message]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $message)
    {

        $this->validate($request, [
            'content' => 'required',
        ]);

        $message = Message::findOrFail($message);
        $message->content = $request->get('content');
        $message->from_id = Auth::user()->id;
        $message->save();

        return response()->json(['message' => 'success']);
    }

    public function destroy(int $message, int $ticket)
    {
        $ticket = Ticket::findOrFail($ticket);
        Message::destroy($message);
        return redirect(route('ticket.show', ['id' => $ticket->id]));
    }
}
