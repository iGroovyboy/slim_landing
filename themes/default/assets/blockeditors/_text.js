import * as fn from '../js/lib.js';

export function text(data) {
    return {
        form: form(data),
        scripts: ''
    }
}

export function form(data) {
    return `<form name="node_editor">
<input type="text" class="form-control" name="text" value="${data}">
<input type="hidden" name="datatype" value="text">
</form>`;
}

