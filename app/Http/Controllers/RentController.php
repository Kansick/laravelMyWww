<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentReport;
use Illuminate\Support\Facades\DB;

class RentController extends Controller
{
    public function index()
    {
        // Получаем все записи, сортируем по дате (свежие сверху)
        $records = RentReport::query()
            ->orderByDesc('period_date')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('services.rent.index', compact('records'));
    }

    //Получаем запись по ID
    public function getRecord($id)
    {
        $record = RentReport::with(['fixedCharges', 'meterReadings'])->find($id);

        if (!$record) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }

        return response()->json($this->formatReport($record));
    }

    //Получаем запись последнюю
    public function getLast()
    {
        $record = RentReport::with(['fixedCharges', 'meterReadings'])
            ->orderByDesc('period_date')
            ->orderByDesc('created_at')
            ->first();

        if (!$record) {
            return response()->json([]);
        }

        return response()->json($this->formatReport($record));
    }

    public function createRecord(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'period_date' => ['nullable', 'date'],

            'fixed_charges' => ['nullable', 'array'],
            'fixed_charges.*.title' => ['required_with:fixed_charges', 'string', 'max:255'],
            'fixed_charges.*.amount' => ['required_with:fixed_charges', 'numeric', 'min:0'],

            'meter_readings' => ['nullable', 'array'],
            'meter_readings.*.meter_name' => ['required_with:meter_readings', 'string', 'max:255'],
            'meter_readings.*.tariff' => ['required_with:meter_readings', 'numeric', 'min:0'],
            'meter_readings.*.start_value' => ['required_with:meter_readings', 'numeric', 'min:0'],
            'meter_readings.*.end_value' => ['required_with:meter_readings', 'numeric', 'min:0'],
        ]);

        $report = DB::transaction(function () use ($data) {
            $report = RentReport::create([
                'title' => $data['title'],
                'period_date' => $data['period_date'] ?? now()->toDateString(),
            ]);

            $fixedChargesSum = 0;

            foreach (($data['fixed_charges'] ?? []) as $index => $charge) {
                $amount = (float) $charge['amount'];

                $fixedChargesSum += $amount;

                $report->fixedCharges()->create([
                    'title' => $charge['title'],
                    'amount' => $amount,
                    'sort_order' => $index,
                ]);
            }

            $metersSum = 0;

            foreach (($data['meter_readings'] ?? []) as $index => $reading) {
                $tariff = (float) $reading['tariff'];
                $startValue = (float) $reading['start_value'];
                $endValue = (float) $reading['end_value'];

                $consumption = max(0, $endValue - $startValue);
                $amount = $consumption * $tariff;

                $metersSum += $amount;

                $report->meterReadings()->create([
                    'meter_name' => $reading['meter_name'],
                    'tariff' => $tariff,
                    'start_value' => $startValue,
                    'end_value' => $endValue,
                    'consumption' => $consumption,
                    'amount' => $amount,
                    'sort_order' => $index,
                ]);
            }

            $report->update([
                'fixed_charges_sum' => $fixedChargesSum,
                'meters_sum' => $metersSum,
                'result_sum' => $fixedChargesSum + $metersSum,
            ]);

            return $report->load(['fixedCharges', 'meterReadings']);
        });

        return response()->json($this->formatReport($report), 201);
    }


    private function formatReport(RentReport $record): array
    {
        return [
            'id' => $record->id,
            'title' => $record->title,
            'period_date' => $record->period_date?->toDateString(),
            'date_formatted' => $record->period_date?->locale('ru')->translatedFormat('j F Y'),
            'fixed_charges_sum' => $record->fixed_charges_sum,
            'meters_sum' => $record->meters_sum,
            'result_sum' => $record->result_sum,
            'fixed_charges' => $record->fixedCharges,
            'meter_readings' => $record->meterReadings,
        ];
    }
}
