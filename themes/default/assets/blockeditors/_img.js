import * as fn from '../js/lib.js';

export function img(data) {
    return {
        form: form(data),
        scripts: scripts(data),
        styles: styles(),
    }
}

function form(data) {
    return `
<form name="node_editor" enctype="multipart/form-data">
    <div class="preview" style="">
        <p>No image</p>
    </div>
    <div>
        <label for="profile_pic">Choose a file to upload</label>
        <input class="default__img-upload" name="files[]" type="file" multiple="multiple">
        <input type="hidden" name="datatype" value="image">
    </div>
</form>
`;
}

function scripts(data) {
    return (() => {
        window.prepareForm = function (formData) {
            const keys = ['files[]', 'img_title[]'];

            const {files, img_title} = fn.prepareFormDataForExport(formData, keys);

            const data = []
            for (let [i, file] of files.entries()) {
                data.push({
                    'src': file.name,
                    'alt': img_title[i],
                });
            }

            console.log('save: ', data );

            formData.set('json', JSON.stringify(data));
            return formData;
        }
        document.addEventListener('change', function (e) {
            if (fn.hasClass(e.target, 'default__img-upload')) {
                // preview.src = window.URL.createObjectURL(e.target.files[0]);
                // image.src = window.URL.createObjectURL(input.files[0]);

                const files = e.target.files,
                      preview = document.querySelector('form[name="node_editor"] .preview');

                if (files.length === 0) {
                    preview.innerHTML = '<p>No image</p>';
                }

                let list = document.createElement('ul');

                for (var i = 0; i < files.length; i++) {
                    let listItem = document.createElement('li');

                    if (fn.validFileType(files[i])) {
                        let p = document.createElement('p');
                        p.textContent = 'File name ' + files[i].name + ', file size ' + fn.getFileSize(files[i].size) + '.';

                        let image = document.createElement('img');
                        image.src = window.URL.createObjectURL(files[i]);

                        let title = document.createElement('input');
                        title.placeholder = "Title";
                        title.type = "text";
                        title.name = "img_title[]";

                        listItem.appendChild(image);
                        listItem.appendChild(p);
                        listItem.appendChild(title);

                    } else {
                        p.textContent = 'File name ' + files[i].name + ': Not a valid file type. Update your selection.';
                        listItem.appendChild(p);
                    }

                    list.appendChild(listItem);
                }

                preview.appendChild(list);

            }
        }, false)

    })()
}

function styles() {
    return `
    .x-edit .preview {

    }
    .x-edit .preview ul {
        display: flex;
    }
    .x-edit .preview li {
        width: 40%; height: 10rem; overflow: hidden; margin: 5%;
    }
    `;
}
