<?php

namespace App\Repositories\Report;

use LaravelEasyRepository\Repository;

interface ReportRepository extends Repository{

    // Write something awesome :)
      public function salesSummary(string $startDate, string $endDate);
}
