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
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(value)
    });

    return await response.json();
}

export async function getAllowedFiletypes(key) {
    let response = await fetch(`/api/filetypes/`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        }
    });

    return await response.json();
}


