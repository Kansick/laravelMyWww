@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Сервисы')

@section('header-page-main', 'Сервисы')
@section('sub-header-page-main', 'Здесь можно посмотреть все сервисы которые можно использовать')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush