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
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $id)
    {

//        $request->session()->put('previous-route', "message.store");
        $this->validate($request, [
            'content' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $message = new Message();
        $message->content = $request->get('content');
        $message->from_id = Auth::user()->id;
        if (Auth::user()->id == $ticket->user->id):
            $message->to_id = 1;
        else:
            $message->to_id = $ticket->user->id;
        endif;
        $message->ticket()->associate($ticket);
        $message->save();
        $action = new Action();
        $action->content = 'à poster un message';
        $action->from_id = Auth::user()->id;
        $action->ticket()->associate($ticket);
        $action->save();

        $user = User::find($message->to_id);
        //send message
//        if($user->id !== 1){
//        $softease = User::find(1);
//        $softease->notify(new NewMessage($message, $ticket));
//        }
//        $user->notify(new NewMessage($message, $ticket));

        return response()->json(['message' => 'success']);
    }

    public function destroy(int $message, int $ticket)
    {
        $ticket = Ticket::findOrFail($ticket);
        Message::destroy($message);
        return redirect(route('ticket.show', ['id' => $ticket->id]));
    }
}
