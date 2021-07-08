import * as fn from './lib.js';
import * as editors from '../blockeditors/editors.js';
import * as api from './api.js';


let editables     = document.querySelectorAll('[data-edit]'),
    modalObj      = null;

const layer       = document.querySelector('.x-edit'),
      hotspots    = layer.querySelector('.x-edit__hotspots'),
      theme       = layer.attributes['data-theme'].value,

      modalEditor = document.getElementById('modalEditor'),
      modalSave   = modalEditor.querySelector('[data-action="save"]'),
      modalCancel = modalEditor.querySelector('[data-action="cancel"]');

const data_edit   = 'data-edit',
      data_src    = 'data-src',
      data_key    = 'data-key',
      data_empty  = 'data-empty',
      hovered     = 'hovered',

      currentThemeEditorsPath = `../../../${theme}/assets/blockeditors/test.js`;



// add edit button to all editable elements
[].forEach.call(editables, el => {
    const id = el.attributes[data_edit].value;

    placeholdEmptyElements(el);
    addEditButtonToElement(el, hotspots);
});

// bind basic mouse actions to edit buttons
[].forEach.call(document.querySelectorAll('button.x-edit'), el => {
    el.addEventListener('mouseover', function (e) {
        if (undefined !== e.target.attributes[data_src]){
            const key = e.target.attributes[data_src].value;
            document.querySelector(`[data-edit=${key}]`).classList.add(hovered);
        }
    });

    el.addEventListener('mouseleave', function (e) {
        editables = document.querySelectorAll(`[${data_edit}]`);
        [].forEach.call(editables, editable => {
            editable.classList.remove(hovered);
        });
    });

    el.addEventListener('click', async function (e) {
        if (undefined === e.target.attributes[data_src]){
            return;
        }

        const key = e.target.attributes[data_src].value;
        const editorType = document.querySelector(`[${data_edit}=${key}]`).tagName;

        let response = await api.get(key);

        let editor = await getEditor(editorType, response.data) || '';

        modalEditor.setAttribute(data_key, key);
        modalEditor.querySelector(".key").textContent = key;
        modalEditor.querySelector(".key").textContent = key;
        modalEditor.querySelector(".slot").innerHTML  = editor.form;
        layer.querySelector(".x-edit__scripts").innerHTML = `<script>${editor.scripts}</script>`;
        layer.querySelector(".x-edit__styles style").innerHTML = editor.styles;

        modalObj = new bootstrap.Modal(modalEditor, {backdrop: false, keyboard: true, focus: true})
        modalObj.show();
    });
});

// save node
modalSave.addEventListener('click', async function (e) {
    const key = modalEditor.attributes[data_key].value;

    let formData = new FormData(document.forms.node_editor);
    formData.set('parent', fn.getPageSlug());
    formData = window.node_editor.prepareForm(formData);

    const response = await api.set(key, formData);

    window.node_editor = null;

    modalObj.hide();
});

// cancel save node
modalCancel.addEventListener('click', async function (e) {
    modalEditor.attributes[data_key].value = '';
});

function getFormDataAsArray() {
    let data = {};

    for (let key of formData.keys()) {
        console.log(key, formData.get(key));
        data[key] = formData.get(key);
    }

    return data;
}

function addEditButtonToElement(el, parent) {
    const coords = fn.getPos(el);
    const id     = el.attributes[data_edit].value;

    const pos    = `left: ${coords.x}px; top: ${coords.y}px;`;
    const color  = el.hasAttribute(data_empty) ? "background-color: rgba(255, 167, 0, 0.7)" : '';

    const html   = `<button class="x-edit" data-src="${id}" style="${pos} ${color}">E</button>`;

    parent.insertAdjacentHTML('beforeend', html);
}

function placeholdEmptyElements(el) {
    const tag = el.tagName;

    if('P' === tag) {
        el.textContent = el.textContent || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        el.setAttribute(data_empty, 'true');
    }
    if('SPAN' === tag){
        el.textContent = el.textContent || 'oooo';
        el.setAttribute(data_empty, 'true');
    }
    if('A' === tag){
        el.textContent = el.textContent || 'AAAAAAAA';
        el.setAttribute(data_empty, 'true');
    }
    if(tag.includes('H')) {
        el.textContent = el.textContent || 'ENTER TEXT';
        el.setAttribute(data_empty, 'true');
    }

}


async function getEditor(type, data) {
    // try to load custom theme editors
    let themeEditors = await import(currentThemeEditorsPath);

    if (typeof(themeEditors.getTags) === 'function' && themeEditors.getTags().includes(type)){
        return themeEditors.getEditor(type);
    }

    // default editors

    // image uploader
    if (type === 'IMG') {
        return editors.img(data);
    }

    if (type === 'A' || type === 'BUTTON') {
        return editors.link(data);
    }

    // single input[type=text]
    else {
        return editors.text(data);
    }

}
