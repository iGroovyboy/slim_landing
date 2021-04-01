console.log('edit!');

//find all editable elements
let editables = document.querySelectorAll('[data-edit]');

const layer = document.querySelector('.x-edit'),
    modalSingle = document.getElementById('modalSingle');

[].forEach.call(editables, el => {
    htmlContent = el.innerHTML;
    id = el.attributes['data-edit'].value;
    // type = getElType(el);

    //console.log(el);

    addEditButtonToEl(el);

});



let editButtons = document.querySelectorAll('.x-edit');
[].forEach.call(editButtons, el => {
    el.addEventListener('mouseover', function (e) {
        // console.clear();
        dataSrc = e.target.attributes['data-src'].value

        console.log(dataSrc);

        document.querySelector(`[data-edit=${dataSrc}]`).classList.add('hovered');
    });

    el.addEventListener('mouseleave', function (e) {
        editables = document.querySelectorAll(`[data-edit]`);
        [].forEach.call(editables, editable => {
            editable.classList.remove('hovered');
        });
    });

    el.addEventListener('click', function (e) {
        console.clear();
        console.log(e);

        dataSrc = e.target.attributes['data-src'].value

        input = `<input type="text" class="form-control" value="123">`;
        // show modal
        console.log(modalSingle.querySelector(".name"))
        modalSingle.querySelector(".name").textContent = 'wewewe'
        modalSingle.querySelector(".slot").innerHTML = input

        var modalSingleB = new bootstrap.Modal(modalSingle, {backdrop: false, keyboard: true, focus: true })
        modalSingleB.show();

    });
});

function addEditButtonToEl(el) {
    const coords = getPos(el);
    const id = el.attributes['data-edit'].value;

    console.log(coords);
    const pos = `left: ${coords.x}px; top: ${coords.y}px;`;

    const html =
        `<button class="x-edit" data-src="${id}" style="position: absolute; ${pos} z-index: 10000; width: 40px; height: 40px; border-radius: 40px; border: none;  background-color: rgba(255,0,0,0.7); color: white;">E</button>`;

    layer.insertAdjacentHTML('beforeend', html);
}

function getPos(el) {
    var rect = el.getBoundingClientRect();
    return { x: rect.left , y: rect.top + window.scrollY };
}
