document.addEventListener("DOMContentLoaded", async function () {
    let mapContainer = document.getElementById("est-map");
    if (!mapContainer) return;

    let locationData = mapContainer.getAttribute("data-location");
    if (!locationData) return;

    let [lat, lng] = locationData.split(",").map(Number);

    // Ensure Google Maps API is fully loaded before using it
    if (typeof google === "undefined" || !google.maps) {
        console.error("Google Maps API failed to load.");
        return;
    }

    try {
        // Load required Google Maps modules using importLibrary
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        let map = new Map(mapContainer, {
            center: { lat: lat, lng: lng },
            zoom: 13,
            mapId: "c90daffa6805ba6d" // Your Google Maps Map ID
        });

        new AdvancedMarkerElement({
            position: { lat: lat, lng: lng },
            map: map,
            title: "Location Marker"
        });

    } catch (error) {
        console.error("Error loading Google Maps libraries:", error);
    }
});
