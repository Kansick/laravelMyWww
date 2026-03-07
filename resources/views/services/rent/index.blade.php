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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-white mb-0">Отчеты по аренде</h1>
            </div>

            @if($records->isEmpty())
                <div class="alert alert-warning bg-dark border-secondary text-white">
                    Записей пока нет. Добавьте первый расчет.
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
                                            <h5>{{ $item->title ?? "NOT FOUND" }}</h5>
                                            <p>Дата создания: {{ $item->date_create->format('d.m.Y') }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-white-50 small">Итого:</span>
                                                <span class="text-success fw-bold fs-5">
                                                    {{ number_format($item->result_sum, 0, '.', ' ') }} ₽
                                                </span>
                                            </div>
                                            <hr class="border-secondary my-2">
                                            <a href="#" class="btn btn-outline-red w-100 mt-3 btn-sm">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($loop->last && $chunk->count() < $chunksRows)
                                @for($i = $chunk->count(); $i < 3; $i++)
                                    <div class="col-md-4 d-none d-md-block"></div>
                                @endfor
                            @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($chunks->count() > 1)
                <div class="d-flex justify-content-end align-items-center mt-4">
                    <button class="btn bg-dark-custom d-flex align-items-center justify-content-center me-2" type="button" data-bs-target="#rentCarousel" data-bs-slide="prev" 
                        style="width: 50px; height: 30px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                    </button>
                    <button class="btn bg-dark-custom d-flex align-items-center justify-content-center" type="button" data-bs-target="#rentCarousel" data-bs-slide="next"
                            style="width: 50px; height: 30px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>
    </section>

@endsection

{{-- Сюда вставляется script теги для main.blade.php --}}
@push('scripts')
   
@endpush