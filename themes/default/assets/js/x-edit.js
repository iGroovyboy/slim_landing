import * as editors from './editors.js';
import * as api from './api.js';


let editables     = document.querySelectorAll('[data-edit]');

const layer       = document.querySelector('.x-edit'),
      theme       = layer.attributes['data-theme'].value,
      modalEditor = document.getElementById('modalEditor'),
      modalSave   = modalEditor.querySelector('[data-action="save"]'),
      modalCancel = modalEditor.querySelector('[data-action="cancel"]');



// add edit button to all editable elements
[].forEach.call(editables, el => {
    const id = el.attributes['data-edit'].value;

    addEditButtonToElement(el);
});

// bind basic mouse actions to edit buttons
[].forEach.call(document.querySelectorAll('.x-edit'), el => {
    el.addEventListener('mouseover', function (e) {
        if (undefined !== e.target.attributes['data-src']){
            const key = e.target.attributes['data-src'].value;
            document.querySelector(`[data-edit=${key}]`).classList.add('hovered');
        }
    });

    el.addEventListener('mouseleave', function (e) {
        editables = document.querySelectorAll(`[data-edit]`);
        [].forEach.call(editables, editable => {
            editable.classList.remove('hovered');
        });
    });

    el.addEventListener('click', async function (e) {
        if (undefined === e.target.attributes['data-src']){
            return;
        }

        const key = e.target.attributes['data-src'].value;
        const editorType = document.querySelector(`[data-edit=${key}]`).tagName;

        let response = await api.get(key);

        let editor = await getEditor(editorType, response.data) || '';

        modalEditor.setAttribute('data-key', key);
        modalEditor.querySelector(".key").textContent = key;
        modalEditor.querySelector(".slot").innerHTML = editor;

        let modalObj = new bootstrap.Modal(modalEditor, {backdrop: false, keyboard: true, focus: true})
        modalObj.show();
    });
});

// save node
modalSave.addEventListener('click', async function (e) {
    let data = {};

    const formData = new FormData(document.forms.node_editor)
    for (let key of formData.keys()) {
       data[key] = formData.get(key);
    }

    const key = modalEditor.attributes['data-key'].value;

    console.log(`saving: ${key}`, data);

    let response = await api.set(key, data);

});

// cancel save node
modalCancel.addEventListener('click', async function (e) {
    modalEditor.attributes['data-key'].value = '';
});


function addEditButtonToElement(el) {
    const coords = getPos(el);
    const id = el.attributes['data-edit'].value;

    console.log(coords);
    const pos = `left: ${coords.x}px; top: ${coords.y}px;`;

    const html =
        `<button class="x-edit" data-src="${id}" style="position: absolute; ${pos} z-index: 10000; width: 40px; height: 40px; border-radius: 40px; border: none;  background-color: rgba(255,0,0,0.7); color: white;">E</button>`;

    layer.insertAdjacentHTML('beforeend', html);
}

function getPos(el) {
    var rect = el.getBoundingClientRect();
    return {x: rect.left, y: rect.top + window.scrollY};
}

async function getEditor(type, data) {
    // try to load custom theme editors
    let themeEditors = await import(`../../../${theme}/assets/blockeditors/test.js`);

    if (typeof(themeEditors.getTags) === 'function' && themeEditors.getTags().includes(type)){
        return themeEditors.getEditor(type);
    }

    // default editors

    // image uploader
    if (type === 'img') {
        return editors.img(data);
    }

    // single input[type=text]
    else {
        return editors.text(data);
    }

}
