<?php

namespace App\Http\Controllers\API;

use App\Analytic;
use App\Helpers\AnalyticsHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Analytics;
use Spatie\Analytics\Period;

class AnalyticsController extends Controller
{

    /**
     * @var AnalyticsHelper
     */
    private $helper;

    /**
     * AnalyticsController constructor.
     * @param AnalyticsHelper $helper
     */
    public function __construct(AnalyticsHelper $helper)
    {

        $this->helper = $helper;
    }

    public function getViews(){

        return $views = Analytic::all();
    }

    public function getData(Request $request){


        $MyTinyLamp = $this->helper->getView($request->view);
        $pages = $MyTinyLamp->fetchMostVisitedPages(Period::days(7), 5);
        $refered = $MyTinyLamp->fetchTopReferrers(Period::days(7));
        $visitors = $MyTinyLamp->fetchTotalVisitorsAndPageViews(Period::days(5));
        $query = $MyTinyLamp->performQuery(Period::days(7), 'ga:pageviews,ga:sessionDuration,ga:exits', ['dimensions' => 'ga:source']);
        $ca = $MyTinyLamp->performQuery(Period::days(30), 'ga:transactionRevenue', ['dimensions' => 'ga:date']);
        $ca = collect($ca['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'date' => $pageRow[0],
                    'ca' => $pageRow[1],
                ];
            });
        return response()->json(['pages' => $pages, 'visitors' => $visitors, 'refered' => $refered, 'CA' => $ca]);

    }


}

