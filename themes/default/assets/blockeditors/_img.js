import * as fn from '../js/lib.js';

export function img(data) {
    return {
        form: form(data),
        scripts: scripts(data)
    }
}

function form(data) {
    return `
<form name="node_editor" enctype="multipart/form-data">
    <div class="preview">
        <p>No files</p>
    </div>
    <div>
        <label for="profile_pic">Choose file to upload</label>
        <input class="default__img-upload" name="image" type="file" accept=".jpg, .jpeg, .png">
    </div>
</form>
`;
}

function scripts(data) {
    return (() => {
        document.addEventListener('change', function (e) {
            if (fn.hasClass(e.target, 'default__img-upload')) {
                console.log('you updated  arr image 2', e)
                // preview.src = window.URL.createObjectURL(e.target.files[0]);
                // image.src = window.URL.createObjectURL(input.files[0]);

                const files = e.target.files,
                      preview = document.querySelector('form[name="node_editor"] .preview');

                if (files.length === 0) {
                    preview.innerHTML = '<p>No files</p>';
                }

                let list = document.createElement('ul');

                for (var i = 0; i < files.length; i++) {
                    let listItem = document.createElement('li');
                    let p = document.createElement('p');

                    if (fn.validFileType(files[i])) {
                        p.textContent = 'File name ' + files[i].name + ', file size ' + fn.getFileSize(files[i].size) + '.';
                        let image = document.createElement('img');
                        image.src = window.URL.createObjectURL(files[i]);

                        listItem.appendChild(image);
                        listItem.appendChild(p);

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
