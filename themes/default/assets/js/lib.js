export function hasClass(elem, className) {
    return elem.classList.contains(className);
}

export function getPos(el) {
    var rect = el.getBoundingClientRect();
    return {x: rect.left, y: rect.top + window.scrollY};
}
