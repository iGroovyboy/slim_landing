import * as fn from '../js/lib.js';

export function myCustomName(data) {
    return {
        form: form(data),
        scripts: scripts(data),
        styles: styles(),
    }
}

function form(data) {
    return `
<form name="node_editor">
    <div>
        <input class="form-control" name="parent" type="hidden" value="${fn.getPageSlug()}">
        <input class="custom__input1" name="text" type="text">
    </div>
</form>
`;
}

function scripts(data) {
    return (() => {
        document.addEventListener('change', function (e) {
            if (fn.hasClass(e.target, 'custom__input1')) {
                // ..
            }
        }, false)

    })()
}

function styles() {
    return `
    `;
}
