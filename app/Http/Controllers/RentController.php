<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentList;

class RentController extends Controller
{
    public function index()
    {
        // Получаем все записи, сортируем по дате (свежие сверху)
        $records = RentList::orderBy('date_create', 'desc')->get();

        return view('services.rent.index', compact('records'));
    }
}
