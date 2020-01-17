@extends('layouts.main')
@section('title', 'Neubox Test - Marcador')
@section('content')    
    <a href="{{ route('home') }}" class="btn btn-block btn-outline-secondary mb-3"><i class="fas fa-long-arrow-alt-left"></i> Regresar</a>
    <h3 class="d-block w-100 text-center mb-4">Marcador</h3>
    <form id="formMarcador">        
        <div class="form-group">
            <div class="file_container">                
                <label for="fileTxt">
                    <i class="far fa-file-code"></i>
                    <div>Selecciona un archivo de texto</div>
                    <div class="nameFile"></div>
                </label>
                <input style="display:none;" type="file" class="form-control-file" id="fileTxt" name="fileTxt">
            </div>    
        </div>
        <div class="form-group">
            <div id="msgError" style="display:none;" class="text-center alert alert-warning" role="alert"></div>
            <input type="submit" class="form-control btn btn-primary" value="Verificar ganador">
            <div id="msgSuccess" style="display:none;" class="mt-3 text-center alert alert-success" role="alert"></div>
        </div>
    </form>
@endsection