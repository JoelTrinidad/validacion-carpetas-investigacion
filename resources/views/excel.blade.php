
@extends('adminlte::page')
@section('content')
<div class="col-6 mx-auto pt-4">

    <div class="card card-primary ">
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
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
                <button type="submit" class="btn btn-block btn-primary ">Upload File</button>
            </form>
        </div>
    </div>
</div>
@stop