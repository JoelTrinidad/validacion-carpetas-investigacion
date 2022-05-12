$(function () {
    const errorsList = $('#errors')

    $('#sendButton').click(sendForm);
    
    function sendForm() {
        errorsList.html('')
        $('#file').removeClass('is-invalid')
        $('.loader').show()
        $('#importModal').modal('hide')
        const file = $('#file').prop("files");
        const formData = new FormData($('#excelForm')[0])
        if (file[0]) {
            formData.append('file', file[0])
        }
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
                $('.loader').hide()
                createDownloadModal(res, xhr.getResponseHeader("File-Name"))
            },
            error: function(err){
                $('.loader').hide();
                if (err.status === 400) {
                    $('#file').addClass('is-invalid')
                    errors = err.responseJSON['errors'];
                    for (let typeError in errors) {
                        for(let error in errors[typeError]){
                            errorsList.append(`<li class="text-danger">${errors[typeError][error]}</li>`)
                        }
                    }
                } else {
                    $('.content-wrapper').append('<div class="alert alert-danger alert-dismissible col-md-6 col-lg-3 error-alert" id="errorAlert">Algo salió mal<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>')
                }
            }
        })
    }

    function createDownloadModal(file, fileName) {
        var link=document.createElement('a')
        link.href=window.URL.createObjectURL(file)
        link.download= fileName
        link.innerHTML = '<p class="fs-4">Descargar archivo</p>'
        $('#exportModal .link-container').html(link)
        $('#exportModal').modal('show')
    }
});