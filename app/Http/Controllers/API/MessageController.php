<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Http\Controllers\Controller;
use App\Jobs\NewMessageJob;
use App\Mail\NewMessageEmail;
use App\Message;
use App\Notifications\NewMessage;
use App\Task;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $messages = Message::where('commentable_id', $request->get('element'))->whereNull('to_id')->get();
        return response()->json(['messages' => $messages]);
    }

    /**
     * @param Request $request
     * @param $ticket
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $id)
    {

        $this->validate($request, [
            'content' => 'required',
            'type' => 'required'
        ]);

        $type = $request->get('type');
        $element = $type == 'Ticket' ? Ticket::findOrFail($id) : Task::findOrFail($id);
        $message = new Message();
        $message->content = $request->get('content');
        $message->from_id = Auth::user()->id;
        $message->commentable_type = $type == 'Ticket' ? Ticket::class : Task::Class;
        $message->commentable_id = $element->id;

        // Si c'est une response à un message :
        if ($request->to_id) {
            $message->to_id = $request->to_id;
            if ($type == 'Ticket') {
                $toId = Message::with('from')->findOrFail($request->to_id);
                $user = User::findOrFail($element->user_id);
                $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
                $action = new Action();
                $action->content = 'à répondu à ' . $toId->from->fullname;
                $action->from_id = Auth::user()->id;
                $action->ticket()->associate($element);
                $action->save();
                $this->dispatch(new NewMessageJob($element, $toId->from,$leader, $response = true));
            }
        } elseif (!$request->to_id && $type == 'Ticket') {
            $action = new Action();
            $action->content = 'à poster un message';
            $action->from_id = Auth::user()->id;
            $action->ticket()->associate($element);
            $action->save();
            $user = User::findOrFail($element->user_id);
            $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
            $this->dispatch(new NewMessageJob($element, $user, $leader, $response = false));
        }
        $message->save();

        $message->load(['from', 'parent']);
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

    public function destroy(int $message)
    {
//        $ticket = Ticket::findOrFail($ticket);
        Message::destroy($message);
    }

    private function getMentionUsers($request)
    {
        $content = str_word_count($request, 1, '@');

        $users = [];
        foreach ($content as $word) {

            if (($pos = strpos($word, "@")) !== FALSE) {
                $whatIWant = substr($word, $pos + 1);
                $users[] = $whatIWant;
            }
        }
        return $users;
    }

//    non fonctionnell , en cours ...
    public function sendEmailMessage(Request $request)
    {

        $message = Message::findOrFail($request->message);
        $ticket = Ticket::findOrfail($request->commentable_id);
        $user = User::findOrFail($ticket->user_id);
        $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();

        if ($message->to_id) {
            $toId = Message::with('from')->findOrFail($request->to_id);
            $this->dispatch(new NewMessageJob($ticket, $user, $leader, $response = true));

        } else {
            $this->dispatch(new NewMessageJob($ticket, $user, $leader, $response = false));
        }
    }
}
