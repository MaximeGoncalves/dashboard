<?php

namespace App\Http\Controllers\API;

use App\Attachment;
use App\Source;
use App\State;
use App\Technician;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Ticket[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $ticket = Ticket::with(['user', 'source', 'society', 'technician.user', 'state'])->orderBy('created_at', 'DESC')->latest()->paginate(10);
        $techs = Technician::get()->load('user');
        $technicians = [];
        $states = State::all();
        foreach ($techs as $tech) {
            $technicians[] = $tech->user;
        }
        $user = User::all();
        $sources = Source::all();

        return response(json_encode(['user' => $user, 'states' => $states, 'ticket' => $ticket, 'technician' => $technicians, 'sources' => $sources]));
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
        } else {
            $this->validate($request, [
                'topic' => 'required',
                'description' => 'required',
                'importance' => 'required',
            ]);
            //utilisateur user ou leader
            $ticket = new Ticket();
            $ticket->topic = $request->topic;
            $ticket->description = $request->description;
            $ticket->importance = $request->importance;
            $ticket->user()->associate(Auth::user()->id);
            $ticket->society()->associate(Auth::user()->society->id);
            $ticket->save();
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
            if ($request->hasFile('files')):
                foreach ($files as $file):
                    $file->move($destinationPath, '/' . $file->getClientOriginalName());
                    $pj = new Attachment();
                    $pj->name = $file->getClientOriginalName();
                    $pj->link = '/app/SfTicket/' . $pathSociety . '/' . $ticket->id . '/' . $file->getClientOriginalName();
                    $pj->ticket()->associate($ticket);
                    $pj->save();
                endforeach;
            endif;
        endif;

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

        $ticket = Ticket::findOrfail($id);
        return $ticket->load('user','society','state', 'technician.user', 'source', 'attachments', 'actions.from', 'messages.from');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeAction(Request $request, $id){
        $ticket = Ticket::findOrFail($id);
        $action = new Action();
        $action->content = $request->get('content');
        $action->from_id = Auth::user()->id;
        $action->ticket()->associate($ticket);
        $action->save();
        return response()->json(['message' => 'success']);
    }
}
