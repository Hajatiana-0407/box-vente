if ( localStorage.getItem('mode') === 'dark'){
    let body = document.querySelector('body') ; 
    // body.setAttribute('class' , 'dark') ; 
    $('.mode-menu').html('<i class="fa-solid fa-sun"></i>');
    // localStorage.setItem("mode", "dark");
    $('.mode-menu').data("light" , false) 
}
else {
    $('.mode-menu').html('<i class="fa-solid fa-moon"></i>');
    // $('.mode-menu').data("light" , true )  
}

function create() {
    const form = document.querySelector("#modal-form");
    if(!form) {
        alert("undefined modal ...");
        return false;
    }
    const modal = bootstrap.Modal.getOrCreateInstance(form);

    modal.show();
}

function update(id, self) {
    const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-form"));
    $.get($(self).data('url'), {id: id},
        function (data, textStatus, jqXHR) {
          $("#form-content").html(data);
          modal.show();  
        },
    );
}

function _submit(e, form) {
    e.preventDefault();
    const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-form"));
    const submitLoader = $("#submit-loader");
    $(submitLoader).removeClass("d-none");
    $(submitLoader).parent("button").prop("disabled", true);
    $.ajax({
        type: "post",
        url: $(form).attr("action"),
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
    })
    .done(res => {
        if(res.success) {
            $(".content").html(res.page);
            showSuccessAlert();
            showTooltip();
            modal.hide();
        }else {
            $('#form-content').html(res.page);
        }
    })
    .always(() => {
        $(submitLoader).addClass("d-none");
        $(submitLoader).parent("button").prop("disabled", false);
    })
}
function _delete(id, self) {
    const alert = new Alert();
    alert.confirm(() => {
        $.post($(self).data('url'), {id: id},
            function (data, textStatus, jqXHR) {
                $(".content").html(data);
                showTooltip();
            }
        );
    })
}

function sendMessageToUser(e, form) {
    e.preventDefault()
    
    const inputs = $(form).find('input.form-control,textarea.form-control')
    $(inputs).removeClass('is-invalid')
    $.post($(form).attr('action'), $(form).serialize(),
        function (data, textStatus, jqXHR) {
            if(data.success === false) {
                console.log(data);
                for(let input of inputs) {
                    if(data.errors.includes($(input).attr('name'))) {
                        $(input).addClass('is-invalid')
                    }
                }
            } else {
                const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-new-message"));
                modal.hide();
                $("#assist-link").click()
            }
        },
        "json"
    );
}







let window_width = window.innerWidth;

$(document).ready(function () {
    if(window_width <= 768){
        $('.sidebar').addClass('hide');
    }
    $(window).on('resize', function() {
        if($(this).innerWidth() <= 768 ){
            $('.sidebar').addClass('hide');
        }else{
            $('.sidebar').removeClass('hide');
        }
    })
    const elemtooltips = document.querySelectorAll('.btn-tooltip')
    for(let elem of elemtooltips){
        new bootstrap.Tooltip(elem)
    }

    if(localStorage.length === 0 ) {
        // $('body').removeClass("dark");
        $('.mode-menu').html('<i class="fa-solid fa-moon"></i>').data("light",true);

    } else {
        if(localStorage.getItem("mode") === "light") {
            // $('body').removeClass("dark");
            $('.mode-menu').html('<i class="fa-solid fa-moon"></i>').data("light",true);
        } else {
            // $('body').addClass("dark");
            $('.mode-menu').html('<i class="fa-solid fa-sun"></i>').data("light",false);
        }
    }
});

function toggleSidebar() {
    $('.sidebar').toggleClass('hide');
    $('.backdrop').toggleClass('d-none');
}

function toggleMode(self){
    let value = $(self).data('light');
    // $('body').toggleClass('dark');

    if(value){
        $(self).html('<i class="fa-solid fa-sun"></i>')
        // localStorage.setItem("mode","dark")
    }else{
        $(self).html('<i class="fa-solid fa-moon"></i>')
        // localStorage.setItem("mode","light")
    }

    $(self).data('light',!value);

}
function toggleUserMenu() {
    $(".user-menu-wrapper").toggleClass("d-none");
}

function showSuccessAlert() {
    $("#message-success").addClass("show");
    let t_out = setTimeout( () => {
        hideSuccessAlert();
        clearTimeout(t_out);
    }, 5000);
}
function hideSuccessAlert() {
    $("#message-success").removeClass("show");
}







//const conn = new WebSocket('ws://localhost:9001');
const conn = new WebSocket('wss://qitkif.com/ws2/');
conn.onopen = function(e) {
    const data = {
        "type" : "register",
        "userId" : -1,
    };
    conn.send(JSON.stringify(data));
};

conn.onmessage = function(e) {
    let res = JSON.parse(e.data)
    if(in_message_panel && Number(res.idService) === Number($("#id-service-messenger").val()) && Number(res.sender.id) === Number($("#id-user-messenger").val())) {
        $.post(base_url('admin/messenger/markAsRead'), {idService: res.idService},function (data, textStatus, jqXHR) {});

        let piece_jointe = "";
        if(res.pieceJointe) {
            piece_jointe = `<div class="message-piece-jointe">
                                <img src="${base_url('public/piece_jointe/' + res.pieceJointe) }" onclick="zoomIn(this)">
                            </div>`
        }
        $(".message-wrapper").append(`<div class="alert message-list d-flex" role="alert">
            <div>
            <img src="${res.sender.photo ? base_url('public/images/profils/' . res.sender.photo) : base_url('public/images/avatar.png') }" class="photo-messenger">
            </div>
            <div class="ps-3 w-100">
                <div class="d-flex justify-content-between">
                    <strong class="alert-heading">${ res.sender.pseudo }</strong>
                    <span class="text-muted">${ res.date_ }</span>
                </div>
                ${res.message ? res.message : ""}
                ${piece_jointe}
            </div>
        </div>`)

        scrollTobottom('.message-wrapper');

    }else {
        $.getJSON(base_url('admin/messenger/getUnreadCount'),
            function (data, textStatus, jqXHR) {
                if(data.count > 0) {
                    $("#unread-message-count").text(data.count)
                } else {
                    $("#unread-message-count").text(null)
                }
            }
        );
    }
}
