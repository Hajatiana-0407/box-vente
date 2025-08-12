$(document).ready( function (){
    let  i = 1 ; 
    if (window.innerWidth <= 768 ) {
        i = 0  ; 
    }
    $('.menu-hamborger').on('click' , function (){
        if ( i % 2 != 0 ){
            if (window.innerWidth <= 768 ){
                $('.sidebar').animate({marginLeft:'-220px'} , 200  , function (){
                    $('.corps').animate({marginLeft:'0'} , 350  )
                })

                $('#the_back_drop').remove() ; 
            }
            else {
                $('.sidebar').animate({marginLeft:'-220px'} , 200  , function (){
                    $('.corps').animate({marginLeft:'0'} , 350  )
                })
            }
            i++ ; 
        }
        else {
            if (  window.innerWidth <= 768 ) {
                $('.sidebar').animate({marginLeft:'0'} , 200  , function (){
                })
                $( document.body ).append('<div id="the_back_drop"></div>')
            }
            else {
                $('.sidebar').animate({marginLeft:'0'} , 200  , function (){
                    $('.corps').animate({marginLeft:'220px'} , 350  )
                })
            }
            i++ ; 
        }
    })

    $( document.body).on('click' , '#the_back_drop'  , function (){
        i++ ; 
        $('.sidebar').animate({marginLeft:'-220px'} , 200  , function (){
        })
        $( this).remove(); 
    })
})

$.ajax({
    method : 'post' , 
    url : base_url('Auth/getuseractive'),
    dataType : 'json' ,
    async : false 
}).done( function ( nom ){
    $('#thenameuser').text( nom ) ; 
}).fail( function (){
    console.error('erreur sur la recuperation du nom d\'utilisateur');
    
})