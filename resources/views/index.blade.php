@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Главная')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush