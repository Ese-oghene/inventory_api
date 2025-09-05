<?php

namespace App\Services\Report;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Report\ReportRepository;

class ReportServiceImplement extends ServiceApi implements ReportService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
        protected ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
      $this->reportRepository = $reportRepository;
    }

    // Define your custom methods :)

     public function generateReport(string $startDate, string $endDate) {
        $summary = $this->reportRepository->salesSummary($startDate, $endDate);

        $totalRevenue = $summary->sum('revenue');
        $totalItemsSold = $summary->sum('sold_quantity');

        return $this->setCode(200)
                    ->setMessage("CEO Report from $startDate to $endDate")
                    ->setData([
                        'total_revenue' => $totalRevenue,
                        'total_items_sold' => $totalItemsSold,
                        'products' => $summary
                    ]);
    }
}
