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

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload" role="tab" aria-controls="upload" aria-selected="true">Upload image</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="link-tab" data-toggle="tab" href="#link" role="tab" aria-controls="link" aria-selected="false">Link to image</a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <div>
                <label for="profile_pic">Choose a file to upload</label>
                <input class="default__img-upload" name="files[]" type="file">
                <input type="hidden" name="thumbnailSize" value="300x400">
            </div>
        </div>
        <div class="tab-pane" id="link" role="tabpanel" aria-labelledby="link-tab">
            <label for="profile_pic">Image URL</label>
            <input class="default__img-src" name="linked_src" type="text">
            <br>

            <label for="profile_pic">Title</label>
            <input class="default__img-title" name="linked_title" type="text">
            <br>

            <label for="profile_pic">Link</label>
            <input class="default__img-link" name="linked_href" type="text">
        </div>
    </div>

    <input type="hidden" name="datatype" value="image">
    <input type="hidden" name="thumbnailSize" value="300x400">
</form>
`;
}

function scripts(data) {
    return (() => {
        window.node_editor = {};
        window.node_editor.image_type = 'upload';

        window.node_editor.prepareForm = function (formData) {
            // const data = {
            //     'src':  formData.get("files[]").name,
            //     'link': formData.get("link[]"),
            //     'alt':  formData.get("img_title[]"),
            // };

            const data = []

            if ('upload' === window.node_editor.image_type) {
                const keys = ['files[]', 'upload_link[]', 'upload_title[]'];

                const {files, upload_link, upload_title} = fn.prepareFormDataForExport(formData, keys);

                for (let [i, file] of files.entries()) {
                    data.push({
                        'src':  file.name,
                        'link': upload_link[i],
                        'alt':  upload_title[i],
                    });
                }
            }

            if ('link' === window.node_editor.image_type) {
                data.push({
                    'src':  formData.get("linked_src"),
                    'link': formData.get("linked_href"),
                    'alt':  formData.get("linked_title"),
                });
            }

            console.log(data);

            formData.set('json', JSON.stringify(data));
            return formData;
        }
        document.addEventListener('click', function (e) {
            let files = document.querySelector('form[name="node_editor"] .default__img-upload'),
                preview = document.querySelector('form[name="node_editor"] .preview');

            if (e.target.id === 'upload-tab'){
                window.node_editor.image_type = 'upload';
            }

            if (e.target.id === 'link-tab'){
                window.node_editor.image_type = 'link';
                preview.innerHTML = '<p>No files</p>';
                files.value = '';
                console.log('files', files);
            }
        });
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
                            name: "upload_title[]",
                        });

                        const link = fn.createEl('input', {
                            placeholder : "https://site.com",
                            type: "text",
                            name: "upload_link[]",
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
