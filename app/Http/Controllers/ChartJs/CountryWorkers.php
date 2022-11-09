<?php

namespace App\Http\Controllers\ChartJs;

use App\Models\Worker;
use vitopedro\chartjs\PieChart;
use Illuminate\Support\Facades\DB;

class CountryWorkers
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $records = Worker::groupBy('country')
            ->select('country', DB::raw('count(*) as total'))
            ->get();

        $labels = [];
        $data = [];
        foreach ($records as $record) {
            $labels[] = $record->country;
            $data[] = $record->total;
        }
        $pie = new PieChart();
        $pie->setLabels($labels);
        $pie->setSeries([
            [
                'label' => 'Country Wise Employee',
                'data'  => $data,
            ],
        ]);
        return $pie;
    }
}
