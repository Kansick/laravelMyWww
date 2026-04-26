<div class="modal modalRent fade" id="rentView" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark-custom border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white fw-bold rent-modal-title me-2" id="modalTitle">
                    Загрузка...
                </h5>

                <div class="ms-auto d-flex align-items-center gap-3 flex-shrink-0">
                    <span class="text-white-50 small text-nowrap" id="modalPeriodDate"></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            
            <div class="modal-body text-white">
                <div id="modalLoader" class="text-center py-5">
                    <div class="spinner-border loader-color" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2 text-muted">Получаем данные...</p>
                </div>

                <div id="modalContent" style="display:none;">
                    <div class="row g-4">
                        <!-- Постоянные расходы -->
                        <div class="col-md-12">
                            <div id="fixedCharges">
                                
                            </div>
                        </div>
                        <!-- Счетчики -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-dark table-sm border-secondary">
                                    <thead>
                                        <tr class="small">
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Счетчики</th>
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Тариф</th>
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Начало</th>
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Конец</th>
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Расход</th>
                                            <th class="text-uppercase fw-bold bg-dark-custom" style="width: 1%;">Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>
                            <span class="text-white d-flex justify-content-start">
                                <span>Сумма:</span>
                                <span id="tableSum" class="fw-bold ms-2">0 ₽</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-secondary">
                <div class="me-auto">
                    <span class="text-white-50 small d-block">Итоговая сумма:</span>
                    <span class="fw-bold fs-4" id="modalTotalSum">0 ₽</span>
                </div>
                <button type="button" class="btn btn-outline-red" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>