<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;

class InvoicesFilter extends ApiFilter {
    protected $queryParams = [
        'customerId' => ['eq'],
        'amount' => ['eq','gt','lt','lte','gte'],
        'status' => ['eq', 'ne'],
        'billedDate' => ['eq','gt','lt','lte','gte'],
        'paidDate' => ['eq','gt','lt','lte','gte'],
    ];

    protected $columnMap = [
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date',
        'customerId' => 'customer_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
    ];

}