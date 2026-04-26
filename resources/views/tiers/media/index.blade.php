@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Тир лист медиа')

@section('header-page-main', 'Тир лист медиа')
@section('sub-header-page-main', 'Здесь можно составить свой тир лист по медиа')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush