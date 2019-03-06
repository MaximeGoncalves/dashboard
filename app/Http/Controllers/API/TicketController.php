<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Attachment;
use App\Filter;
use App\Jobs\CloseTicketJob;
use App\Jobs\NewTicketJob;
use App\Mail\NewTicketEmail;
use App\Message;
use App\Source;
use App\State;
use App\Technician;
use App\Ticket;
use App\User;
use App\Society;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Ticket[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $techs = Technician::get()->load('user');
        $technicians = [];
        $states = State::all();
        foreach ($techs as $tech) {
            $technicians[] = $tech->user;
        }
        $user = User::all();
        $sources = Source::all();
        $society = Society::with('users')->get();


        $filter = new Filter();
        $tickets = $filter->query($request);


        return response()->json([
            'user' => $user,
            'states' => $states,
            'ticket' => $tickets,
            'technician' => $technicians,
            'sources' => $sources,
            'count' => $tickets->count(),
            'society' => $society,
            'request' => $request->all()
        ]);


//        if (Auth::user()->role == 'leader') {
//            $ticket = Ticket::where('society_id', Auth::user()->society->id)->with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])->orderBy('created_at', 'DESC')->latest()->paginate(10);
//        } else if (Auth::user()->role == 'user') {
//            $ticket = Ticket::where('user_id', Auth::user()->id)->with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])->orderBy('created_at', 'DESC')->latest()->paginate(10);
//        } else {
//            $ticket = Ticket::with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])->orderBy('created_at', 'DESC')->latest()->paginate(10);
//        }


//        return response(json_encode(['user' => $user, 'states' => $states, 'ticket' => $ticket, 'technician' => $technicians, 'sources' => $sources, 'society' => $society]));
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

        if (Auth::user()->role == 'admin') {
            $this->validate($request, [
                'topic' => 'required',
                'description' => 'required',
                'importance' => 'required',
                'user' => 'required',
                'technician' => 'required',
                'source' => 'required',
            ]);
            //utilisateur admin
            $user = User::where('id', $request->user)->first();
            $technicien = Technician::where('user_id', $request->technician)->first();
            $ticket = new Ticket();
            $ticket->topic = $request->topic;
            $ticket->description = $request->description;
            $ticket->importance = $request->importance;
            $ticket->user()->associate($user);
            $ticket->source()->associate($request->source);
            $ticket->technician()->associate($technicien);
            $ticket->society()->associate($user->society->id);
            $ticket->save();
            $this->dispatch(new NewTicketJob($ticket, $user));

        } else {
            $this->validate($request, [
                'topic' => 'required',
                'description' => 'required',
                'importance' => 'required',
            ]);
            //utilisateur user ou leader
            $ticket = new Ticket();
            $user = User::where('id', Auth::user()->id)->first();
            $ticket->topic = $request->topic;
            $ticket->description = $request->description;
            $ticket->importance = $request->importance;
            $ticket->user()->associate($user->id);
            $ticket->society()->associate(Auth::user()->society->id);
            $ticket->save();
            $this->dispatch(new NewTicketJob($ticket, $user));

        }

        if ($request->hasFile('files')):
            $pathSociety = $ticket->user->society->name;
            $appPath = public_path() . '/app/SfTicket' . '/' . $pathSociety;
            if (!file_exists($appPath)):
                mkdir($appPath, 0777, true);
            endif;

            mkdir($appPath . '/' . $ticket->id, 0777, true);
            $destinationPath = $appPath . '/' . $ticket->id;

            $files = $request->file('files');
            foreach ($files as $file):
                $file->move($destinationPath, '/' . $file->getClientOriginalName());
                $pj = new Attachment();
                $pj->name = $file->getClientOriginalName();
                $pj->url = '/app/SfTicket/' . $pathSociety . '/' . $ticket->id . '/' . $file->getClientOriginalName();
                $pj->attachable_type = Ticket::class;
                $pj->attachable_id = $ticket->id;
                $pj->name = $file->getClientOriginalName();
                $pj->save();
            endforeach;
        endif;

        $action = new Action();
        $action->from()->associate(Auth::user());
        $action->ticket()->associate($ticket);
        $action->content = 'à ouvert un ticket';
        $action->save();

        return response(['message' => "success"]);


    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::with('user', 'society', 'state', 'technician.user', 'source', 'attachments', 'actions.from', 'messages.from')->findOrfail($id);
        $messages = Message::where('ticket_id', $id)->whereNull('to_id')->get();
        $actions = Action::where('ticket_id', $id)->get();
        if (Gate::allows('isYourTicket', $ticket)) {
            $techs = Technician::get()->load('user');
            $technicians = [];
            $states = State::all();
            foreach ($techs as $tech) {
                $technicians[] = $tech->user;
            }
            $user = User::all();
            $sources = Source::all();

            return response()->json(['states' => $states,
                'ticket' => $ticket,
                'technicians' => $technicians,
                'sources' => $sources,
                'messages' => $messages,
                'actions' => $actions,
            ]);
        } else {
            return response()->json(['unauthorized' => '404']);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->technician) {
            $technicien = Technician::where('user_id', $request->technician)->first();
            $ticket->technician()->associate($technicien);
        }
        $action = new Action();
        $this->publishedAction($action, $request, $ticket, Auth::user()->id);
        $ticket->state()->associate($request->state);
        $ticket->importance = $request->importance;
        $ticket->source()->associate($request->source);
        $ticket->save();

        if ($request->state == '4') {
            $user = User::where('id', $ticket->user_id)->first();
            $this->dispatch(new CloseTicketJob($ticket, $user));
        }
        return response('ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        Ticket::destroy($id);
        Storage::deleteDirectory('/SfTicket/' . $ticket->society->name . '/' . $ticket->id);
        foreach ($ticket->attachments()->get() as $attach) {
            File::delete($attach->url);
        }
        foreach ($ticket->actions()->get() as $action){
            $action->delete();
        }
        $ticket->attachments()->delete();
        return response()->json(['response' => 'Ticket supprimé']);
    }

    public function storeAction(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $action = new Action();
        $action->content = $request->get('content');
        $action->from_id = Auth::user()->id;
        $action->ticket()->associate($ticket);
        $action->save();
        return response()->json(['message' => 'success']);
    }

    private function publishedAction($action, $request, $ticket, $id)
    {
        if ($request->state == 4 && $ticket->state_id != 4) {
            $action->content = 'à cloturé le ticket';
            $action->from_id = $id;
            $action->ticket()->associate($ticket);
            $action->save();
        } elseif ($request->state == 3 && $ticket->state_id != 3) {
            $action->content = 'à passé le ticket en attente client';
            $action->from_id = $id;
            $action->ticket()->associate($ticket);
            $action->save();
        } elseif ($request->state == 2 && $ticket->state_id != 2) {
            $action->content = "à ouvert le ticket";
            $action->from_id = $id;
            $action->ticket()->associate($ticket);
            $action->save();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function stats(){
        $date = Carbon::now();
        $data = Ticket::whereYear('created_at', $date->year)->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('m');
        });

        $tickets = [];
        foreach($data as $ticket){
            $tickets[] = $ticket->count();
        }
        return response()->json($tickets);
    }
}
