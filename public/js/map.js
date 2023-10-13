let map = L.map('map').setView([0, 0], 13);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    minZoom: 1,
    maxZoom: 20,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

map.invalidateSize();

let xmlHttpRequest = new XMLHttpRequest();

xmlHttpRequest.onreadystatechange = () => {
    if (xmlHttpRequest.readyState == 4) {
      if (xmlHttpRequest.status == 200) {
        let data = JSON.parse(xmlHttpRequest.responseText);
        Object.entries(data).forEach(item => {
            if (item[1][0]) {
                let marker = L.marker([item[1][0].latitude, item[1][0].longitude]).addTo(map);
                marker.bindPopup(`<b>${item[1][0].positionType.type}</b><br>${item[1][0].city.name}.`).openPopup();
            }
        });
      } else {
        console.log("Failed to fetch locations");
      }  
    } 
}

xmlHttpRequest.open("GET", "/job-offers-locations");
xmlHttpRequest.send(null);
