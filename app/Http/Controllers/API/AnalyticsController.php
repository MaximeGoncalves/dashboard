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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $view = $this->helper->getView($request->view);
        $now = Carbon::now();
        $period = 0;
        $dimensions = '';
        $lastPeriod = Period::create(Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->subDays(7)->endOfDay());
        if ($request->get('period') == 30) {
            $period = Period::days(31);
            $dimensions = 'ga:day';
            $lastPeriod = Period::create(Carbon::now()->subDays(60)->startOfDay(), Carbon::now()->subDays(30)->endOfDay());
        } elseif ($request->get('period') == 7) {
            $period = Period::days(6);
            $dimensions = 'ga:day';
            $lastPeriod = Period::create(Carbon::now()->subDays(14)->startOfDay(), Carbon::now()->subDays(7)->endOfDay());
        } elseif ($request->get('period') == 1) {
            $lastPeriod = Period::create(Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay());
            $period = Period::days(0);
            $dimensions = 'ga:hour';
        } elseif ($request->get('period') == -1) {
            $lastPeriod = Period::create(Carbon::yesterday()->subDays(1)->startOfDay(), Carbon::yesterday()->subDays(1)->endOfDay());
            $period = Period::create(Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay());
            $dimensions = 'ga:hour';
        }

        $pages = $this->fetchPagesViews($period, $view, 5);
        $refereds = $this->fetchSocialTraffic($period, $view);
        $visitors = $this->fetchTotalVisitors($period, $view, $dimensions, $lastPeriod);
        $ca = $this->fetchTotalCA($period, $view, $dimensions, $lastPeriod);

        // Visitor and returnin visitor
        $userVSReturning = $this->fetchUserVsReturning($period, $view, $lastPeriod); // Today

        $bounceRate = $this->fetchBounceRate($period, $view, $lastPeriod);
        $sessionDuration = $this->fetchSessionDuration($period, $view, $lastPeriod);

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

    protected function fetchUserVsReturning($period, $view, $lastPeriod)
    {
        $uVSr = $view->performQuery($period, 'ga:users', ['dimensions' => 'ga:userType']);
        $last = $view->performQuery($lastPeriod, 'ga:users', ['dimensions' => 'ga:userType']);

        $ReturningUser = 0;
        $lastReturningUser = 0;
        if(isset($uVSr['rows'][1])){
            $ReturningUser =  $uVSr['rows'][1][1];
        }
        if(isset($last['rows'][1])){
            $lastReturningUser =  $last['rows'][1][1];
        }
        $percentNew = $this->getPercent($uVSr['rows'][0][1], $last['rows'][0][1]);
        $percentReturning = $this->getPercent($ReturningUser, $lastReturningUser);

        return $array = [
            'NewUser' => $uVSr['rows'][0][1],
            'ReturningUser' =>  $ReturningUser,
            'lastReturningUser' =>  $lastReturningUser,
            'lastuser' => $last['rows'][0][1],
            'PercentNew' => $percentNew,
            'PercentReturning' => $percentReturning,
        ];
    }

    protected function fetchBounceRate($period, $view, $lastPeriod)
    {
        $bounce = $view->performQuery($period, 'ga:bounceRate');
        $lastBounce = $view->performQuery($lastPeriod, 'ga:bounceRate');
        $percent = $this->getPercent($bounce['rows'][0][0],$lastBounce['rows'][0][0]);
        return $array = [
            'bounce' => $bounce['rows'][0][0],
            'Percent' =>  $percent,
        ];
    }

    protected function fetchSessionDuration($period, $view, $lastPeriod)
    {
        $duration = $view->performQuery($period, 'ga:avgSessionDuration');
        $last = $view->performQuery($lastPeriod, 'ga:avgSessionDuration');
        $percent = $this->getPercent($duration['rows'][0][0],$last['rows'][0][0]);
        return $array = [
            'duration' => $last['rows'][0][0],
            'Percent' =>  $percent,
        ];
    }

    protected function fetchTotalCA($period, $view, $dimensions, $lastPeriod)
    {

        $ca = $view->performQuery($period, 'ga:transactionRevenue', ['dimensions' => $dimensions]);
        $lastCa = $view->performQuery($lastPeriod, 'ga:transactionRevenue', ['dimensions' => $dimensions]);
        return collect($ca['rows'] ?? [])
            ->map(function (array $pageRow) use($lastCa) {
                return [
                    'date' => $pageRow[0],
                    'ca' => $pageRow[1],
                    'lastCA' => $lastCa['rows'][1][1],
                ];
            });
    }

    protected function fetchSocialTraffic($period, $view)
    {
        $refered = $view->performQuery($period, 'ga:pageviews', ['dimensions' => 'ga:fullReferrer', 'sort' => '-ga:pageviews', 'max-results' => 5,]);
        return collect($refered['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'name' => $pageRow[0],
                    'count' => $pageRow[1],
                ];
            });
    }

    protected function fetchPagesViews($period, $view, $maxResults)
    {
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
                    'pageViews' => (int)$pageRow[2],
                    'AverageTime' => (int)$pageRow[3],
                ];
            });
    }

    protected function fetchTotalVisitors($period, $view, $dimensions, $lastPeriod)
    {
        $response = $view->performQuery(
            $period,
            'ga:users,ga:pageviews',
            ['dimensions' => $dimensions]
        );

        return collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'date' => $dateRow[0],
                'visitors' => (int)$dateRow[1],
                'pageViews' => (int)$dateRow[2],
            ];
        });

    }

    protected function getPercent($yesterday, $today)
    {
        if($yesterday === 0){
            $yesterday = 1;
        }

        if($today === 0){
            $today = 1;
        }
        // (b-a) / a * 100
        return $result = (($yesterday - $today ) / $today) * 100;
    }

}

