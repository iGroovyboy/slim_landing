export async function get(key) {
    let response = await fetch(`/api/nodes/${key}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        }
    });

    return await response.json();
}

export async function set(key, value) {
    let response = await fetch(`/api/nodes/${key}`, {
        method: 'POST',
        body: value
    });

    return await response.json();
}

export async function allowedFileExtensions() {
    let response = await fetch(`/api/uploads/allowed/`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        }
    });

    return await response.json();
}


