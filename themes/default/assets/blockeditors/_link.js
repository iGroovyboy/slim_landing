import * as fn from '../js/lib.js';

export function link(data) {
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
        <label for="title">Link text</label>
        <input class="link_title" name="title" type="text">
    </div>
    <div>
        <label for="href">Link address</label>
        <input class="link_href" name="href" type="text">
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
