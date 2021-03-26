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
    var iconCompany = L.icon({
        iconUrl: 'build/images/bandit.svg',

        iconSize:     [32, 32], // size of the icon
        iconAnchor:   [22, 22], // point of the icon which will correspond to marker's location
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    //Company
    for (dataCompany in datasCompany) {
        var markerCompany = L.marker([datasCompany[dataCompany].Lat, datasCompany[dataCompany].Long], { icon: iconCompany }).addTo(macarte);
        var name = datasCompany[dataCompany].Name;
        markerCompany.valueOf()._icon.style.color = 'green';
        markerCompany.bindPopup(name);
    }

    //Freelancer
    for (dataFreelancer in datasFreelancer) {
        var markerFreelancer = L.marker([datasFreelancer[dataFreelancer].Lat, datasFreelancer[dataFreelancer].Long]).addTo(macarte);
        var name = datasFreelancer[dataFreelancer].Name;
        markerFreelancer.bindPopup(name);
    }

    //Dietetician
    for (dataDietetician in datasDietetician) {
        var markerDietetician = L.marker([datasDietetician[dataDietetician].Lat, datasDietetician[dataDietetician].Long]).addTo(macarte);
        var name = datasDietetician[dataDietetician].Name;
        markerDietetician.bindPopup(name);
    }
