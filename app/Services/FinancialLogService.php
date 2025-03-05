<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class FinancialLogService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}


    public function log($car, $oldRecords, $newRecords)
    {
        // Identify deleted records (records in old but not in new)
        $deletedRecords = $oldRecords->reject(function ($oldItem) use ($newRecords) {
            return $newRecords->contains(fn($newItem) => $newItem['id'] === $oldItem['id']);
        });

        // Identify new records (records in new but not in old)
        $newAddedRecords = $newRecords->reject(function ($newItem) use ($oldRecords) {
            return $oldRecords->contains(fn($oldItem) => $oldItem['id'] === $newItem['id']);
        });

        // Identify changed records (same id, but different value)
        $changedRecords = $newRecords->filter(function ($newItem) use ($oldRecords) {
            $oldItem = $oldRecords->first(fn($oldItem) => $oldItem['id'] === $newItem['id']);
            return $oldItem && $oldItem['value'] != $newItem['value'];
        });

        // Log Deleted Records
        foreach ($deletedRecords as $record) {
            Log::channel('car_changes')->info(
                "VIN {$car->vin} წაიშალა ხარჯი {$record['name']}",
                [
                    "  თანხა: {$record['value']}, ოპერაცია განახორციელა: " . auth()->user()->name,
                ]
            );
        }

        // Log New Added Records
        foreach ($newAddedRecords as $record) {
            Log::channel('car_changes')->info(
                "VIN {$car->vin} დაემატა ხარჯი {$record['name']}",
                [
                    "  თანხა: {$record['value']}, ოპერაცია განახორციელა: " . auth()->user()->name,
                ]
            );
        }

        // Log Changed Records
        foreach ($changedRecords as $newItem) {
            $oldItem = $oldRecords->first(fn($oldItem) => $oldItem['id'] === $newItem['id']);
            Log::channel('car_changes')->info(
                "VIN {$car->vin} {$newItem['name']} თანხის ცვლილება",
                [
                    "ძველი თანხა: {$oldItem['value']}, ახალი თანხა: {$newItem['value']}, ოპერაცია განახორციელა: " . auth()->user()->name
                ]
            );
        }
    }





}
