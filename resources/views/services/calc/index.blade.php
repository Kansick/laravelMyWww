@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Аренда')

@section('header-page-main', 'Рассчет аренды')
@section('sub-header-page-main', 'Здесь можно посмотреть все отчеты по квартплате а так же посчитать новую')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush