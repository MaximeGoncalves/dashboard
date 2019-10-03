<?php

namespace App\Http\Controllers\API;

use App\Society;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocietyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Society[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $society = Society::all();
        $society->load('users');
        return response($society)->header('Access-Control-Allow-Origin', '*')
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
            ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
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
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:societies',
            'email' => 'nullable|email|max:191',
            'cp' => 'nullable|digits:5',
            'phone' => 'nullable|digits:10',
            'fax' => 'nullable|digits:10',
        ]);

        $society = new Society();
        $society->name = $request->name;
        $society->email = $request->email;
        $society->address = $request->address;
        $society->city = $request->city;
        $society->cp = $request->cp;
        $society->phone = $request->phone;
        $society->fax = $request->fax;
        $society->save();
        return response()->json(['success' => 'Done !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $society = Society::findOrFail($id);
        $users = User::with('society')->where('society_id', $society->id)->paginate(5);
        $society->users = $users;
        $tickets = $this->TicketsThisYear($society->id);

        return response(['society' => $society,'tickets' => $tickets]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $society = Society::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191|unique:societies,name,' . $society->id,
            'email' => 'nullable|email|max:191',
            'cp' => 'nullable|digits:5',
            'phone' => 'nullable|digits:10',
            'fax' => 'nullable|digits:10',
        ]);

        $society->name = $request->name;
        $society->email = $request->email;
        $society->address = $request->address;
        $society->city = $request->city;
        $society->cp = $request->cp;
        $society->phone = $request->phone;
        $society->fax = $request->fax;
        $society->save();

        return response(['message' => 'Enregistrement terminé']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        Society::destroy($id);
        return ['message' => 'Société supprimé'];
    }

    public function search(Request $request)
    {
        $search = $request->search;
        return $society = Society::with('users')->where('name', 'like', "%$search%")->get();

    }

    private function TicketsThisYear($id)
    {
        $date = Carbon::now();
        $data = Ticket::where('society_id', $id)->whereYear('created_at', $date)->get()->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('m');
        });
        $tickets = [];
        for ($i = 0; $i < 12; $i++) {
            foreach ($data as $key => $ticket) {
                if (array_key_exists($i, $tickets)) {
                    continue;
                } elseif ($key == $i + 1) {
                    $tickets[] = $ticket->count();
                    unset($data[$key]);
                } else {
                    $tickets[] = 0;
                }
            }
        }

        return $tickets;
    }


}
