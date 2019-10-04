<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\NewNoteJob;
use App\Mail\NewNoteEmail;
use App\Note;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NoteController extends Controller
{

    public function store(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $note = New Note();
        $note->content = $request->get('content');
        $note->private = $request->get('private');
        $note->from()->associate(Auth::user());
        $note->ticket()->associate($ticket);
        $note->save();
        return response($note);
    }

    public function update(Request $request, $id){
        $note = Note::findOrFail($id);
        $note->content = $request->get('content');
        $note->save();
    }

    public function sendEmail(Request $request) {
        $note = Note::findOrFail($request->note);
        $this->dispatch(new NewNoteJob($note, Auth::user()));
    }
}
