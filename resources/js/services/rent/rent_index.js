import { $ } from "jquery";

$(document).ready(function(){
    let modals = $('.modalRent');
    let modalsData = [];
    let modalBootstrap = [];
    $.each(modals.toArray(), function(index, modal){
        modalsData['#' + $(modal).attr('id')] = {
            'loader': $(modal).find('#modalLoader'),
            'content': $(modal).find('#modalContent'),
            'title': $(modal).find('#modalTitle'),
            'periodDate': $(modal).find('#modalPeriodDate'),
            'fixedCharges': $(modal).find('#fixedCharges'),
            'tableBody': $(modal).find('#tableBody'),
            'totalSum': $(modal).find('#modalTotalSum'),
            'tableSum': $(modal).find('#tableSum')
        }
        modalBootstrap['#' + $(modal).attr('id')] = new bootstrap.Modal(modal);
    });
    if(Object.keys(modalsData).length === 0) return;

    $('.btn-open-rent-modal').on('click', function() {
        const recordId = $(this).data('id');
        let modal = modalsData[$(this).data('bs-target')];
        $(modal['loader']).show();
        $(modal['content']).hide();
        $(modal['title']).text('Загрузка...');
        
        modalBootstrap[$(this).data('bs-target')].show();

        $.ajax({
            url: `/api/rent/${recordId}`,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                viewRent(data, modal);
                $(modal['loader']).hide();
                $(modal['content']).show();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $(modal['title']).text('Ошибка');
                $(modal['loader']).html(`<p class="text-danger">Не удалось загрузить: ${xhr.status} ${error}</p>`);
            }
        });
    });

    function viewRent(data, modal)
    {
        let title = $(modal['title']);
        let periodDate = $(modal['periodDate']);
        let fixedCharges = $(modal['fixedCharges']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);

        title.text(data.title || 'NOT FOUND');

        periodDate.text(data.date_formatted || 'NOT FOUND');

        fixedCharges.empty();
        if (Array.isArray(data.fixed_charges) && data.fixed_charges.length > 0) {
            let summFixed = 0;
            $.each(data.fixed_charges, function(index, fixed_charge) {
                summFixed += parseFloat(fixed_charge.amount) || 0;
                fixedCharges.append(`
                    <span class="text-white d-flex justify-content-start">
                        <span class="">${capitalize(fixed_charge.title)}:</span>
                        <span class="fw-bold ms-2">${formatMoney(parseFloat(fixed_charge.amount) || 0)} ₽</span>
                    </span>
                `);
            });
            fixedCharges.append(`
                <span class="text-white d-flex justify-content-start">
                    <span class="">Сумма:</span>
                    <span class="fw-bold ms-2">${formatMoney(summFixed)} ₽</span>
                </span>
            `);
        } else {
            fixedCharges.append('<li class="text-muted p-2">Нет данных</li>');
        }

        tableBody.empty();
        if (Array.isArray(data.meter_readings) && data.meter_readings.length > 0) {
            let sumTable = 0;
            $.each(data.meter_readings, function(index, meter_reading) {
                    let amount = parseFloat(meter_reading.amount) || 0;
                    sumTable += amount;

                    tableBody.append(`
                        <tr>
                            <td class="text-start bg-dark-custom">${meter_reading.meter_name}</td>
                            <td class="text-start bg-dark-custom">${parseFloat(meter_reading.tariff) || 0}</td>
                            <td class="text-start bg-dark-custom">${parseFloat(meter_reading.start_value) || 0}</td>
                            <td class="text-start bg-dark-custom">${parseFloat(meter_reading.end_value) || 0}</td>
                            <td class="text-start bg-dark-custom">${parseFloat(meter_reading.consumption) || 0}</td>
                            <td class="text-start bg-dark-custom">${formatMoney(amount)}</td>
                        </tr>
                    `);
            });
            tableSum.html(`${formatMoney(sumTable)} ₽`);
        } else {
            tableBody.append('<tr><td colspan="6" class="text-center text-muted">Нет данных</td></tr>');
        }

        totalSum.text(parseFloat(data.result_sum) || 0 + ' ₽');
    }

    function formatMoney(amount) {
        return Number(amount).toLocaleString('ru-RU', { maximumFractionDigits: 0 });
    }

    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    $('.btn-create-rent').off().on('click', function(e){
        e.preventDefault();
        let modal = modalsData[$(this).data('bs-target')];
        $(modal['loader']).show();
        $(modal['content']).hide();
        $(modal['title']).text('Загрузка...');
        
        modalBootstrap[$(this).data('bs-target')].show();

        $.ajax({
            url: `/api/rent/last`,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                createRent(data, modal);
                $(modal['loader']).hide();
                $(modal['content']).show();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $(modal['title']).text('Ошибка');
                $(modal['loader']).html(`<p class="text-danger">Не удалось загрузить: ${xhr.status} ${error}</p>`);
            }
        });
    });

    function createRent(data, modal)
    {
        let title = $(modal['title']);
        let fixedCharges = $(modal['fixedCharges']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);
        let periodDate = $(modal['periodDate']);
        let periodDateFormatted = $(modal['periodDateFormatted']);

        tableBody.empty();
        fixedCharges.empty();
        totalSum.empty();
        tableSum.empty();
        $('.amountFixedCharges').empty();
        if(data && typeof data === 'object' && Object.keys(data).length > 0){
            let today = getLocalDateString();
            periodDate.val(today);
            title.val(data.title);
            if (data.fixed_charges) {
                $.each(data.fixed_charges, function(index, fixed_charge) {
                    fixedCharges.prepend(`
                        <span class="text-white d-flex justify-content-start fixedChargeLine">
                            <input type="text" class="form-control key me-2 mt-2 bg-dark-custom" placeholder="Ключ" value="${fixed_charge.title}">
                            <input type="text" class="form-control value mt-2 bg-dark-custom" placeholder="Значение" value="${parseFloat(fixed_charge.amount) || 0}">
                        </span>
                    `);
                    recalculateRent(modal);
                    fixedCharges.find('.key, .value').off().on('input', function(){
                        recalculateRent(modal);
                    });
                });
            }
            if (data.meter_readings) {
                $.each(data.meter_readings, function(index, meter_reading) {
                    tableBody.append(`
                        <tr class="meterLine">
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate name" placeholder="Счетчик" value="${meter_reading.meter_name}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate tariff" placeholder="Тариф" value="${parseFloat(meter_reading.tariff) || 0}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate start" placeholder="Начало" value="${parseFloat(meter_reading.end_value) || 0}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate end" placeholder="Конец"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom consumption" placeholder="0" disabled></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom amount" placeholder="0 ₽" disabled></td>
                        </tr>
                    `);
                    tableBody.find('.table_recalculate').off().on('input', function(){
                        recalculateRent(modal);
                    });
                });
            }
        }

        $('#btnFixedChargeAdd').off().on('click', function(){
            fixedCharges.prepend(`
                <span class="text-white d-flex justify-content-start fixedChargeLine">
                    <input type="text" class="form-control key me-2 mt-2 bg-dark-custom" placeholder="Ключ">
                    <input type="text" class="form-control value mt-2 bg-dark-custom" placeholder="Значение">
                </span>
            `);
            fixedCharges.find('.key, .value').off().on('input', function(){
                recalculateRent(modal);
            });
        });


        $('#btnFixedChargeRemove').off().on('click', function(){
            fixedCharges.find('.fixedChargeLine').last().remove();
            recalculateRent(modal);
        });

        $('#btnTableLineAdd').off().on('click', function(){
            tableBody.append(`
                <tr class="meterLine">
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate name" placeholder="Счетчик"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate tariff" placeholder="Тариф"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate start" placeholder="Начало"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate end" placeholder="Конец"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom consumption" placeholder="0" disabled></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom amount" placeholder="0 ₽" disabled></td>
                </tr>
            `);
            tableBody.find('.table_recalculate').off().on('input', function(){
                recalculateRent(modal);
            });
        });

        $('#btnTableLineRemove').off().on('click', function(){
            tableBody.find('.meterLine').last().remove();
            recalculateRent(modal);
        });

        $('#btnCreate').off().on('click', function(){
            let payload = collectRentPayload(modal);

            if (payload.title.length === 0) {
                alert("Заполни название");
                return;
            }

            $.ajax({
                url: '/api/rent/create',
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON || xhr.responseText);

                    if (xhr.status === 422) {
                        alert('Проверь заполнение полей');
                        return;
                    }

                    if (xhr.status === 419) {
                        alert('CSRF-токен устарел. Обнови страницу');
                        return;
                    }

                    alert('Не удалось создать отчет');
                }
            });
        })
    }

    function recalculateRent(modal)
    {
        let fixedCharges = $(modal['fixedCharges']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);

        let sumAll = 0;
        let amountFixedCharges = 0;
        fixedCharges.find('.value').toArray().forEach((value) => {
            let keyInput = $(value).closest('.fixedChargeLine').find('.key');
            if(keyInput.length !== 0){
                if(keyInput.val().length !== 0){
                    let rawValue = $(value).val();
                    let numValue = parseFloat(rawValue.replace(',', '.'));
                    if (!isNaN(numValue)) {
                        amountFixedCharges += numValue;
                        sumAll += numValue;
                    }
                }
            }
        }); 
        $('.amountFixedCharges').text(formatMoney(amountFixedCharges) + ' ₽');
        totalSum.text(sumAll.toFixed(2) || 0 + ' ₽');
        let sumTable = 0;
        tableBody.find('.meterLine').toArray().forEach((lineTable) => {
            let fieldsInput = $(lineTable).find('.table_recalculate').toArray();
            if(Array.isArray(fieldsInput) && fieldsInput.length > 0){
                let countMatch = fieldsInput.length;
                let countMatchResult = 0;
                fieldsInput.forEach(input => {
                    if($(input).val().trim().length !== 0) countMatchResult++;
                });
                if(countMatch == countMatchResult){
                    let start = 0;
                    let end = 0;
                    let tariff = 0;
                    fieldsInput.forEach(input => {
                        if($(input).hasClass('start')) start = parseFloat($(input).val().trim().replace(',','.'));
                        if($(input).hasClass('end')) end = parseFloat($(input).val().trim().replace(',','.'));
                        if($(input).hasClass('tariff')) tariff = parseFloat($(input).val().trim().replace(',','.'));
                    });
                    let consumption = end - start;
                    let amount = consumption * tariff;
                    
                    $(lineTable).find('.consumption').val(consumption.toFixed(2));
                    $(lineTable).find('.amount').val(amount.toFixed(2));
                    
                    sumAll += amount;
                    sumTable += amount;
                }else{
                    $(lineTable).find('.consumption').val(0);
                    $(lineTable).find('.amount').val(0);
                }
            }
        });
        totalSum.text(sumAll.toFixed(2) || 0 + ' ₽');
        tableSum.text(sumTable.toFixed(2) + ' ₽');
    }

    function parseRentNumber(value) {
        let number = parseFloat(String(value || '').replace(',', '.'));
        return Number.isNaN(number) ? 0 : number;
    }

    function getLocalDateString() {
        let date = new Date();
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        return date.toISOString().slice(0, 10);
    }

    function collectRentPayload(modal) {
        let title = $(modal['title']).val().trim();
        let periodDate = $(modal['periodDate']).val().trim();
        let fixedCharges = $(modal['fixedCharges']);
        let tableBody = $(modal['tableBody']);

        let fixedChargesPayload = [];

        fixedCharges.find('.fixedChargeLine').each(function() {
            let chargeTitle = $(this).find('.key').val().trim();
            let amount = parseRentNumber($(this).find('.value').val());

            if (chargeTitle.length === 0) {
                return;
            }

            fixedChargesPayload.push({
                title: chargeTitle,
                amount: amount,
            });
        });

        let meterReadingsPayload = [];

        tableBody.find('.meterLine').each(function() {
            let meterName = $(this).find('.name').val().trim();
            let tariff = parseRentNumber($(this).find('.tariff').val());
            let startValue = parseRentNumber($(this).find('.start').val());
            let endValue = parseRentNumber($(this).find('.end').val());

            if (meterName.length === 0) {
                return;
            }

            meterReadingsPayload.push({
                meter_name: meterName,
                tariff: tariff,
                start_value: startValue,
                end_value: endValue,
            });
        });

        return {
            title: title,
            period_date: periodDate || getLocalDateString(),
            fixed_charges: fixedChargesPayload,
            meter_readings: meterReadingsPayload,
        };
    }

});

