export function hasClass(elem, className) {
    return elem.classList.contains(className);
}

export function getPos(el) {
    var rect = el.getBoundingClientRect();
    return {x: rect.left, y: rect.top + window.scrollY};
}

export function validFileType(file, fileTypes = null) {
    if (null === fileTypes){
        fileTypes = [
            'image/jpeg',
            'image/pjpeg',
            'image/svg+xml',
            'image/gif',
            'image/webp',
            'image/png'
        ];
    }

    for (let i = 0; i < fileTypes.length; i++) {
        if (file.type === fileTypes[i]) {
            return true;
        }
    }

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
