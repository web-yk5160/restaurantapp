<?php

namespace App\Exports;
use App\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use function PHPSTORM_META\map;

class SaleReportExport implements FromView
{
    private $dateStart;
    private $dateEnd;
    private $sales;
    private $totalSale;
    public function __construct($dateStart, $dateEnd)
    {
        $dateStart = date("Y-m-d H:i:s", strtotime($dateStart));
        $dateEnd = date("Y-m-d H:i:s", strtotime($dateEnd));
        $sales = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])->where('sale_status', '支払済')->get();
        $totalSale = $sales->sum('total_price');
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->sales = $sales;
        $this->totalSale = $totalSale;
    }

    public function view(): View
    {
        return view('exports.saleReport', [
            'sales' => $this->sales,
            'totalSale' => $this->totalSale,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd
        ]);
    }
}