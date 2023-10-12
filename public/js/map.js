let map = L.map('map').setView([-15.71667, 46.31667], 13);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    minZoom: 1,
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let marker = L.marker([-15.71667, 46.31667]).addTo(map);
marker.bindPopup("<b>Développeur Javascript</b><br>Majunga.").openPopup();