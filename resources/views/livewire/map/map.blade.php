<div id="map" class="h-full flex-grow"></div>

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

    let allLayers = new Map();
    let visibleLayers = new Map(); // We'll store each bimble in a layer
    let hiddenLayers = new Map();
    let bimbleLayerGroup = L.layerGroup();

    // Add all bimbles to map as layers, containing pings of bimble
    // Ensure layer has id of bimble, so we can toggle visibility later
    @js($bimbles).forEach((bimble) => {
        // Create a line for the bimble
        let line = [];
        bimble.pings.forEach((ping) => {
            line.push([ping.lat, ping.lon]);
        });

        // Add the line to the bimble layer group
        let layer = L.polyline(line);
        bimbleLayerGroup.addLayer(layer);
        visibleLayers.set(bimble.id, bimbleLayerGroup.getLayerId(layer));
        allLayers.set(bimble.id, bimbleLayerGroup.getLayerId(layer));

    });

    // add the group of bimble layers to the map
    bimbleLayerGroup.addTo(map);


    Livewire.on('bimbles-changed', (data) => {
        // Hide all layers, show any that we should
        // let newVisibleLayers = new Map();
        // let newHiddenLayers = new Map();
        console.log(data.shownBimbles, data.hiddenBimbles);
        Object.values(data.shownBimbles).forEach(function (bimbleId) {
            let layer = bimbleLayerGroup.getLayer(allLayers.get(bimbleId));
            layer.options.opacity = 1;
            layer.setStyle(layer.options);
        });

        Object.values(data.hiddenBimbles).forEach(function (bimbleId) {
            let layer = bimbleLayerGroup.getLayer(allLayers.get(bimbleId));
            layer.options.opacity = 0;
            layer.setStyle(layer.options);
        });

        // allLayers.forEach(function(layerId, bimbleId) {
        //     Object.values(data.bimbles).forEach((bimble) => {
        //         if (bimble.id === bimbleId) {
        //             newVisibleLayers.set(bimbleId, layerId);
        //             newHiddenLayers.delete(bimbleId);
        //         }
        //     });
        // });

        // visibleLayers = new Map(newVisibleLayers);
        // hiddenLayers = new Map(newHiddenLayers);
        //
        // visibleLayers.values().forEach((layerId) => {
        //     let layer = bimbleLayerGroup.getLayer(layerId);
        //     layer.options.opacity = 1;
        //     layer.setStyle(layer.options);
        // });
        // hiddenLayers.values().forEach((layerId) => {
        //     let layer = bimbleLayerGroup.getLayer(layerId);
        //     layer.options.opacity = 0;
        //     layer.setStyle(layer.options);
        // });
    });

    Echo.channel(`pings`)
        .listen('.ping.created', (e) => {
            console.log(e);
            let ping = e.ping;
            // allLayers[ping.bimble_id]
            // Add ping to line in bimble's layer (or redraw whole line)
            // Ensure line is updated in bimbleLayerGroup
            // And added to map
            // And layer has correct opacity (hidden or shown)
        });

</script>
@endscript
