if ( localStorage.getItem('mode') === 'dark'){
    let body = document.querySelector('body') ; 
    // body.setAttribute('class' , 'dark') ; 
    $('.mode-menu').html('<i class="fa-solid fa-sun"></i>');
    // localStorage.setItem("mode", "dark");
    $('.mode-menu').data("light" , false) 
}
else {
    $('.mode-menu').html('<i class="fa-solid fa-moon"></i>');
    $('.mode-menu').data("light" , true )  
}

$(document.body).on("click", "#validation", function () {
    $.ajax({
        url: base_url('Uniter/validationUniter'),
        type: "post",
        dataType: "json",
        data: {
            nomInsert: $("#nom").val(),
            groupeInsert: $("#groupe").val(),
        },
    }).done(function (data) {
        if (data.success) {
            $("#valider").click();
        } else {
            if (data.error) {
                Swal.fire({
                    title: "Attention !",
                    text: "L'unité est déjà existant.",
                    icon: "error",
                })
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Erreur lors de la vérification de l'existence de l'agent :", textStatus, errorThrown);
    });
});




function deleteIt(elem) {
    const id = elem.getAttribute("data-id");
    Swal.fire({
        title: "Attention",
        text: "Etes-vous sur de bien vouloir supprimer ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, bien sûr",
        cancelButtonText: "Annuler",
    }).then((result) => {
        $("#agentId").val();
        if (result.isConfirmed) {
            $.ajax({
                url: base_url('Uniter/deleteUniter'),
                type: "POST",
                data: { id: id },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        $(elem).closest("tr").remove();
                        Swal.fire({
                            title: "Effectué !",
                            text: "Suppression effectuée.",
                            icon: "success",
                        });
                    }
                    else if ( response.error == 'both' ) {
                        Swal.fire({
                            title: "Attention",
                            text: "Imposible de supprimer un uniter dejà utiliser !!",
                            icon: "warning",
                            cancelButtonText: "Ok",
                        })
                    }
                    else if ( response.error == 'prix' ) {
                        Swal.fire({
                            title: "Attention",
                            text: "L'unité est utilisé dans Prix !!",
                            icon: "warning",
                            cancelButtonText: "Ok",
                        })
                    }
                    else if ( response.error == 'parent' ) {
                        Swal.fire({
                            title: "Attention",
                            text: "L' unité possede des sous unités !!",
                            icon: "warning",
                            cancelButtonText: "Ok",
                        })
                    }
                },
                error: function (xhr, status, error) {
                    // console.error("Erreur lors de la suppression :", error);
                    // location.reload();
                    alert('ereur') ; 
                },
            });
        }
    });
}

$(document.body).on("click", "#modifier", function () {
    $.ajax({
        url: base_url('Uniter/verifiUniter'),
        type: "post",
        dataType: "json",
        data: {
            nomModif: $("#nom_modif").val(),
            oldId: $("#idClient_modif").val(),
            idGroupModif: $("#groupe_modif").val(),
            formule: $("#formule_modif").val(),
        },
    }).done(function (data) {
        if (data.success) {
            $("#modification").click();
        } else if (data.error) {
            Swal.fire({
                title: "Attention !",
                text: "L'unité est déjà existant.",
                icon: "error",
            })
        }
    }).fail(function(){
        alert('erreur dans la modification ') ; 
    });
});



let window_width = window.innerWidth;

$(document).ready(function () {
    if (window_width <= 768) {
        $('.sidebar').addClass('hide');
    }
    $(window).on('resize', function () {
        if ($(this).innerWidth() <= 768) {
            $('.sidebar').addClass('hide');
        } else {
            $('.sidebar').removeClass('hide');
        }
    })
    const elemtooltips = document.querySelectorAll('.btn-tooltip')
    for (let elem of elemtooltips) {
        new bootstrap.Tooltip(elem)
    }

    if (localStorage.length === 0) {
        // $('body').removeClass("dark");
        $('.mode-menu').html('<i class="fa-solid fa-moon"></i>').data("light", true);

    } else {
        if (localStorage.getItem("mode") === "light") {
            // $('body').removeClass("dark");
            $('.mode-menu').html('<i class="fa-solid fa-moon"></i>').data("light", true);
        } else {
            // $('body').addClass("dark");
            $('.mode-menu').html('<i class="fa-solid fa-sun"></i>').data("light", false);
        }
    }
});

function toggleSidebar() {
    $('.sidebar').toggleClass('hide');
    $('.backdrop').toggleClass('d-none');
}

function toggleMode(self) {
    let value = $(self).data('light');
    // $('body').toggleClass('dark');

    if (value) {
        $(self).html('<i class="fa-solid fa-sun"></i>')
        // localStorage.setItem("mode", "dark")
    } else {
        $(self).html('<i class="fa-solid fa-moon"></i>')
        // localStorage.setItem("mode", "light")
    }

    $(self).data('light', !value);

}
function toggleUserMenu() {
    $(".user-menu-wrapper").toggleClass("d-none");
}

function showSuccessAlert() {
    $("#message-success").addClass("show");
    let t_out = setTimeout(() => {
        hideSuccessAlert();
        clearTimeout(t_out);
    }, 5000);
}
function hideSuccessAlert() {
    $("#message-success").removeClass("show");
}


// ****************************************************************************************** //
function DonneUniter(elem) {
    $.ajax({
        url: base_url('Uniter/donnerUniter'),
        type: "post",
        data: {
            uniter: elem.getAttribute("data-id"),
        },
        dataType: "json",
    }).done(function (data) {
        $("#idClient_modif").val(data.idUniter);
        $("#nom_modif").val(data.nomUniter);

        getGroupe ( '#groupe_modif' , true  , data)
        
    }).fail(function (errorMessage) {
        console.log(errorMessage);
    });
}

function getGroupe ( group = '' , edit = false , dataEdit = ''  ){
    $.ajax( {
        method: 'post' , 
        url : base_url('Uniter/getAllGroups') , 
        dataType : 'json'
        
    } ).done (function ( data){
        let content = '' ; 
        let firstgroup = data[0].id_group ; 
        if ( !edit ){
// * *********************pas d'edition 
            for (let i = 0; i < data.length; i++) {
                content += `
                        <option class = 'my_group' value="`+ data[i].id_group+`">`+ data[i].denomination_group+`</option>
                `
            }
            if ( data.length == 0 ){
                content += `
                            <option class = 'my_group' value="aucune groupe"></option>
                     `
            }
            getUniter( '#parent' , firstgroup  , edit , dataEdit) ; 

            $( document ).on( 'click' , '.my_group' , function (){
                getUniter( '#parent' , $(this).val() , edit , dataEdit , true )
                // alert($(this).val()) ; 
            })

        }
// * *********************edition 
        else {
            let the_first ; 
            console.log(data);
            for (let i = 0; i < data.length; i++) {
                if ( data[i].id_group == dataEdit.id_group){
                    content += `
                        <option class = 'my_group_modif' value="`+ data[i].id_group+`">`+ data[i].denomination_group+`</option>
                `
                the_first = data[i].id_group ; 
                }
            }
            for (let i = 0; i < data.length; i++) {
                if ( data[i].id_group != dataEdit.id_group){
                    content += `
                        <option class = 'my_group_modif' value="`+ data[i].id_group+`">`+ data[i].denomination_group+`</option>
                        `
                }
            }
            getUniter( '#parent_modif' , the_first  , edit , dataEdit )

            
        }
        $(group).html( content) ; 

        
        
    }).fail ( function (){
        alert('erreur sur l\'affichage du groupe') ; 
    })
}

// ***** afficher group  **** //  
getGroupe( '#groupe') 

function getUniter( uniter = '' , groupe = '' , edit = '' , dataEdit , click = '' ){
    verfierParent( dataEdit.idUniter) ; 
    var parent = [] ; 
    function verfierParent( iduniter ){
        $.ajax({
            method : 'post' , 
            url : base_url('Uniter/verifierchildUnite') , 
            data : { id : iduniter } , 
            dataType : 'json' ,
        }).done( function (verif){
            if ( verif.length == 0 ){

                $.ajax( {
                    method: 'post' , 
                    url : base_url('Uniter/getUniterbygr/') , 
                    dataType : 'json' , 
                    data : { group : groupe , notIt : parent } 
                    
                } ).done (function ( data){
                    if (edit == false ) {
                        let content_No_edit = ''; 
             // * *********************pas d'edition 
                        if (data.length == 0  || $('#nom').val() == ''){
                            $('#formule_container').css({
                                'display' : 'none '
                            })
                        }
                        else  {
                            $('#formule_container').css({
                                'display' : 'block'
                            })
                        }
                        if ( data.length != 0 ){
                            $('#parent_val').text( data[0].nomUniter ) ;
                        } 
                        for (let i = 0; i < data.length; i++) {
                            content_No_edit += `
                                    <option class = 'my_parent_unit' value="`+ data[i].idUniter+`">`+ data[i].nomUniter+`</option>
                            `
                        }
                        content_No_edit += `
                                    <option class = 'my_parent_unit' value="0">Aucune</option>
                            ` ;
            
                        
                        $(document).on( 'keyup' , "#nom" , function (){
                            
                            if ($('#parent').val() == 0 || $(this).val()==''){
                                $('#formule_container').css({
                                    'display' : 'none'
                                })
                            }
                            else  {
                                $('#formule_container').css({
                                    'display' : 'block'
                                })
            
                            }
                            
                            $('#child_val').text( $('#nom').val())
                        })
            
                        $(document).on('click' , '.my_parent_unit' , function (){
                                if ( $(this).val()!=0 && $('#nom').val()!='' ){
                                    $('#formule_container').css({
                                        'display' : 'block'
                                    })
            
                                }
                                else {
                                    $('#formule_container').css({
                                        'display' : 'none'
                                    })
                                }
                                $('#parent_val').text( $(this).text()) ; 
                        })
            
            
                        
                        $(uniter).html( content_No_edit) ;
            
                    }
              // * *********************edition 
                    else {
                        
                        let content_edit = '' ;
                        if (  dataEdit.formule  != '' ){
                            $('#formule_modif').val( dataEdit.formule) ; 
                        }
                        
                        if ( data.length == 0 ){
                            content_edit = `<option class = 'my_parent_unit_modif' value="0">Aucune</option>`

                            $('#formule_container_modif').css({
                                'display' : 'none'
                            })
            
                            $(uniter).html(content_edit) ; 
                        }
                        else  {

                            $('#formule_container_modif').css({
                                'display' : 'block'
                            })
                            for (let i = 0; i < data.length; i++) {
                                if ( data[i].idUniter == dataEdit.parent ){
                                    content_edit += `<option class = 'my_parent_unit_modif' value="`+ data[i].idUniter +`">`+ data[i].nomUniter +`</option>` ;
                                    $('#parent_val_modif').text( data[i].nomUniter ) 
                                }
                            }
                            for (let i = 0; i < data.length; i++) {
                                if ( data[i].idUniter != dataEdit.idUniter && data[i].idUniter != dataEdit.parent){
                                    content_edit += `<option class = 'my_parent_unit_modif' value="`+ data[i].idUniter +`">`+ data[i].nomUniter +`</option>` ; 
                                }
                            }
                            content_edit += `<option class = 'my_parent_unit_modif' value="0">Aucune</option>` ;
                            
                            if ( data.length == 1 ){
                                $('#formule_container_modif').css({
                                    'display' : 'none'
                                })
                            }


                            $(uniter).html(content_edit) ; 
                        }
                        
                        $('#child_val_modif').text( dataEdit.nomUniter) ; 
            
            
                        $(document).on( 'keyup' , "#nom_modif" , function (){
                            if ($('#parent_modif').val() == 0 || $(this).val()=='' ){
                                $('#formule_container_modif').css({
                                    'display' : 'none'
                                })
                            }
                            else  {
                                $('#formule_container_modif').css({
                                    'display' : 'block'
                                })
                            }
                            $('#child_val_modif').text( $('#nom_modif').val())
                        })
            
                    $( document ).on( 'click' , '.my_group_modif' , function (e){
                        e.stoppropagation ; 
                        $.ajax( {
                            method: 'post' , 
                            url : base_url('Uniter/getUniterbygr') , 
                            dataType : 'json' , 
                            data : { group : $(this).val() , notIt : parent } 
                            
                        } ).done (function ( data){
                            // alert(data) ; 
                            console.log(data);
                            let temp = '' ; 

                            if ( data.length == 1 || data.length == 0){
                                $('#formule_container_modif').css({
                                    'display' : 'none'
                                })
                                $('#formule_modif').val(0) ; 
                            } else {
                                $('#formule_container_modif').css({
                                    'display' : 'block'
                                })
                            }

                            let conf = true ; 
                            for (let i = 0; i < data.length; i++) {
                                if ( data[i].idUniter == dataEdit.parent ){
                                    temp += `<option class = 'my_parent_unit_modif' value="`+ data[i].idUniter +`">`+ data[i].nomUniter +`</option>` ; 
                                    $('#parent_val_modif').text( data[i].nomUniter )
                                    conf = false 
                                }
                            }
                            for (let i = 0; i < data.length; i++) {
                                if ( data[i].idUniter != dataEdit.idUniter && data[i].idUniter != dataEdit.parent){
                                    temp += `<option class = 'my_parent_unit_modif' value="`+ data[i].idUniter +`">`+ data[i].nomUniter +`</option>` ; 
                                    if ( conf ){
                                        $('#parent_val_modif').text( data[i].nomUniter )
                                        conf = false 
                                    }
                                }
                            }
                            temp += `<option class = 'my_parent_unit_modif' value="0">Aucune</option>` ;
                            
                            $(uniter).html(temp) ; 
                        }).fail(function (){
                            alert('erreur sur recuperation des unites par group') ; 
                        })
                    })

                    $(document).on('click' , '.my_parent_unit_modif' , function (){
                        if ( $(this).val()!=0 && $('#nom_modif').val()!='' ){
                            $('#formule_container_modif').css({
                                'display' : 'block'
                            })

                        }
                        else {
                            $('#formule_container_modif').css({
                                'display' : 'none'
                            })
                        }
                        if ( $(this).val()==0){
                            $('#formule_modif').val(0) ; 
                        }
                        $('#parent_val_modif').text( $(this).text()) ; 
                    })
                        
                    }
                }).fail ( function (){
                    alert('erreur sur l\'affichage des uniter') ; 
                })



            }else {
                parent.push(verif[0].idUniter) 
                verfierParent( verif[0].idUniter ) ; 
            }
        }).fail( function (){
            alert('erreur dans la verification ') ; 
        })
    }

    // alert(groupe) ; 
    
}




