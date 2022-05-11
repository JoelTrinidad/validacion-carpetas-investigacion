$(function () {

    $('#sendButton').click(sendForm);

    function sendForm() {
        $('.loader').show()
        $('#importModal').modal('hide')
        const file = $('#file').prop("files");
        const formData = new FormData($('#excelForm')[0])
//            if (file[0]) {
            formData.append('file', file[0])
//            }
        $.ajax({
            url: `${window.location.pathname}`, 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            xhr.responseType = "text";
                        }
                    }
                };
                return xhr;
            },
            success: function(res, status, xhr){
                $('.loader').hide();
                var link=document.createElement('a');
                link.href=window.URL.createObjectURL(res);
                link.download= xhr.getResponseHeader("File-Name");
                link.innerHTML = '<p class="fs-4">Descargar archivo</p>'
                $('#exportModal .link-container').html(link);
                $('#exportModal').modal('show')
            },
            error: function(xhr, status, res){
                $('.loader').hide();
                console.log(res);
                console.log(xhr.status);
                if (xhr.status === 400) {
                    errors = xhr.responseJSON;
                    console.log(errors);
                } else {
                    $('.content-wrapper').append('<div class="alert alert-danger alert-dismissible" id="errorAlert">Algo salió mal</div>')

                    setTimeout(function() {
                        $("#errorAlert").remove();
                    }, 5000);
                }
            }
        })
    }
});