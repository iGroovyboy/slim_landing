import * as api from './api.js';

export function hasClass(elem, className) {
    return elem.classList.contains(className);
}

export function getPos(el) {
    var rect = el.getBoundingClientRect();
    return {x: rect.left, y: rect.top + window.scrollY};
}

export async function validFileType(file) {
    const fileTypes = await api.allowedFileExtensions();

    if (fileTypes === null) {
        console.log("Couldn't fetch allowed file types");
        return false;
    }

    for (let i = 0; i < fileTypes.length; i++) {
        if (file.type === fileTypes[i]) {
            return true;
        }
    }
    
    console.log('Upload unallowed file type is forbidden');

    return false;
}

export function getFileSize(number) {
    if (number < 1024) {
        return number + 'bytes';
    } else if (number > 1024 && number < 1048576) {
        return (number / 1024).toFixed(1) + 'KB';
    } else if (number > 1048576) {
        return (number / 1048576).toFixed(1) + 'MB';
    }
}

export function getPageSlug() {
    return document.querySelector('body').attributes['data-parent'].value || null;
}

/**
 * Packs FormData data into 'regular' array/obj to let it be unpacked later and be used
 * with templating
 *
 * @param {FormData} formData
 * @param {[string]} keys
 */
export function prepareFormDataForExport(formData, keys) {
    let data = [];

    for (const pair of formData.entries()) {
        keys.forEach(function (keyName, i, arr) {
            if (keyName===pair[0]) {
                keyName = keyName.replace('[]', ''); // remove '[]' from keys

                if (data[keyName] === undefined){
                   data[keyName] = [];
                }

                data[keyName].push( pair[1] )
            }
        });
    }

    return data;
}

export function createEl(tagName, attributes) {
    let element = document.createElement(tagName);
    for (const attr in attributes) {
        element[attr] = attributes[attr];
    }
    return element;
}
