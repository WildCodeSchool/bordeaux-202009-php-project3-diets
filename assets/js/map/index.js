function Get(yourUrl) {
    const Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open('GET', yourUrl, false);
    Httpreq.send(null);
    return Httpreq.responseText;
}

const datas = JSON.parse(Get('/jsonMap'));
const datasCompany = datas[0];
const datasFreelancer = datas[1];
const datasDietetician = datas[2];

// const test = document.getElementById('map-container');
// var url = test.dataset.url;


const all = document.getElementById('all');
const filterCompany = document.getElementById('company');
const filterFreelancer = document.getElementById('freelancer');
const filterDietetician = document.getElementById('dietetician');
const buttons = document.getElementsByClassName('btn');

let markers = new L.LayerGroup();

// On initialise la latitude et la longitude de Paris (centre de la carte)
let macarte = null;

// Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
macarte = L.map('map').setView([44.737789, -0.57918], 11);
// Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    // Il est toujours bien de laisser le lien vers la source des données
    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 2,
    maxZoom: 18,
}).addTo(macarte);

// Nous ajoutons des marqueurs

// var iconNew = L.icon({
// iconUrl: (url),

// iconSize:     [38, 95], // size of the icon
// iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
// popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
// });

// var markerNew = L.marker([44.8749500, -0.5178200], { icon: iconNew }).addTo(macarte);

function resetButtons() {
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('active');
    }
}

macarte.addLayer(markers);

filterCompany.addEventListener('click', () => {
    markers.clearLayers();
    resetButtons();
    for (dataCompany in datasCompany) {
        var markerCompany = L.marker([datasCompany[dataCompany].Lat, datasCompany[dataCompany].Long]).addTo(markers);
        var nameCompany = datasCompany[dataCompany].Name;
        var descriptionCompany = datasCompany[dataCompany].description;
        var popup = L.popup()
            .setContent('<div class="popup">'
                + '<button id="companyButton">'
                + '<h3>'
                + nameCompany
                + '</h3>'
                + '</button>'
                + '<p><b>' + descriptionCompany + '</b><br>'
                + '</\p>'
                + '</\div>');
        markerCompany.bindPopup(popup);
        var idCompany = datasCompany[dataCompany].id;
        markerCompany.addEventListener('click', (event) => {
            document.getElementById('companyButton').addEventListener('click', (event) => {
                document.location.href = `http://127.0.0.1:8000/profil/show/${idCompany}`;
            });
        });
    }
    filterCompany.classList.add('active');
});

filterFreelancer.addEventListener('click', () => {
    markers.clearLayers();
    resetButtons();
    for (dataFreelancer in datasFreelancer) {
        var markerFreelancer = L.marker([datasFreelancer[dataFreelancer].Lat, datasFreelancer[dataFreelancer].Long]).addTo(markers);
        var nameFreelancer = datasFreelancer[dataFreelancer].Name;
        var descriptionFreelancer = datasFreelancer[dataFreelancer].description;
        var popupFreelancer = L.popup()
            .setContent('<div class="popup">'
                + '<button id="freelancerButton">'
                + '<h3>'
                + nameFreelancer
                + '</h3>'
                + '</button>'
                + '<p><b>' + descriptionFreelancer + '</b><br>'
                + '</p>'
                + '</div>');
        markerFreelancer.bindPopup(popupFreelancer);
        var idFreelancer = datasFreelancer[dataFreelancer].id;
        markerFreelancer.addEventListener('click', (event) => {
            document.getElementById('freelancerButton').addEventListener('click', (event) => {
                document.location.href = `http://127.0.0.1:8000/profil/show/${idFreelancer}`;
            });
        });
    }
    filterFreelancer.classList.add('active');
});

filterDietetician.addEventListener('click', () => {
    markers.clearLayers();
    resetButtons();

    for (dataDietetician in datasDietetician) {
        var markerDietetician = L.marker([datasDietetician[dataDietetician].Lat, datasDietetician[dataDietetician].Long]).addTo(markers);
        var nameDietetician = datasDietetician[dataDietetician].Name;
        var specializationDietetician = datasDietetician[dataDietetician].Specialization;
        var popup = L.popup()
            .setContent('<div class="popup">'
                + '<button id="dieteticianButton">'
                + '<h3>'
                + nameDietetician
                + '</h3>'
                + '</button>'
                + '<p><b>' + specializationDietetician + '</b><br>'
                + '</p>'
                + '</div>');
        markerDietetician.bindPopup(popup);
        var idDietetician = datasDietetician[dataDietetician].id;
        markerDietetician.addEventListener('click', (event) => {
            document.getElementById('dieteticianButton').addEventListener('click', (event) => {
                document.location.href = `http://127.0.0.1:8000/profil/show/${idDietetician}`;
            });
        });
    }
    filterDietetician.classList.add('active');
});

all.addEventListener('click', () => {
    resetButtons();
    for (dataCompany in datasCompany) {
        var markerCompany = L.marker([datasCompany[dataCompany].Lat, datasCompany[dataCompany].Long]).addTo(markers);
        var nameCompany = datasCompany[dataCompany].Name;
        var descriptionCompany = datasCompany[dataCompany].description;
        var popupCompany = L.popup()
            .setContent('<div class="popup">'
                + '<button id="companyButton">'
                + '<h3>'
                + nameCompany
                + '</h3>'
                + '</button>'
                + '<p><b>' + descriptionCompany + '</b><br>'
                + '</p>'
                + '</div>');
        markerCompany.bindPopup(popupCompany);
        var idCompany = datasCompany[dataCompany].id;
        markerCompany.addEventListener('click', (event) => {
            document.getElementById('companyButton').addEventListener('click', (event) => {
                document.location.href = `http://127.0.0.1:8000/profil/show/${idCompany}`;
            });
        });
    }
    for (dataFreelancer in datasFreelancer) {
        var markerFreelancer = L.marker([datasFreelancer[dataFreelancer].Lat, datasFreelancer[dataFreelancer].Long]).addTo(markers);
        var nameFreelancer = datasFreelancer[dataFreelancer].Name;
        var descriptionFreelancer = datasFreelancer[dataFreelancer].description;
        var popupFreelancer = L.popup()
            .setContent('<div class="popup">'
                + '<button id="freelancerButton">'
                + '<h3>'
                + nameFreelancer
                + '</h3>'
                + '</button>'
                + '<p><b>' + descriptionFreelancer + '</b><br>'
                + '</p>'
                + '</div>');
        markerFreelancer.bindPopup(popupFreelancer);
        var idFreelancer = datasFreelancer[dataFreelancer].id;
        markerFreelancer.addEventListener('click', (event) => {
            document.getElementById('freelancerButton').addEventListener('click', (event) => {
                document.location.href = `http://127.0.0.1:8000/profil/show/${idFreelancer}`;
            });
        });
    }
    for (dataDietetician in datasDietetician) {
        var markerDietetician = L.marker([datasDietetician[dataDietetician].Lat, datasDietetician[dataDietetician].Long]).addTo(markers);
        var nameDietetician = datasDietetician[dataDietetician].Name;
        var specializationDietetician = datasDietetician[dataDietetician].Specialization;
        var popup = L.popup()
            .setContent('<div class="popup">'
                + '<button id="dieteticianButton">'
                + '<h3>'
                + nameDietetician
                + '</h3>'
                + '</button>'
                + '<p><b>' + specializationDietetician + '</b><br>'
                + '</p>'
                + '</div>');
        markerDietetician.bindPopup(popup);
    }
    all.classList.add('active');
    var idDietetician = datasDietetician[dataDietetician].id;
    markerDietetician.addEventListener('click', (event) => {
        document.getElementById('dieteticianButton').addEventListener('click', (event) => {
            document.location.href = `http://127.0.0.1:8000/profil/show/${idDietetician}`;
        });
    });
});
