$(document).ready(function () {
    $('#referenceMat, textarea').keyboard({
        layout: 'qwerty',
        autoAccept: true,
        usePreview: false,
        accepted: function () {
            $(this).blur();
        }
    }) ; 
});

let isDraggableEnabled = false;

$(document).on('focus', '#referenceMat', function () {
    if (!isDraggableEnabled) {
        const container = $('.ui-keyboard-keyset').parent();
        $(container).draggable();
        isDraggableEnabled = true; // Marque comme activé
    }
});