@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Главная')

@section('header-page-main', 'Мой сайт сервис')
@section('sub-header-page-main', 'Здесь хранятся все нужные сервисы для оптимизации руттиных задач')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush