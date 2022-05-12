@extends('adminlte::page')
@section('content')
    <div class="col-md-6 mx-auto pt-4">

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

                    <div class="input-group mt-4 mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="file" >
                            <label class="custom-file-label" for="file">Subir archivo</label>
                        </div>
                    </div>
                    <ul id="errors" class="list-unstyled"></ul>
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
    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title" >Validación de Carpetas de Investigación</h4>
                </div>
                <div class="bg-success text-center">
                    Aviso
                </div>
                <div class="modal-body">
                    <div class="text-center link-container">
                        
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/excel.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/excel.js') }}" defer></script>
@stop