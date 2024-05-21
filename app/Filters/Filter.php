<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Exception;
use Illuminate\Http\Request;

abstract class Filter
{
    protected array $allowedOperatorsFields = [];

    protected array $translateOperatorsFields = [
        'gt' => '>',
        'lt' => '<',
        'gte' => '>=',
        'lte' => '<=',
        'ne' => '!=',
        'eq' => '=',
        'in' => 'in',
    ];

    public function filter(Request $request)
    {
        $where = [];
        $whereIn = [];

        if (empty($this->allowedOperatorsFields)) {
            throw new PropertyException("Property allowedOperatorsFields is empty");
        }

        foreach ($this->allowedOperatorsFields as $field => $operators) {
            $queryOperator = $request->query($field);
            if ($queryOperator) {
                foreach ($queryOperator as $key => $value) {
                    if (!in_array($key, $operators)) {
                        throw new Exception("{$field} does not have {$key} operator");
                    }

                    if (str_contains($value,"[")) {
                        $whereIn[] = [
                            $field,
                            explode(",", str_replace(['[',']'], '', $value)),
                            $value
                        ];
                    } else {
                        $where[] = [
                            $field,
                            $this->translateOperatorsFields[$key],
                            $value
                        ];
                    }
                }
            }

        }

        if (empty($where) && empty($whereIn)) {
            return [];
        }

        return [
            'where' => $where,
            'whereIn' => $whereIn
        ];
    }
}
