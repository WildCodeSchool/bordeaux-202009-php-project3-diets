import '../styles/app.scss';

const $ = require('jquery');

require('bootstrap');

const arrow = document.getElementById('welcome-arrow');
arrow.addEventListener('click',() => {
    window.scrollTo({
        top: 710,
        behavior: 'smooth',
    });
})