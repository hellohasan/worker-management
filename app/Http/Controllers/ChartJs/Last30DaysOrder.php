<?php

namespace App\Http\Controllers\ChartJs;

use Carbon\Carbon;
use App\Models\Order;
use Carbon\CarbonPeriod;
use vitopedro\chartjs\LineChart;

class Last30DaysOrder
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $query = Order::query();
        $query->where('created_at', '>=', now()->subDays(30)->format('Y-m-d'));
        $collection = $query->get();

        $chartQuery = $collection->sortBy('created_at')
            ->groupBy(function ($entry) {
                return $entry->created_at->format('Y-m-d');
            })
            ->map(function ($entries) {
                return $entries->count();
            });

        $newData = collect([]);

        CarbonPeriod::since(now()->subDays(30))
            ->until(now())
            ->forEach(function (Carbon $date) use ($chartQuery, &$newData) {
                $key = $date->format('Y-m-d');
                $newData->put($key, $chartQuery[$key] ?? 0);
            });

        $line = new LineChart();
        $line->setLabels($newData->keys());
        $line->setSeries([
            [
                'label' => 'Orders',
                'data'  => $newData->values()->toArray(),
            ],
        ]);

        return $line;
    }
}
