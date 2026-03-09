import { $ } from "jquery";

$(document).ready(function(){
    let modal = $('#rentModalGlobal');
    if(modal.length === 0) return;

    let modalBootstrap = new bootstrap.Modal(modal);
    
    let loader = $(modal).find('#modalLoader');
    let content = $(modal).find('#modalContent');
    let title = $(modal).find('#modalTitle');
    let attrList = $(modal).find('#attributesList');
    let tableBody = $(modal).find('#tableBody');
    let totalSum = $(modal).find('#modalTotalSum');

    $('.btn-open-rent-modal').on('click', function() {
        const recordId = $(this).data('id');
        
        console.log('Click detected, ID:', recordId);

        loader.show();
        content.hide();
        title.text('Загрузка...');
        
        modalBootstrap.show();

        $.ajax({
            url: `/api/rent/${recordId}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data received:', data);
                viewRent(data);
                loader.hide();
                content.show();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                title.text('Ошибка');
                loader.html(`<p class="text-danger">Не удалось загрузить: ${xhr.status} ${error}</p>`);
            }
        });
    });

    function viewRent(data)
    {
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
                            <td class="text-start">${key}</td>
                            <td class="text-start">${tariff}</td>
                            <td class="text-start">${start}</td>
                            <td class="text-start">${end}</td>
                            <td class="text-start">${diff}</td>
                            <td class="text-start">${summ}</td>
                        </tr>
                    `);
                }
            });
            $('.tableSum').html(`${(sumTable).toFixed(2)} ₽`);
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
});

