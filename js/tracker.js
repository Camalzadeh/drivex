let watchID = null;
let totalDistance = 0;
let routePoints = [];
let lastLat = null;
let lastLon = null;
let startTime = null;

$(document).ready(function () {

    $('#start-btn').on('click', function (e) {
        startTracking();
    });

    $('#stop-btn').click(function () {
        if (watchID !== null) {
            navigator.geolocation.clearWatch(watchID);
            watchID = null;
            showStatus('Tracking Paused.', '#ffc107');
            $(this).text('Continue').removeClass('btn-danger').addClass('btn-success');
        } else {
            showStatus('Resuming Tracking...', '#ffc107');
            $(this).text('Stop').removeClass('btn-success').addClass('btn-danger');
            startTracking(true);
        }
    });

    $('#live-form').on('submit', function (e) {
        if (totalDistance === 0) {
            e.preventDefault();
            alert('Error: Distance cannot be 0 km. Drive some distance first.');
            return false;
        }

        if (totalDistance > 0 && totalDistance < 0.1) {
            totalDistance = 0.1;
        }

        const roadTypes = $('#tab-live input[name="road_type[]"]:checked').length;
        const traffic = $('#tab-live input[name="traffic[]"]:checked').length;

        if (roadTypes === 0 || traffic === 0) {
            e.preventDefault();
            alert('Error: Please select at least one Road Type and Traffic condition.');
            return false;
        }

        let endTime = new Date().toISOString();
        $('#end-time').val(endTime);
        $('#distance-input').val(totalDistance.toFixed(2));
        $('#route-points').val(JSON.stringify(routePoints));

        return true;
    });

    function startTracking(isResuming = false) {
        if (!navigator.geolocation) {
            showStatus("Geolocation is not supported by your browser.", "red");
            return;
        }

        if (!isResuming) {
            totalDistance = 0;
            routePoints = [];
            lastLat = null;
            lastLon = null;
            startTime = new Date().toISOString();
            $('#start-time').val(startTime);
        }

        $('#start-btn').hide();
        $('#stop-btn').show();
        showStatus('Thinking...', '#ffc107');

        watchID = navigator.geolocation.watchPosition(updatePosition, handleError, {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
        });
    }
});

function updatePosition(position) {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;
    let timestamp = new Date().toISOString();

    if (lastLat !== null && lastLon !== null) {
        let dist = calculateDistance(lastLat, lastLon, lat, lon);

        if (dist > 0.005) {
            totalDistance += dist;
            $('#distance-display').text(totalDistance.toFixed(2) + ' km');
        }
    }

    lastLat = lat;
    lastLon = lon;

    routePoints.push({
        lat: lat,
        lng: lon,
        time: timestamp
    });

    showStatus('Tracking active: ' + routePoints.length + ' points', '#28a745');
}

function handleError(error) {
    let msg = "Location error.";
    switch (error.code) {
        case error.PERMISSION_DENIED:
            msg = "Location permission denied. Please enable GPS.";
            break;
        case error.POSITION_UNAVAILABLE:
            msg = "Location information is unavailable.";
            break;
        case error.TIMEOUT:
            msg = "The request to get user location timed out.";
            break;
        case error.UNKNOWN_ERROR:
            msg = "An unknown error occurred.";
            break;
    }
    console.warn('Geo Error: ' + msg);
    showStatus(msg + " (Tracking stopped)", "red");

    if (watchID !== null) {
        navigator.geolocation.clearWatch(watchID);
        watchID = null;
        $('#start-btn').show();
        $('#stop-btn').hide();
    }
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function showStatus(text, color) {
    $('#status-display').text(text).css('color', color);
}
