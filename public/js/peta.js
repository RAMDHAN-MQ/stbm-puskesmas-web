var map = L.map('map').setView([-7.5, 111.0], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

function getColor(status) {
    switch(status) {
        case 'Layak': return '#28a745';
        case 'Cukup': return '#ffc107';
        case 'Tidak Layak': return '#dc3545';
        default: return '#6c757d';     
    }
}

fetch('/geojson/banyakan.json')
.then(res => res.json())
.then(geojson => {

    var layerBanyakan = L.geoJSON(geojson, {
        style: function(feature) {
            var desaName = feature.properties.NAMOBJ;
            var status = statusDesa[desaName] || 'Belum Ada Data';
            return {
                color: '#333',
                weight: 1,
                fillColor: getColor(status),
                fillOpacity: 0.6
            };
        },
        onEachFeature: function(feature, layer) {
            var desaName = feature.properties.NAMOBJ;
            var status = statusDesa[desaName] || 'Belum Ada Data';

            layer.bindPopup('<b>Desa:</b> ' + desaName + '<br><b>Status:</b> ' + status);

            layer.on({
                mouseover: function(e) {
                    var l = e.target;
                    l.setStyle({
                        weight: 2,
                        color: '#666',
                        fillOpacity: 0.8
                    });
                },
                mouseout: function(e) {
                    layerBanyakan.resetStyle(e.target);
                }
            });
        }
    }).addTo(map);

    map.fitBounds(layerBanyakan.getBounds());
})
.catch(err => console.error('Gagal load GeoJSON:', err));
