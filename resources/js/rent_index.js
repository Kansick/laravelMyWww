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

        if(!$.isEmptyObject(data)){
            alert('not empty');
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
        });

        $('#btnTableLineAdd').off().on('click', function(){
            tableBody.append(`
                <tr class="propertyLine">
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom name" placeholder="Счетчики"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom tariff" placeholder="Тариф"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom start" placeholder="Начало"></td>
                    <td class="bg-dark-custom"><input type="text" class="form-control bg-dark-custom end" placeholder="Конец"></td>
                    <td class="bg-dark-custom"></td>
                    <td class="bg-dark-custom"></td>
                </tr>
            `);
            tableBody.find('.name, .tariff, .start, .end').off().on('input', function(){
                recalculateRent(modal);
            });
        });

        $('#btnTableLineRemove').off().on('click', function(){
            tableBody.find('.propertyLine').last().remove();
        });

        $('#btnCreate').off().on('click', function(){

        })
    }

    function recalculateRent(modal)
    {
        let title = $(modal['title']);
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

        tableBody.find('.tariff').toArray().forEach((tariff) => {

        });
    }
});

