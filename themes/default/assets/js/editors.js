export function text(data) {
    return `<form name="node_editor"><input type="text" class="form-control" name="text" value="${data}"></form>`;
}

export function img(data) {
    return `<form name="node_editor"><input type="text" class="form-control" name="text" value="${data}"><img src="" alt=""></form>`;
}
