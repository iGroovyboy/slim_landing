import * as fn from '../js/lib.js';

export function text(data) {
    return {
        form: form(data),
        scripts: ''
    }
}

export function form(data) {
    return `<form name="node_editor">
<input type="hidden" class="form-control" name="parent" value="${fn.getPageSlug()}">
<input type="text" class="form-control" name="text" value="${data}">
</form>`;
}

