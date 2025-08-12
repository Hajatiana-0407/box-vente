$(document).ready( function (){
    const link = $('.pagination:not(.in_vente) a')
    const strong = $('.pagination:not(.in_vente)  strong')
    for (let i = 0; i < link.length; i++) {
        link[i].setAttribute('class' , 'page-item page-link ')
    }

    if ( strong[0]){
        strong[0].setAttribute('class' , 'page-item page-link active')
    }
})