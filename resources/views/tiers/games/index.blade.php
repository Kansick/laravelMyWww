@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Тир лист игр')

@section('header-page-main', 'Тир лист игр')
@section('sub-header-page-main', 'Здесь можно составить свой тир лист игр')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush