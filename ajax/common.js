// ============================================================
// ERP HRIS - Centralized AJAX Handler
// Compatible with legacy PHP/CRUD ($_POST)
// ============================================================

var API_BASE = '';

function apiRequest(endpoint, method, data, onSuccess, onError) {

    var xhr = new XMLHttpRequest();
    var url = API_BASE + endpoint;

    method = method.toUpperCase();

    // ----------------------------
    // GET Query String
    // ----------------------------
    if (method === 'GET' && data) {

        var params = [];

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                params.push(
                    encodeURIComponent(key) + '=' +
                    encodeURIComponent(data[key])
                );
            }
        }

        if (params.length) {
            url += '?' + params.join('&');
        }
    }

    xhr.open(method, url, true);
    xhr.setRequestHeader('Accept', 'application/json');

    var sendData = null;

    // ----------------------------
    // POST / PUT / DELETE
    // Send as FormData so PHP receives $_POST
    // ----------------------------
    if (method === 'POST' || method === 'PUT' || method === 'DELETE') {

        sendData = new FormData();

        if (data) {
            for (var key in data) {
                if (data.hasOwnProperty(key)) {

                    if (Array.isArray(data[key])) {

                        data[key].forEach(function (value) {
                            sendData.append(key + '[]', value);
                        });

                    } else {

                        sendData.append(key, data[key]);

                    }
                }
            }
        }
    }

    xhr.onload = function () {

        var response;

        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            response = {
                success: false,
                message: xhr.responseText
            };
        }

        if (xhr.status >= 200 && xhr.status < 300) {

            if (typeof onSuccess === 'function') {
                onSuccess(response, xhr.status);
            }

        } else {

            if (typeof onError === 'function') {
                onError(response, xhr.status);
            }

        }
    };

    xhr.onerror = function () {

        if (typeof onError === 'function') {
            onError({
                success: false,
                message: 'Network error'
            }, 0);
        }

    };

    xhr.send(sendData);
}


// ============================================================
// Helper Methods
// ============================================================

function apiGet(endpoint, data, onSuccess, onError) {
    apiRequest(endpoint, 'GET', data, onSuccess, onError);
}

function apiPost(endpoint, data, onSuccess, onError) {
    apiRequest(endpoint, 'POST', data, onSuccess, onError);
}

function apiPut(endpoint, data, onSuccess, onError) {
    apiRequest(endpoint, 'PUT', data, onSuccess, onError);
}

function apiDelete(endpoint, data, onSuccess, onError) {
    apiRequest(endpoint, 'DELETE', data, onSuccess, onError);
}