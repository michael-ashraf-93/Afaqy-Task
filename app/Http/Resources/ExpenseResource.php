<?php

namespace App\Http\Resources;

use App\Services\Factories\ExpenseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expensesData = $this->resource;
        $expenses = collect();
        foreach ($expensesData as $expense) {
            $expenses->push(ExpenseFactory::create(type: $expense->type, entry: $expense)->resolve());
        }
        return [
            'data' => $expenses,
            'meta' => [
                'total' => $expensesData->total(),
                'perPage' => $expensesData->perPage(),
                'currentPage' => $expensesData->currentPage(),
                'totalPages' => ceil($expensesData->total() / $expensesData->perPage()),
            ],
        ];
    }
}
