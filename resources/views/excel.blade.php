@extends('adminlte::page')
@section('content')
    <div class="col-6 mx-auto pt-4">

        <div class="card card-primary ">
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

                    Select excel file to upload <br><br>
                    <input type="file" class="form-control" name="file" id="file"> <br><br>
                    <button type="button" class="btn btn-block btn-primary " data-toggle="modal" data-backdrop="static"
                        data-keyboard="false" data-target="#myModalHorizontal">Subir archivo</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModalHorizontal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title" id="myModalLabel">Validación de Carpetas de Investigación</h4>
                </div> <!-- Modal Body -->
                <div class="bg-info text-center">
                    Aviso
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <p>El tiempo de procesamiento y búsqueda puede ser demasiado largo</p>
                        <p>¿Desea continuar?</p>
                    </div>
                    <div class="modal-body">
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button type="submit" form="excelForm" class="btn btn-primary">Si</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
