<div id="map" class="h-full"></div>

@assets
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
@endassets

@script
<script>
    let map = L.map('map').setView([{{ env('HOME_LAT') }}, {{ env('HOME_LON') }}], 9);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let pings = @js($pings->select(['lat', 'lon']));

    let line = [];

    pings.forEach((ping) => {
        line.push([ping.lat, ping.lon]);
    });

    L.polyline(line).addTo(map);

</script>
@endscript