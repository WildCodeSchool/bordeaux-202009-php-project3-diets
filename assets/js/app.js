import '../styles/app.scss';

const $ = require('jquery');

require('bootstrap');

const arrow = document.getElementById('welcome-arrow');
if (arrow !== null) {
    arrow.addEventListener('click', () => {
        window.scrollTo({
            top: 710,
            behavior: 'smooth',
        });
    });
}

// Nav-items color
const idPath = document.getElementById('nav-color').innerHTML;
const nav1 = document.getElementById('nav-1');
const nav2 = document.getElementById('nav-2');
const nav3 = document.getElementById('nav-3');
const nav4 = document.getElementById('nav-4');
const nav5 = document.getElementById('nav-5');

if (idPath == 1) {
        nav1.style.color = '#09B174';
}else if (idPath == 2) {
        nav2.style.color = '#09B174';
}else if(idPath == 3) {
        nav3.style.color = '#09B174';
}else if(idPath == 4) {
        nav4.style.color = '#09B174';
}else if(idPath == 5) {
        nav5.style.color = '#09B174';
}
