function updateOrder(id, struc) {
    fetch('/todo/order', {
        method: 'POST',
        body: JSON.stringify({
            id: id,
            struc: struc,
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

//
//
// function enableDragSort(listClass) {
//     const sortableLists = document.getElementsByClassName(listClass);
//     Array.prototype.map.call(sortableLists, (list) => {enableDragList(list)});
// }
//
// function enableDragList(list) {
//     Array.prototype.map.call(list.children, (item) => {enableDragItem(item)});
// }
//
// function enableDragItem(item) {
//     item.setAttribute('draggable', true)
//     item.ondrag = handleDrag;
//     item.ondragend = handleDrop;
// }
//
// function handleDrag(item) {
//     const selectedItem = item.target,
//         list = selectedItem.parentNode,
//         x = event.clientX,
//         y = event.clientY;
//
//     selectedItem.classList.add('drag-sort-active');
//     let swapItem = document.elementFromPoint(x, y) === null ? selectedItem : document.elementFromPoint(x, y);
//
//     if (list === swapItem.parentNode) {
//         swapItem = swapItem !== selectedItem.nextSibling ? swapItem : swapItem.nextSibling;
//         list.insertBefore(selectedItem, swapItem);
//     }
//     fetch('/todo/order', {
//         method: 'POST',
//         body: JSON.stringify({
//             id: id,
//             struc: struc,
//         }),
//         headers: {
//             'Content-type': 'application/json; charset=UTF-8'
//         }
//     })
//         .then((response) => response.text())
//         .then((json) => {
//                 console.log(json);
//             }
//         );;
// }
//
// function handleDrop(item) {
//     item.target.classList.remove('drag-sort-active');
// }
//
// (()=> {enableDragSort('drag-sort-enable')})();