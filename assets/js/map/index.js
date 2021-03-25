function Get(yourUrl) {
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return Httpreq.responseText;
}

var datas = JSON.parse(Get('/jsonMap'));
console.log(datas);

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
   /* const datas = {
        'User1': { "Name": 48.852969, "lon": 2.349903, "Pop": 'Toto' },
        "User2": { "Name": 48.383, "lon": -4.500, "Pop": 'Toto' },
        "User3": { "Name": 48.000, "lon": -4.100, "Pop": 'Quimper' },
        "User4": { "Name": 43.500, "lon": -1.467, "Pop": 'Bayonne' }
    };
    console.log(datas["User1"].Name);*/


    for (data in datas) {
        var marker = L.marker([datas[data].Lat, datas[data].Long]).addTo(macarte);
        var name = datas[data].Name;
        marker.bindPopup(name);
    }