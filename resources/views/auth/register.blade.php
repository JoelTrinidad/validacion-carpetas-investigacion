@extends('adminlte::auth.register')

@section('auth_body')
    <form action="{{ route('register') }}" method="post">
        @csrf
        {{-- dependencia field --}}
        <div class="input-group mb-3">
            <select name="dependencia" class="form-control @error('dependencia') is-invalid @enderror" value="{{ old('dependencia') }}" autofocus>
                <option>-- Dependencia --</option>
                @foreach($dependencias as $dependencia)
                    <option value="{{ $dependencia['clave'] }}">{{ $dependencia['valor'] }}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-building {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('dependencia')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- curp field --}}
        <div class="input-group mb-3">
            <input type="curp" name="curp" class="form-control @error('curp') is-invalid @enderror"
            value="{{ old('curp') }}" placeholder="{{ __('adminlte::adminlte.curp') }}">
            
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-fingerprint {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('curp')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">
            
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- oficio alta field --}}
        <div class="input-group mb-3">
            <input type="oficio_alta" name="oficio_alta" class="form-control @error('oficio_alta') is-invalid @enderror"
            value="{{ old('oficio_alta') }}" placeholder="{{ __('adminlte::adminlte.oficio_alta') }}">
            
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-file-alt {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('oficio_alta')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="{{ __('adminlte::adminlte.password') }}">
            
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            placeholder="{{ __('adminlte::adminlte.retype_password') }}">
            
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            
            @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>
    </form>
@stop