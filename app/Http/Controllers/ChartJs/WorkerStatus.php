<?php

namespace App\Http\Controllers\ChartJs;

use App\Models\Worker;
use vitopedro\chartjs\PieChart;
use Illuminate\Support\Facades\DB;

class WorkerStatus
{
    /**
     * @param Type $args
     */
    public function generate()
    {
        $records = Worker::select(['id', 'status_id', DB::raw('count(*) as total')])
            ->groupBy('status_id')
            ->with(['status:id,name'])
            ->get();
        $labels = [];
        $data = [];
        foreach ($records as $record) {
            $labels[] = $record->status->name;
            $data[] = $record->total;
        }
        $pie = new PieChart();
        $pie->setLabels($labels);
        $pie->setSeries([
            [
                'label' => 'Employee Status',
                'data'  => $data,
            ],
        ]);
        return $pie;
    }
}
