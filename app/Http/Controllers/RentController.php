<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentList;

class RentController extends Controller
{
    public function index()
    {
        // Получаем все записи, сортируем по дате (свежие сверху)
        $records = RentList::orderBy('date_create', 'desc')->limit(15)->get();

        return view('services.rent.index', compact('records'));
    }

    //Получаем запись по ID
    public function getRecord($id)
    {
        $record = RentList::find($id);

        if (!$record) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        }

        return response()->json([
            'title' => $record->title,
            'date_create' => $record->date_create->locale('ru')->translatedFormat('j F Y'),
            'result_sum' => $record->result_sum,
            'attributes' => $record->attributes,
            'propertys' => $record->propertys,
            'propertys_values' => $record->propertys_values,
        ]);
    }
}
