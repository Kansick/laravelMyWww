@extends('layouts.main.main')

{{-- 2. Задаем заголовок страницы (опционально) --}}
@section('title', 'Аренда')

@section('header-page-main', 'Рассчет аренды')
@section('sub-header-page-main', 'Здесь можно посмотреть все отчеты по квартплате а так же посчитать новую')

{{-- 3. Пишем контент, который вставится вместо @yield('content') в макете --}}
@section('content')
    
    {{-- Обернули весь блок в section с отступом mt-5 --}}
    <section class="mt-5 mb-5">
        <div class="container" style="max-width: 1200px;">
            <div class="d-flex justify-content-start align-items-center mb-4">
                <h1 class="text-white mb-0">Отчеты по аренде
                    <span class="text-muted ms-2 fs-5">последние 15 записей</span>
                </h1>
            </div>

            @if($records->isEmpty())
                <div id="rentCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-4 justify-content-center">
                                @for($i = 0; $i < 3; $i++)
                                    <div class="col-md-4 d-flex justify-content-center">
                                        <div class="card bg-dark-custom card-custom-hover" 
                                            style="width: 400px; height: 213px;">
                                            
                                            <div class="card-body d-flex justify-content-center align-items-center btn-create-rent" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rentCreate"
                                                style="height: 100%; width: 100%;">
                                                <h5 class="text-white mb-0">Создать отчет</h5>
                                            </div>
                                            
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $chunksRows = 3;
                    $chunks = $records->chunk($chunksRows);
                @endphp
            <div id="rentCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($chunks as $index => $chunk)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row g-4">
                            @foreach($chunk as $item)
                                <div class="col-md-4">
                                    <div class="card h-100 bg-dark-custom card-custom-hover">
                                        <div class="card-body">
                                            <h5 class="rent-report-title" title="{{ $item->title ?? 'NOT FOUND' }}">{{ $item->title ?? "NOT FOUND" }}</h5>
                                            <p class="fw-bold fs-5 text-uppercase">{{ $item->period_date->locale('ru')->isoFormat('DD MMMM YYYY') }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-white-50 small">Итого:</span>
                                                <span class="text-success fw-bold fs-5">
                                                    {{ str_replace(',', '.', $item->result_sum) }} ₽
                                                </span>
                                            </div>
                                            <hr class="border-secondary my-2">
                                            <a href="#" class="btn btn-outline-red w-100 mt-3 btn-sm btn-open-rent-modal"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rentView"
                                                data-id="{{ $item->id }}">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($loop->last && $chunk->count() < $chunksRows)
                                @for($i = $chunk->count(); $i < 3; $i++)
                                    <div class="col-md-4">
                                        <div class="card h-100 bg-dark-custom card-custom-hover">
                                            <div class="card-body d-flex justify-content-center align-items-center btn-create-rent" data-bs-toggle="modal" data-bs-target="#rentCreate">
                                                <h5 class="text-white">Создать отчет</h5>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center align-items-center mt-4">
                @if($chunks->count() > 1)
                    <a href="" class="btn btn-outline-red ms-2 me-2" type="button" data-bs-target="#rentCarousel" data-bs-slide="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                    </a>
                @endif
                    <a href="" class="btn-create-rent btn btn-outline-red ms-2 me-2" data-bs-toggle="modal" data-bs-target="#rentCreate">Создать отчет</a>
                @if($chunks->count() > 1)
                    <a href="" class="btn btn-outline-red ms-2 me-2" type="button" data-bs-target="#rentCarousel" data-bs-slide="next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </a>
                @endif
                </div>
            </div>
            @endif
        </div>
    </section>

@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   @vite(['resources/js/services/rent/rent_index.js'])
@endpush

@include('services.rent.modalRentView')
@include('services.rent.modalRentCreate')