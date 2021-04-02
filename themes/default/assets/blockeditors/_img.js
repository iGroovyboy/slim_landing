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
  <div>
    <label for="profile_pic">Choose file to upload</label>
    <input class="wrewq" type="file" id="profile_pic" name="profile_pic" accept=".jpg, .jpeg, .png">
  </div>
</form>
`;
}

function scripts(data) {
    return (() => {
        console.log('hoooray')

        document.addEventListener('change', function (e) {
            if (fn.hasClass(e.target, 'wrewq')) {
                console.log('you updated  arr image 2')
            }
        }, false)

    })()
}
