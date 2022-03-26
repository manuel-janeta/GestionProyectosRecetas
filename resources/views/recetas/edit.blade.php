@extends('layouts.app')

<!--Estilos-->
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css" integrity="sha512-CWdvnJD7uGtuypLLe5rLU3eUAkbzBR3Bm1SFPEaRfvXXI2v2H5Y0057EMTzNuGGRIznt8+128QIDQ8RqmHbAdg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('botones')
<a href={{route('recetas.index')}} class="btn btn-primary mr-2 text-white">Lista Recetas</a>
@endsection

@section('content')
<h2 class="text-center mb-5">Editar Receta: {{$receta->nombre}}</h2>
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <form method="POST" action="{{route('recetas.update' , ['receta'=>$receta->id])}}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <div class="form-group">
                <label form="nombre">Nombre Receta</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" id="nombre" placeholder="Nombre Receta" value="{{$receta->nombre}}">
                @error('nombre')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label form="categoria">Categoria</label>
                <select name="categoria" class="form-control @error('categoria') is-invalid @enderror" id="categoria">
                    <option value=''>--Seleccione--</option>
                    @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}" {{$receta->categoria_id==$categoria->id?'selected':''}}>{{$categoria->nombre}}</option>
                    @endforeach
                </select>
                @error('categoria')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label form="ingredientes">Ingredientes</label>
                <input name="ingredientes" type='hidden' id="ingredientes" value="{{$receta->ingredientes}}">
                <trix-editor class="form-control @error('ingredientes') is-invalid @enderror" input="ingredientes"> </trix-editor>
                @error('ingredientes')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="preparacion">Preparación</label>
                <input name="preparacion" type='hidden' id="preparacion" value="{{$receta->preparacion}}">
                <trix-editor class="form-control @error('preparacion') is-invalid @enderror" input="preparacion"> </trix-editor>
                @error('preparacion')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="imagen">Imagen</label>
                <input id="imagen" type='file' class="form-control @error('imagen') is-invalid @enderror" name="imagen">
                <div class="mt-4">
                    <p> Imagen Actual</p>
                    <img src="/storage/{{$receta->imagen}}" style="width:300px">
                </div>
                @error('imagen')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{$message}}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizar Receta">
            </div>
        </form>
    </div>
</div>
@endsection

<!--Estilos-->
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@endsection
