function Get(yourUrl)
{
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return Httpreq.responseText;
}

var datas = JSON.parse(Get('/jsonMap'));
var datasCompany = datas[0];
var datasFreelancer = datas[1];
var datasDietetician = datas[2];
console.log(datasCompany, datasFreelancer, datasDietetician);

const all = document.getElementById('all');
const filterCompany = document.getElementById('company');
const filterFreelancer = document.getElementById('freelancer');
const filterDietetician = document.getElementById('dietetician');
const buttons = document.getElementsByClassName('btn');

var markers = new L.LayerGroup();


    // On initialise la latitude et la longitude de Paris (centre de la carte)
    var macarte = null;

    // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
    macarte = L.map('map').setView([44.737789, -0.57918], 11);
    // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    // Il est toujours bien de laisser le lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 2,
    }).addTo(macarte);

// Nous ajoutons des marqueurs
//var iconNew = L.icon({
//    iconUrl: '/assets/images/iconCompany.png',

//    iconSize:     [38, 95], // size of the icon
//    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
//    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
//});

//var markerNew = L.marker([0, 0], {icon: iconNew}).addTo(macarte);

    macarte.addLayer(markers);


    filterCompany.addEventListener('click', () => {
        markers.clearLayers();
        resetButtons();
        for (dataCompany in datasCompany) {
            var markerCompany = L.marker([datasCompany[dataCompany].Lat, datasCompany[dataCompany].Long]).addTo(markers);
            var nameCompany = datasCompany[dataCompany].Name;
            var descriptionCompany = datasCompany[dataCompany].description;
            var popup = L.popup()
                .setContent('<div class="if-you-need-div">' +
                    '<h3>' + nameCompany + '</\h3>' +
                    '<p><b>' + descriptionCompany + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerCompany.bindPopup(popup);
        }
        filterCompany.classList.add('active');
    });




    filterFreelancer.addEventListener('click', () => {
        markers.clearLayers();
        resetButtons();
        for (dataFreelancer in datasFreelancer) {
            var markerFreelancer = L.marker([datasFreelancer[dataFreelancer].Lat, datasFreelancer[dataFreelancer].Long]).addTo(markers);
            var nameFreelancer = datasFreelancer[dataFreelancer].Name;
            var descriptionFreelancer = datasCompany[dataCompany].description;
            var popup = L.popup()
                .setContent('<div class="if-you-need-div">' +
                    '<h3>' + nameFreelancer + '</\h3>' +
                    '<p><b>' + descriptionFreelancer + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerFreelancer.bindPopup(popup);
        }
        filterFreelancer.classList.add('active');
    });

    filterDietetician.addEventListener('click', () => {
        markers.clearLayers();
        resetButtons();
        for (dataDietetician in datasDietetician) {
            var markerDietetician = L.marker([datasDietetician[dataDietetician].Lat, datasDietetician[dataDietetician].Long]).addTo(markers);
            var nameDietetician = datasDietetician[dataDietetician].Name;
            var firstnameDietetician = datasDietetician[dataDietetician].FirstName;
            var popup = L.popup()
                .setContent('<div class="popup">' +
                    '<h3>' + nameDietetician + '</\h3>' +
                    '<p><b>' + firstnameDietetician + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerDietetician.bindPopup(popup);
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
                .setContent('<div class="popup">' +
                    '<h3>' + nameCompany + '</\h3>' +
                    '<p><b>' + descriptionCompany + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerCompany.bindPopup(popupCompany);
        }
        for (dataFreelancer in datasFreelancer) {
            var markerFreelancer = L.marker([datasFreelancer[dataFreelancer].Lat, datasFreelancer[dataFreelancer].Long]).addTo(markers);
            var nameFreelancer = datasFreelancer[dataFreelancer].Name;
            var descriptionFreelancer = datasCompany[dataCompany].description;
            var popupFreelancer = L.popup()
                .setContent('<div class="popup">' +
                    '<h3>' + nameFreelancer + '</\h3>' +
                    '<p><b>' + descriptionFreelancer + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerFreelancer.bindPopup(popupFreelancer);
        }
        for (dataDietetician in datasDietetician) {
            var markerDietetician = L.marker([datasDietetician[dataDietetician].Lat, datasDietetician[dataDietetician].Long]).addTo(markers);
            var nameDietetician = datasDietetician[dataDietetician].Name;
            var firstnameDietetician = datasDietetician[dataDietetician].FirstName;
            var popup = L.popup()
                .setContent('<div class="popup">' +
                    '<h3>' + nameDietetician + '</\h3>' +
                    '<p><b>' + firstnameDietetician + '</b><br>' +
                    '</\p>' +
                    '</\div>');
            markerDietetician.bindPopup(popup);
        }
        all.classList.add('active');
    });

    function resetButtons()
    {
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
        }
    }