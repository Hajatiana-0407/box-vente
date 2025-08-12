$(document).ready(function () {
    $(document).on('change', '#format', function () {
        $('#loaderFacture').removeClass('d-none');
        $('#pdfFrame').addClass('d-none');

        const format = $(this).val();

        let src = $('#pdfFrame').attr('src');
        let new_src = '';
        $('#pdfFrame').attr('src', '');

        if (format == 'A4') {
            new_src = src.replace('tiquet', 'facture');
        } else {
            new_src = src.replace('facture', 'tiquet');
        }
        $('#pdfFrame').attr('src', new_src);

        setTimeout(function () {
            $('#loaderFacture').addClass('d-none');
            $('#pdfFrame').removeClass('d-none');
        }, 5000)
    })
    $(document.body).on("click", ".detail", function () {
        var idfacture = $(this).data("idfacture");
        $.ajax({
            url: base_url("getDetails"),
            type: "post",
            data: {
                idfacture: idfacture,
            },
        }).done(function (data) {
            $("#tab").html(data);
        });
    });
})