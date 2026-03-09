import './bootstrap';

// 1. Стили Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';

// 2. Bootstrap JS + Popper (bundle версия включает всё нужное)
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// 3. Делаем глобальным
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import $ from 'jquery';
window.$ = window.jQuery = $;

// Ждем полной загрузки DOM
document.addEventListener('DOMContentLoaded', function () {
    const dropdownTriggerList = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    
    dropdownTriggerList.forEach(function (toggle) {
        const dropdown = new bootstrap.Dropdown(toggle);
        
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropdown.toggle();
        });
    });
});