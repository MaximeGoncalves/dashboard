<?php

namespace App;


use Illuminate\Support\Facades\Auth;

class Filter
{
    public function query($request)
    {
        if (Auth::user()->role == 'admin') {
            $tickets = Ticket::where(function ($q) use ($request) {
                if ($request->technician != null) {
                    $technician = Technician::whereIn('user_id', $request->technician)->get();
                    $technicians = [];
                    foreach ($technician as $tech) {
                        $technicians[] = $tech->id;
                    }
                    $q->whereIn('technician_id', $technicians);
                }
            })
                ->where(function ($q) use ($request) {
                    if ($request->state != null) {
                        $q->whereIn('state_id', $request->state);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->importance != null) {
                        $q->where('importance', $request->importance);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->source != null) {
                        $q->where('source_id', $request->source);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->user != null) {
                        $q->where('user_id', $request->user);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->society != null) {
                        $q->where('society_id', $request->society);
                    }
                })
                ->with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])
                ->orderBy('created_at', 'desc')
                ->latest()
                ->paginate(10);
            return $tickets;
        } elseif (Auth::user()->role == 'leader') {
            $tickets = Ticket::where(function ($q) use ($request) {
                if ($request->technician != null) {
                    $technician = Technician::whereIn('user_id', $request->technician)->get();
                    $technicians = [];
                    foreach ($technician as $tech) {
                        $technicians[] = $tech->id;
                    }
                    $q->whereIn('technician_id', $technicians);
                }
            })
                ->where(function ($q) use ($request) {
                    if ($request->state != null) {
                        $q->whereIn('state_id', $request->state);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->importance != null) {
                        $q->where('importance', $request->importance);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->source != null) {
                        $q->where('source_id', $request->source);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->user != null) {
                        $q->where('user_id', $request->user);
                    }
                })
                ->where('society_id', Auth::user()->society->id)
                ->with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])
                ->orderBy('created_at', 'desc')
                ->latest()
                ->paginate(10);
            return $tickets;
        } else {
            $tickets = Ticket::where(function ($q) use ($request) {
                if ($request->technician != null) {
                    $technician = Technician::whereIn('user_id', $request->technician)->get();
                    $technicians = [];
                    foreach ($technician as $tech) {
                        $technicians[] = $tech->id;
                    }
                    $q->whereIn('technician_id', $technicians);
                }
            })
                ->where(function ($q) use ($request) {
                    if ($request->state != null) {
                        $q->whereIn('state_id', $request->state);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->importance != null) {
                        $q->where('importance', $request->importance);
                    }
                })->where(function ($q) use ($request) {
                    if ($request->source != null) {
                        $q->where('source_id', $request->source);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->user != null) {
                        $q->where('user_id', $request->user);
                    }
                })
                ->where('user_id', Auth::user()->id)
                ->with(['user', 'source', 'society', 'technician.user', 'state', 'actions', 'messages'])
                ->orderBy('created_at', 'desc')
                ->latest()
                ->paginate(10);
            return $tickets;
        }
    }
}
