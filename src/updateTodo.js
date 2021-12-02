function editTodo(el) {
    el.style.border = "1px rgb(0, 5, 99)";
    el.style.padding = "15px";
    el.contentEditable = true;
}

function addTodo(el) {
    el.style.border = "1px rgb(0, 5, 99)";
    el.contentEditable = true;
}

function updateTodo(el, newId) {
    el.style.padding = "15px";
    el.style.border = "none";
    el.contentEditable = false;

    fetch('/todo/update', {
        method: 'POST',
        body: JSON.stringify({
            id: newId,
            title: el.innerText
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then((response) => response.text())
        .then((json) => {
                console.log(json);
                if (json) {
                    location.reload();
                }
            }
        );

}

function newTodo(el) {
    el.style.padding = "15px";
    el.style.border = "none";
    el.contentEditable = false;

    fetch('/todo/add', {
        method: 'POST',
        body: JSON.stringify({
            title: el.innerText
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then((response) => response.text())
        .then((json) => {
                console.log(json);
                if (json) {
                    location.reload();
                }
            }
        );
}

function finishTodo(id) {
    fetch('/todo/finish', {
        method: 'POST',
        body: JSON.stringify({
            id: id,
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then((response) => response.text())
        .then((json) => {
                console.log(json);
                if (json) {
                    location.reload();
                }
            }
        );
}

function deleteTodo(id) {
    fetch('/todo/delete', {
        method: 'POST',
        body: JSON.stringify({
            id: id,
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then((response) => response.text())
        .then((json) => {
                console.log(json);
                if (json) {
                    location.reload();
                }
            }
        );
}