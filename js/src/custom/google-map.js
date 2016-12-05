function initMap() {

    var items = document.querySelectorAll('[data-lat][data-lng][data-zoom]');

    for (i = 0; i < items.length; ++i) {

        var el = items[i];

        var location = { lat: Number(el.dataset.lat), lng: Number(el.dataset.lng) };

        var map = new google.maps.Map(el, {
            zoom: parseInt(el.dataset.zoom),
            center: location
        });

        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }

}