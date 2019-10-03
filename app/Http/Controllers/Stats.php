<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stats
{
    public function countTicket($date)
    {

        $dataNow = null;
        if (Auth::user()->society_id == 1) {
            $dataNow = Ticket::whereYear('created_at', $date)->get()->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('m');
            });
        } elseif (Auth::user()->role == 'leader') {
            $dataNow = Ticket::where('society_id', Auth::user()->society_id)->whereYear('created_at', $date)->get()->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('m');
            });
        } elseif (Auth::user()->role == 'user') {
            $dataNow = Ticket::where('user_id', Auth::user()->id)->where('society_id', Auth::user()->society_id)->whereYear('created_at', $date)->get()->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('m');
            });
        }

        $temp = $dataNow;
        $tickets = [];

        for ($i = 0; $i < 12; $i++) {
            foreach ($temp as $key => $ticket) {
                if (array_key_exists($i, $tickets)) {
                    continue;
                } elseif ($key == $i + 1) {
                    $tickets[] = $ticket->count();
                    unset($temp[$key]);
                } else {
                    $tickets[] = 0;
                }
            }
        }

        return $tickets;

    }


    public function pendingOpenTicket()
    {
        if (Auth::user()->society_id == 1) {
            $ticketsPending = Ticket::with('user')->where('state_id', 1)->simplePaginate(5);
        } elseif (Auth::user()->role == "leader") {
            $ticketsPending = Ticket::with('state')->where('society_id', Auth::user()->society_id)->where('state_id', ['1', '2'])->simplePaginate(6);
        } elseif (Auth::user()->role == 'user') {
            $ticketsPending = Ticket::with('state')->where('society_id', Auth::user()->society_id)->where('user_id', Auth::user()->id)->where('state_id', ['1', '2'])->simplePaginate(6);
        }
        return $ticketsPending;
    }
}
