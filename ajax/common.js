// ============================================================
// ERP HRIS - Centralized AJAX Handler
// Prepares the project for future AJAX/API development.
// ============================================================

// Set to empty; callers pass the full relative path from the page.
var API_BASE = '';

function apiRequest(endpoint, method, data, onSuccess, onError) {
    var xhr = new XMLHttpRequest();
    var url = API_BASE + endpoint;
    var jsonData = null;

    if (method === 'GET' && data) {
        var params = [];
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                params.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
            }
        }
        if (params.length) url += '?' + params.join('&');
    } else if (data) {
        jsonData = JSON.stringify(data);
    }

    xhr.open(method, url, true);
    xhr.setRequestHeader('Accept', 'application/json');
    if (jsonData) {
        xhr.setRequestHeader('Content-Type', 'application/json');
    }

    xhr.onload = function () {
        var response;
        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            response = xhr.responseText;
        }
        if (xhr.status >= 200 && xhr.status < 300) {
            if (onSuccess) onSuccess(response, xhr.status);
        } else {
            if (onError) onError(response, xhr.status);
        }
    };

    xhr.onerror = function () {
        if (onError) onError({ error: 'Network error' }, 0);
    };

    xhr.send(jsonData);
}

// Shorthand methods
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
