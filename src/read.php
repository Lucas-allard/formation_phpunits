<?php declare( strict_types=1 );

use App\Exception\DatabaseConnectionException;
use App\Helpers\DbQueryBuilderFactory;
use App\Repository\BugReportRepository;

try {
    $queryBuilder = DbQueryBuilderFactory::make();
} catch (Throwable $e) {
    throw new DatabaseConnectionException();
}


$repository = new BugReportRepository($queryBuilder);

$bugReports = $repository->findAll();
