<?php

namespace App\Http\Controllers\API;

use App\Action;
use App\Attachment;
use App\Filter;
use App\Http\Controllers\Stats;
use App\Jobs\CloseTicketJob;
use App\Jobs\NewTicketJob;
use App\Mail\NewTicketEmail;
use App\Message;
use App\Note;
use App\Source;
use App\State;
use App\Technician;
use App\Ticket;
use App\Type;
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
        $types = Type::all();

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
            'request' => $request->all(),
            'types' => $types,
        ])->header('Access-Control-Allow-Origin', '*')
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
            ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
//            $this->dispatch(new NewTicketJob($ticket, $user, $leader));

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
            $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
//            $this->dispatch(new NewTicketJob($ticket, $user, $leader));
        }

        if ($request->hasFile('files')) {
            $attach = new AttachmentController();
            $attach->store($request, $ticket->id);
        }

        $action = new Action();
        $action->from()->associate(Auth::user());
        $action->ticket()->associate($ticket);
        $action->content = 'à créé un ticket';
        $action->save();

        return response(['ticket' => $ticket, 'user' => $user, 'leader' => $leader]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::with('user', 'society', 'state', 'technician.user', 'source', 'attachments', 'actions.from', 'type')->findOrfail($id);
        $messages = Message::where('commentable_id', $id)->get();
        $actions = Action::where('ticket_id', $id)->get();
        $notes = Note::where('ticket_id', $id)->get();
        foreach ($actions as $action) {
            $action->type = 'action';
        }
        if (Gate::allows('isYourTicket', $ticket)) {
            $techs = Technician::get()->load('user');
            $technicians = [];
            $states = State::all();
            foreach ($techs as $tech) {
                $technicians[] = $tech->user;
            }
            $user = User::all();
            $sources = Source::all();
            $types = Type::all();

            $activity = $messages->merge($notes);
            $all = array_values(array_sort($activity, function ($value) {
                return $value['created_at'];
            }));
            return response()->json(['states' => $states,
                'ticket' => $ticket,
                'technicians' => $technicians,
                'sources' => $sources,
                'messages' => array_reverse($all),
                'actions' => $actions,
                'types' => $types
            ]);
        } else {
            return response()->json(['unauthorized' => '404']);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $state = $ticket->state_id;

        if ($request->technician) {
            $technicien = Technician::where('user_id', $request->technician)->first();
            $ticket->technician()->associate($technicien);
        }
        $action = new Action();
        $this->publishedAction($action, $request, $ticket, Auth::user()->id);
        $ticket->state()->associate($request->state);
        $ticket->importance = $request->importance;
        $ticket->source()->associate($request->source);
        $ticket->type()->associate($request->type);
        $ticket->save();

        //Variable pour déterminé si le ticket est cloturé
        $close = false;
        if ($request->state == '4' && $state != 4) {
            $close = true;
            $user = User::where('id', $ticket->user_id)->first();
            $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
            $ticket->close_at = now();
        }

        return response(['ticket' => $ticket, 'close' => $close]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
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
        foreach ($ticket->actions()->get() as $action) {
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
    public function stats()
    {

        $date = Carbon::now();
        setlocale(LC_TIME, 'fr');

        // Graphique des tickets à l'année
        $ticketThisYear = new Stats();
        $ticketThisYear = $ticketThisYear->countTicket($date->year);


        // Graphique des tickets à l'année N-1
        $lastYear = Carbon::now()->subYears(1)->year;
        $ticketsLastYear = new Stats();
        $ticketsLastYear = $ticketsLastYear->countTicket($lastYear);

        //Tickets en attente
        $ticketsPendingOpen = new Stats();
        $ticketsPendingOpen = $ticketsPendingOpen->pendingOpenTicket();

        //Tickets créer ces 5 derniers jours
        $lastFiveCreated = Ticket::whereDate('created_at', '>=', $date->subDays(7))->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d l');
        });
        $created = [];
        foreach ($lastFiveCreated as $last) {
            $created[] = $last->count();
        }
        //Tickets clos ces 5 derniers jours
        $lastFiveClosed = Ticket::where('state_id', 4)->whereDate('created_at', '>=', $date->subDays(7))->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('D');
        });
        $closed = [];
        foreach ($lastFiveClosed as $last) {
            $closed[] = $last->count();
        }

        //Tickets par sociétés (softease) ou utilisateurs (leader).
        if (Auth::user()->society_id == 1) {
            $count = Society::withCount('tickets as count')->groupBy('name')->orderBy('count', 'DSC')->take(6)->get()->except(1);
            $total = Ticket::where('society_id', '<>', 1)->count();
        } elseif (Auth::user()->role !== "admin") {
            $total = Ticket::where('society_id', Auth::user()->society_id)->count();
            $count = User::where('society_id', Auth::user()->society_id)->withCount('tickets as count')->groupBy('name')->orderBy('count', 'DSC')->get();
        }

        $TicketsByCategory = Type::withCount('tickets as count')->groupBy('name')->get();

        return response()->json([
            'ticketCategory' => $TicketsByCategory,
            'totalTicket' => $total,
            'ticket' => $ticketThisYear,
            'lastFive' => $lastFiveCreated,
            'createdCount' => $created,
            'closedCount' => $closed,
            'pending' => $ticketsPendingOpen,
            'lastYear' => $ticketsLastYear,
            'ticketSociety' => $count]);
    }

    private function transliterateString($txt)
    {
        $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'E', 'ё' => 'e', 'Ё' => 'E', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
        return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
    }

    public function deleteFile(Request $request, $id)
    {
        $attachement = Attachment::find($id);

        $pathSociety = Auth()->user()->society->name;
        $appPath = public_path() . '/app/SfTicket' . '/' . $pathSociety;
        $destinationPath = $appPath . '/' . $request->get('elementID') . '/' . $attachement->name;
        unlink($destinationPath);
        $attachement->delete();
    }

    public function editDescription(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->description = $request->get('description');
        $ticket->save();
//        return $ticket;
    }

    public function sendEmail(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket);
        $user = User::findOrFail($ticket->user_id);
        $leader = User::where('society_id', $user->society_id)->where('role', 'leader')->get();
        if ($request->close) {
            $this->dispatch(new CloseTicketJob($ticket, $user, $leader));
        } else {
            $this->dispatch(new NewTicketJob($ticket, $user, $leader));
        }
    }
}
