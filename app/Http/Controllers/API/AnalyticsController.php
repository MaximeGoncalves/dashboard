<?php

namespace App\Http\Controllers\API;

use App\Analytic;
use App\Helpers\AnalyticsHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Analytics;
use phpDocumentor\Reflection\Types\Integer;
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

    public function getViews()
    {

        return $views = Analytic::all();
    }

    public function getData(Request $request)
    {
        $view = $this->helper->getView($request->view);
        $now = Carbon::now();
        $period = 0;
        if($request->get('period') == 30){
            $period =  Period::days(29);
        }elseif($request->get('period') == 7){
            $period =  Period::days(6);
        }elseif($request->get('period') == 1){
            $period =  Period::days(0);
        }elseif($request->get('period') == -1){
            $period = Period::create(Carbon::yesterday()->startOfDay() , Carbon::yesterday()->endOfDay());
        }
        $pages = $this->fetchPagesViews($period, $view, 5);
        $refereds = $this->fetchSocialTraffic($period, $view);
        $visitors = $view->fetchTotalVisitorsAndPageViews($period);
        $ca = $this->fetchTotalCA($period , $view);
        $userVSReturning = $this->fetchUserVsReturning($period, $view);
        $bounceRate = $this->fecthBounceRate($period, $view);
        $sessionDuration = $this->fetchSessionDuration($period, $view);

        return response()->json(['pages' => $pages,
            'visitors' => $visitors,
            'refered' => $refereds,
            'CA' => $ca,
            'userVsReturning' => $userVSReturning,
            'bounceRate' => $bounceRate,
            'sessionDuration' => $sessionDuration]);
    }

    public function store(Request $request)
    {
        $view = new Analytic();
        $view->name = $request->name;
        $view->view = $request->view;
        $view->save();
        return $view;
    }

    protected function fetchUserVsReturning($period, $view)
    {
        $uVSr = $view->performQuery($period, 'ga:sessions', ['dimensions' => 'ga:userType']);
        if(isset($uVSr['rows'][1])){
            return $array = [
                'NewUser' => $uVSr['rows'][0][1],
                'ReturningUser' => $uVSr['rows'][1][1]
            ];
        }else{
            return $array = [
                'NewUser' => $uVSr['rows'][0][1],
                'ReturningUser' => 0
            ];
        }
    }
    protected function fecthBounceRate($period, $view)
    {
        $bounce = $view->performQuery($period, 'ga:bounceRate');
        return $bounce['rows'][0][0];
    }
    protected function fetchSessionDuration($period, $view)
    {
        $uVSr = $view->performQuery($period, 'ga:avgSessionDuration');
        return $uVSr['rows'][0][0];
    }
    protected function fetchTotalCA($period, $view){
        $ca = $view->performQuery($period, 'ga:transactionRevenue', ['dimensions' => 'ga:date']);
        return collect($ca['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'date' => $pageRow[0],
                    'ca' => $pageRow[1],
                ];
            });
    }
    protected function fetchSocialTraffic($period, $view){
        $refered = $view->performQuery($period, 'ga:pageviews', ['dimensions' => 'ga:fullReferrer', 'sort' => '-ga:pageviews', 'max-results' => 5,]);
        return collect($refered['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'name' => $pageRow[0],
                    'count' => $pageRow[1],
                ];
            });
    }
    protected function fetchPagesViews($period, $view, $maxResults){
        $response = $view->performQuery(
            $period,
            'ga:pageviews,ga:avgTimeOnPage',
            [
                'dimensions' => 'ga:pagePath,ga:pageTitle',
                'sort' => '-ga:pageviews',
                'max-results' => $maxResults,
            ]
        );
        return collect($response['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'url' => $pageRow[0],
                    'pageTitle' => $pageRow[1],
                    'pageViews' => (int) $pageRow[2],
                    'AverageTime' => (int) $pageRow[3],
                ];
            });
    }


}

