<?php

namespace App\Filters;

class InvoiceFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'value' => ['gt', 'lt', 'gte', 'lte', 'ne', 'eq'],
        'type' => ['eq', 'ne', 'in'],
        'paid' => ['eq', 'ne'],
        'payment_date' => ['eq', 'ne', 'gt', 'lt', 'gte', 'lte'],
    ];
}
