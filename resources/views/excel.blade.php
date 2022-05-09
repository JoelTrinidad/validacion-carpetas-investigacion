@extends('adminlte::page')
@section('content')
    <div class="col-6 mx-auto pt-4">

        <div class="loader"></div>

        <div class="card">
            <div class="card-header text-center">
                Validación de Carpetas de Investigación
            </div>
            <div class="bg-success text-center">
                Subir el archivo con los folios 
            </div>
            <div class="card-body">
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" id="excelForm">
                    @csrf
                    @if (session('errors'))
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        <br><br>
                    @endif
                    @if (session('success'))
                        {{ session('success') }}
                        <br><br>
                    @endif

                    <div class="input-group mt-4 mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="file" >
                            <label class="custom-file-label" for="file">Subir archivo</label>
                        </div> 
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#importModal">Subir archivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title" >Validación de Carpetas de Investigación</h4>
                </div> <!-- Modal Body -->
                <div class="bg-success text-center">
                    Aviso
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <p>El tiempo de procesamiento y búsqueda puede ser demasiado largo</p>
                        <p>¿Desea continuar?</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">No</button>
                    <button type="button" id="sendButton" form="excelForm" class="btn btn-success px-4">Si</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .loader {
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 3;
            background: url('images/loading.gif') 50% 50% no-repeat rgb(249,249,249);
            opacity: .9;
        }
    </style>
@stop

@section('js')
    <script>
    $(function () {

        $('#sendButton').click(sendForm);

        function sendForm() {
            $('.loader').show()
            $('#importModal').modal('hide')
            const file = $('#file').prop("files");
            const formData = new FormData($('#excelForm')[0])
            formData.append('file', file[0])
            $.ajax({
                url: `${window.location.pathname}`, 
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhrFields:{
                    responseType: 'blob'
                },
                success: function(res, status, xhr){
                    $('.loader').hide();
                    var link=document.createElement('a');
                    link.href=window.URL.createObjectURL(res);
                    link.download= xhr.getResponseHeader("File-Name");
                    link.click();
                }
            })
        }
    });
    </script>
@stop