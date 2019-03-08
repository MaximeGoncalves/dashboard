<?php
namespace App\Helpers;

use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;

class AnalyticsHelper {

    public function getView($viewId)
    {
        config(['laravel-analytics' => $viewId]);
        $config = config('analytics');
        $client = AnalyticsClientFactory::createForConfig($config);
        return new Analytics($client, $viewId);
    }
}
