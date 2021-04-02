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
    <input type="file" id="profile_pic" name="profile_pic" accept=".jpg, .jpeg, .png" onchange="preview_image(event)">
  </div>
</form>
`;
}

function scripts(data) {
    return function preview_image(event) {
        console.log('hoooray')
    }
}
