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
            'attrList': $(modal).find('#attributesList'),
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
        let attrList = $(modal['attrList']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);

        title.text(data.title || 'NOT FOUND');

        attrList.empty();
        if (data.attributes && !$.isEmptyObject(data.attributes)) {
            let summFixed = 0;
            $.each(data.attributes, function(key, value) {
                summFixed += value;
                attrList.append(`
                    <span class="text-white d-flex justify-content-start">
                        <span class="">${capitalize(key)}:</span>
                        <span class="fw-bold ms-2">${formatMoney(value)} ₽</span>
                    </span>
                `);
            });
            attrList.append(`
                <span class="text-white d-flex justify-content-start">
                    <span class="">Сумма:</span>
                    <span class="fw-bold ms-2">${formatMoney(summFixed)} ₽</span>
                </span>
            `);
        } else {
            attrList.append('<li class="text-muted p-2">Нет данных</li>');
        }

        tableBody.empty();
        if (data.propertys_values && !$.isEmptyObject(data.propertys_values)) {
            let sumTable = 0;
            $.each(data.propertys_values, function(key, values) {
                if (typeof values === 'object' && values !== null) {
                    let tariff = data.propertys[key] || 0;
                    let start = values.start || 0;
                    let end = values.end || 0;
                    let diff = (end - start).toFixed(2);
                    let summ = (diff * tariff).toFixed(2);
                    sumTable += (diff * tariff);

                    tableBody.append(`
                        <tr>
                            <td class="text-start bg-dark-custom">${key}</td>
                            <td class="text-start bg-dark-custom">${tariff}</td>
                            <td class="text-start bg-dark-custom">${start}</td>
                            <td class="text-start bg-dark-custom">${end}</td>
                            <td class="text-start bg-dark-custom">${diff}</td>
                            <td class="text-start bg-dark-custom">${summ}</td>
                        </tr>
                    `);
                }
            });
            tableSum.html(`${(sumTable).toFixed(2)} ₽`);
        } else {
            tableBody.append('<tr><td colspan="5" class="text-center text-muted">Нет данных</td></tr>');
        }

        totalSum.text(formatMoney(data.result_sum) + ' ₽');
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
        let attrList = $(modal['attrList']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);

        tableBody.empty();
        attrList.empty();
        totalSum.empty();
        tableSum.empty();
        $('.summAttr').empty();
        if(data && typeof data === 'object' && Object.keys(data).length > 0){
            if (data.attributes) {
                Object.entries(data.attributes).forEach(([key, value]) => {
                    attrList.prepend(`
                        <span class="text-white d-flex justify-content-start attrLine">
                            <input type="text" class="form-control key me-2 mt-2 bg-dark-custom" placeholder="Ключ" value="${key}">
                            <input type="text" class="form-control value mt-2 bg-dark-custom" placeholder="Значение" value="${value}">
                        </span>
                    `);
                    recalculateRent(modal);
                    attrList.find('.key, .value').off().on('input', function(){
                        recalculateRent(modal);
                    });
                });
            }
            if (data.propertys && data.propertys_values) {
                Object.entries(data.propertys).forEach(([key, value]) => {
                    let end = 0;
                    Object.entries(data.propertys_values).forEach(([keyValues, valueValues]) => {
                        if(keyValues == key){
                            end = valueValues.end;
                        }
                    });
                    tableBody.append(`
                        <tr class="propertyLine">
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate" id="name" placeholder="Счетчики" value="${key}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="tariff" placeholder="Тариф" value="${value}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="start" placeholder="Начало" value="${end}"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="end" placeholder="Конец"></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom" id="diff" placeholder="0" disabled></td>
                            <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom" id="summ" placeholder="0 ₽" disabled></td>
                        </tr>
                    `);
                    tableBody.find('.table_recalculate').off().on('input', function(){
                        recalculateRent(modal);
                    });
                });
            }
        }

        $('#btnAddAttr').off().on('click', function(){
            attrList.prepend(`
                <span class="text-white d-flex justify-content-start attrLine">
                    <input type="text" class="form-control key me-2 mt-2 bg-dark-custom" placeholder="Ключ">
                    <input type="text" class="form-control value mt-2 bg-dark-custom" placeholder="Значение">
                </span>
            `);
            attrList.find('.key, .value').off().on('input', function(){
                recalculateRent(modal);
            });
        });


        $('#btnRemoveAttr').off().on('click', function(){
            attrList.find('.attrLine').last().remove();
            recalculateRent(modal);
        });

        $('#btnTableLineAdd').off().on('click', function(){
            tableBody.append(`
                <tr class="propertyLine">
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate" id="name" placeholder="Счетчики"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="tariff" placeholder="Тариф"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="start" placeholder="Начало"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom table_recalculate floats" id="end" placeholder="Конец"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom" id="diff" placeholder="0" disabled></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom" id="summ" placeholder="0 ₽" disabled></td>
                </tr>
            `);
            tableBody.find('.table_recalculate').off().on('input', function(){
                recalculateRent(modal);
            });
        });

        $('#btnTableLineRemove').off().on('click', function(){
            tableBody.find('.propertyLine').last().remove();
            recalculateRent(modal);
        });

        $('#btnCreate').off().on('click', function(){
            if(title.val().trim().length !== 0){
                //отсюда
            }else{
                alert("Заполни название");
            }
        })
    }

    function recalculateRent(modal)
    {
        let attrList = $(modal['attrList']);
        let tableBody = $(modal['tableBody']);
        let totalSum = $(modal['totalSum']);
        let tableSum = $(modal['tableSum']);

        let sumAll = 0;
        let sumAttr = 0;
        attrList.find('.value').toArray().forEach((value) => {
            let keyInput = $(value).closest('.attrLine').find('.key');
            if(keyInput.length !== 0){
                if(keyInput.val().length !== 0){
                    let rawValue = $(value).val();
                    let numValue = parseFloat(rawValue.replace(',', '.'));
                    if (!isNaN(numValue)) {
                        sumAttr += numValue;
                        sumAll += numValue;
                    }
                }
            }
        }); 
        $('.summAttr').text(formatMoney(sumAttr) + ' ₽');
        totalSum.text(formatMoney(sumAll) + ' ₽');
        let sumTable = 0;
        tableBody.find('.propertyLine').toArray().forEach((lineTable) => {
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
                        if($(input).attr('id') == 'start') start = parseFloat($(input).val().trim().replace(',','.'));
                        if($(input).attr('id') == 'end') end = parseFloat($(input).val().trim().replace(',','.'));
                        if($(input).attr('id') == 'tariff') tariff = parseFloat($(input).val().trim().replace(',','.'));
                    });
                    let diff = end - start;
                    let summ = diff * tariff;
                    
                    $(lineTable).find('#diff').val(diff.toFixed(2));
                    $(lineTable).find('#summ').val(summ.toFixed(2));
                    
                    sumAll += summ;
                    sumTable += summ;
                }else{
                    $(lineTable).find('#diff').val(0);
                    $(lineTable).find('#summ').val(0);
                }
            }
        });
        totalSum.text(formatMoney(sumAll.toFixed(2)) + ' ₽');
        tableSum.text(formatMoney(sumTable.toFixed(2)) + ' ₽');
    }
});

