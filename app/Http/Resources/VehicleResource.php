<?php

namespace App\Http\Resources;

use App\Enum\ExpensesEnums;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expenses = $this->additional['expenses'] ?? null;
        unset($this->additional['expenses']);
        return [
            'vehicle_id' => $this->id,
            'vehicle_name' => $this->name,
            'plate_number' => $this->plate_number,
            "created_at" => $this->created_at,
            $this->mergeWhen(!empty($expenses), function () use ($expenses) {
                return [
                    'total_expenses' => $expenses->sum('cost'),
                    'total_fuel_expenses' => $expenses->where('type', ExpensesEnums::FuelEntry)->sum('cost'),
                    'total_insurance_expenses' => $expenses->where('type', ExpensesEnums::InsurancePayment)->sum('cost'),
                    'total_services_expenses' => $expenses->where('type', ExpensesEnums::Service)->sum('cost'),
                    'expenses' => $expenses
                ];
            }),
        ];
    }
}
