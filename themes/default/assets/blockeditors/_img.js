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
        <p>No files</p>
    </div>
    <div>
        <label for="profile_pic">Choose a file to upload</label>
        <input class="default__img-upload" name="files[]" type="file" multiple="multiple">
        <input type="hidden" name="datatype" value="image">
        <input type="hidden" name="thumbnailSize" value="300x400">
    </div>
</form>
`;
}

function scripts(data) {
    return (() => {
        window.prepareForm = function (formData) {
            // const data = {
            //     'src':  formData.get("files[]").name,
            //     'link': formData.get("link[]"),
            //     'alt':  formData.get("img_title[]"),
            // };

            const keys = ['files[]', 'link[]', 'img_title[]'];

            const {files, link, img_title} = fn.prepareFormDataForExport(formData, keys);

            const data = []
            for (let [i, file] of files.entries()) {
                data.push({
                    'src': file.name,
                    'link': link[i],
                    'alt': img_title[i],
                });
            }

            console.log(data);

            formData.set('json', JSON.stringify(data));
            return formData;
        }
        document.addEventListener('change', async function (e) {
            if (fn.hasClass(e.target, 'default__img-upload')) {
                // preview.src = window.URL.createObjectURL(e.target.files[0]);
                // image.src = window.URL.createObjectURL(input.files[0]);

                const files = e.target.files,
                    preview = document.querySelector('form[name="node_editor"] .preview');

                if (files.length === 0) {
                    preview.innerHTML = '<p>No files</p>';
                }

                let list = fn.createEl('ul');

                for (let i = 0; i < files.length; i++) {
                    let listItem = fn.createEl('li');

                    if (await fn.validFileType(files[i])) {
                        preview.innerHTML = '';

                        const p = fn.createEl('p', {
                            textContent: 'File name ' + files[i].name + ', file size ' + fn.getFileSize(files[i].size) + '.'
                        });

                        const image = fn.createEl('img');
                        image.src = window.URL.createObjectURL(files[i]);

                        const title = fn.createEl('input', {
                            placeholder : "Title",
                            type: "text",
                            name: "img_title[]",
                        });

                        const link = fn.createEl('input', {
                            placeholder : "https://site.com",
                            type: "text",
                            name: "link[]",
                        });

                        listItem.appendChild(image);
                        listItem.appendChild(p);
                        listItem.appendChild(title);
                        listItem.appendChild(link);

                    } else {
                        const p = fn.createEl('p', {
                            textContent: 'File name ' + files[i].name + ': Not a valid file type.'
                        });

                        e.target.value = null;
                        e.target.files = null;

                        listItem.appendChild(p);
                    }

                    list.appendChild(listItem);
                }

                preview.innerHTML = '';
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
        width: 40%; overflow: hidden; margin: 5%;
    }
    `;
}
