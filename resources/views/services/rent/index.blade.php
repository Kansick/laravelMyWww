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
                    <a href="" class="btn-create-rent btn btn-outline-red ms-2">Создать отчет</a>
                </h1>
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
                                            <p class="fw-bold fs-5 text-uppercase">{{ $item->date_create->locale('ru')->isoFormat('DD MMMM YYYY') }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-white-50 small">Итого:</span>
                                                <span class="text-success fw-bold fs-5">
                                                    {{ number_format($item->result_sum, 0, '.', ' ') }} ₽
                                                </span>
                                            </div>
                                            <hr class="border-secondary my-2">
                                            <a href="#" class="btn btn-outline-red w-100 mt-3 btn-sm btn-open-rent-modal"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rentModalGlobal"
                                                data-id="{{ $item->id }}">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($loop->last && $chunk->count() < $chunksRows)
                                @for($i = $chunk->count(); $i < 3; $i++)
                                    <div class="col-md-4">
                                        <div class="card h-100 bg-dark-custom card-custom-hover">
                                            <div class="card-body d-flex justify-content-center align-items-center btn-create-rent">
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
   @vite(['resources/js/rent_index.js'])
@endpush


{{-- МОДАЛКА (Одна на всех, внутри контент-плейсхолдеры) --}}
<div class="modal fade" id="rentModalGlobal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark-custom border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold" id="modalTitle">Загрузка...</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body text-white">
                {{-- Индикатор загрузки --}}
                <div id="modalLoader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2 text-muted">Получаем данные...</p>
                </div>

                {{-- Контент (скрыт пока не загрузится) --}}
                <div id="modalContent" style="display:none;">
                    <div class="row g-4">
                        <!-- Постоянные расходы -->
                        <div class="col-md-12">
                            <div id="attributesList">
                                
                            </div>
                        </div>

                        <!-- Счетчики -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-dark table-sm border-secondary">
                                    <thead>
                                        <tr class="small">
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Счетчики</th>
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Тариф</th>
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Начало</th>
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Конец</th>
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Расход</th>
                                            <th class="text-uppercase fw-bold" style="width: 1%;">Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>
                            <span class="text-white d-flex justify-content-start">
                                <span>Сумма:</span>
                                <span class="tableSum fw-bold ms-2"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-secondary">
                <div class="me-auto">
                    <span class="text-white-50 small d-block">Итоговая сумма:</span>
                    <span class="text-success fw-bold fs-4" id="modalTotalSum">0 ₽</span>
                </div>
                <button type="button" class="btn btn-outline-red" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>