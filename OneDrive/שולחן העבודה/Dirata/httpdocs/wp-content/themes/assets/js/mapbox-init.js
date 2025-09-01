(function ($) {
    "use strict";

    $(document).ready(function () {
        if (!document.getElementById('map')) {
            return;
        }

        mapboxgl.accessToken = mapboxSettings.accessToken;

        mapboxgl.setRTLTextPlugin(
            'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.3/mapbox-gl-rtl-text.js',
            null,
            true
        );

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v11',
            center: [34.643497, 31.801447],
            zoom: 15,
        });
        map.addControl(new mapboxgl.NavigationControl());
        const language = new MapboxLanguage();
        map.addControl(language);

        function formatPrice(price) {
            const millionValue = (parseFloat(price) / 1000000).toFixed(1);
            return `${millionValue} מ׳ ₪`;
        }

        function clearMarkers() {
            apartmentMarkers.forEach(markerObj => markerObj.marker.remove());
            apartmentMarkers = [];
        }

        let apartmentMarkers = [];
        let isManualZoom = false;
        let isFirstLoad = true;

        function updateMapMarkers(args = {}) {
            $.ajax({
                url: mapboxSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'dirata_get_apartments_for_map',
                    ...args
                },
                success: function (response) {
                    clearMarkers();

                    if (response.length === 0) {
                        console.log("No apartments found.");
                        return;
                    }

                    let bounds = new mapboxgl.LngLatBounds();

                    response.forEach(apartment => {
                        const lon = parseFloat(apartment.longitude);
                        const lat = parseFloat(apartment.latitude);
                        const formattedPrice = formatPrice(apartment.price);

                        if (isNaN(lon) || isNaN(lat)) {
                            console.error('Invalid coordinates for:', apartment);
                            return;
                        }

                        bounds.extend([lon, lat]);

                        const labelEl = document.createElement('div');
                        labelEl.className = 'mapbox-price-label';
                        labelEl.innerText = formattedPrice;

                        const markerEl = document.createElement('div');
                        markerEl.className = 'mapbox-marker';

                        const markerWrapper = document.createElement('div');
                        markerWrapper.className = 'mapbox-marker-wrapper';
                        markerWrapper.appendChild(labelEl);
                        markerWrapper.appendChild(markerEl);
                        markerWrapper.dataset.apartmentId = apartment.id;

                        const marker = new mapboxgl.Marker({ element: markerWrapper })
                            .setLngLat([lon, lat])
                            .addTo(map);

                        apartmentMarkers.push({
                            id: apartment.id,
                            marker: marker,
                            longitude: lon,
                            latitude: lat
                        });
                    });

                    if (isFirstLoad) {
                        map.fitBounds(bounds, {
                            padding: 50,
                            duration: 1200
                        });
                        isFirstLoad = false;
                    }
                },
                error: function () {

                }

            });

            // Single apartment marker
            if (typeof singleApartmentData !== "undefined" && singleApartmentData.longitude && singleApartmentData.latitude) {
                console.log("Single Apartment Detected:", singleApartmentData);

                setTimeout(() => {
                    console.log("Delayed zoom execution");
                    manualZoomToLocation(singleApartmentData.longitude, singleApartmentData.latitude);
                }, 1200);

                setTimeout(() => {
                    let markerEl = document.querySelector(`.mapbox-marker-wrapper[data-apartment-id="${singleApartmentData.id}"]`);
                    if (markerEl) {
                        markerEl.classList.add('current-apartment-marker-wrapper');
                        let labelEl = markerEl.querySelector('.mapbox-price-label');
                        if (labelEl) {
                            labelEl.innerText = "";
                        }
                        console.log("Current apartment marker styled:", markerEl);
                    } else {
                        console.warn("No marker found for this apartment.");
                    }
                }, 1500);

            }
            console.log('Response:', response);
            console.log('Before clearing:', apartmentMarkers.length);
            clearMarkers();
            console.log('After clearing:', apartmentMarkers.length);

        }

        function getVisibleApartments() {
            if (isManualZoom || (window.DirataMap && window.DirataMap.disableMapEvents)) {
                return;
            }

            const bounds = map.getBounds();
            const visibleApartments = apartmentMarkers.filter(apartment => (
                apartment.latitude >= bounds.getSouth() &&
                apartment.latitude <= bounds.getNorth() &&
                apartment.longitude >= bounds.getWest() &&
                apartment.longitude <= bounds.getEast()
            ));

            if (visibleApartments.length === 0) {
                document.getElementById('apartment-listings').innerHTML = "<p>אין דירות זמינות באזור זה</p>";
                return;
            }

            // Retrieve the city filter from the URL.
            var urlParams = new URLSearchParams(window.location.search);
            var cityFilter = urlParams.get('city');

            // Build the data payload, including the city filter if available.
            var dataPayload = {
                action: 'dirata_ajax_filter_apartments_by_bounds',
                apartment_ids: visibleApartments.map(a => a.id)
            };
            if (cityFilter) {
                dataPayload.city = parseInt(cityFilter, 10);
            }

            $.ajax({
                url: mapboxSettings.ajaxurl,
                type: 'POST',
                data: dataPayload,
                beforeSend: function () {
                    const skeletons = Array.from({ length: 10 }).map(() => `
                      <div class="col-sm-6 col-lg-6 custom_col apartment-skeleton">
                        <div class="image"></div>
                        <div class="desc">
                          <div class="buttons"></div>
                          <div class="desc-items">
                            <div class="title"></div>
                            <div class="details"></div>
                            <div class="price"></div>
                          </div>
                        </div>
                      </div>`).join('');
                    $('#apartment-listings').html(skeletons);
                },
                success: function (response) {
                    setTimeout(function () {
                        $('#apartment-listings').html(response);
                    }, 200);
                },
                error: function () {

                }
            });
        }

        function manualZoomToLocation(lon, lat, zoomLevel = 16) {
            isManualZoom = true;

            map.flyTo({
                center: [lon, lat],
                zoom: zoomLevel,
                speed: 2.5,
                curve: 1.4,
                essential: true
            });

            map.once('moveend', function () {
                isManualZoom = false;
            });
        }

        map.on('zoomend', getVisibleApartments);
        map.on('moveend', getVisibleApartments);

        // Update map markers based on filters
        function updateMarkersFromFilters(filters) {
            var dataPayload = Object.assign({}, filters, { action: 'dirata_ajax_get_filtered_apartments_for_map' });

            $.ajax({
                url: mapboxSettings.ajaxurl,
                type: 'POST',
                data: dataPayload,
                success: function (response) {
                    // Clear current markers
                    clearMarkers();

                    if (!response || response.length === 0) {
                        console.log("No apartments found for filtered parameters.");
                        return;
                    }

                    var bounds = new mapboxgl.LngLatBounds();

                    response.forEach(function (apartment) {
                        var lon = parseFloat(apartment.longitude);
                        var lat = parseFloat(apartment.latitude);
                        if (isNaN(lon) || isNaN(lat)) return;

                        bounds.extend([lon, lat]);

                        var markerEl = document.createElement('div');
                        markerEl.className = 'mapbox-marker';

                        var marker = new mapboxgl.Marker({ element: markerEl })
                            .setLngLat([lon, lat])
                            .addTo(map);

                        apartmentMarkers.push({
                            id: apartment.id,
                            marker: marker,
                            longitude: lon,
                            latitude: lat
                        });
                    });

                    // Zoom the map to the bounds of the filtered markers
                    map.fitBounds(bounds, {
                        padding: 50,
                        duration: 1200
                    });

                    // When the zooming/move ends, re-enable automatic apartment updates.
                    map.once('moveend', function () {
                        if (window.DirataMap) {
                            window.DirataMap.disableMapEvents = false;
                        }
                    });
                },
                error: function () {

                }
            });
        }

        window.DirataMap = {
            manualZoomToLocation: manualZoomToLocation,
            updateMarkersFromFilters: updateMarkersFromFilters,
            clearMarkers: clearMarkers,
            disableMapEvents: false,
        };

        // Check for a query parameter
        var urlParams = new URLSearchParams(window.location.search);
        var cityParam = urlParams.get('city');
        if (cityParam) {
            if (window.DirataMap) {
                window.DirataMap.disableMapEvents = true;
            }
            var filters = { city: cityParam };
            updateMarkersFromFilters(filters);
        } else {
            updateMapMarkers();
        }
    });
})(jQuery);