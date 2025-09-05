<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Report\ReportService;


class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService) {
        $this->reportService = $reportService;
    }


    public function ceoReport(Request $request) {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        return $this->reportService->generateReport($startDate, $endDate)->toJson();
    }
}
