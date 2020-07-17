@extends('layouts.admin')

@section('content')

<h2>Crear usuario</h2>
<br/>

{{ Form::open(array('route' => 'admin.users.store', 'class'=>'form-horizontal')) }}

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('username', 'Usuario:', ['class'=>'control-label']) }}
            {{ Form::text('username', null, ['class'=>'form-control k-input']) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email:', ['class'=>'control-label']) }}
            {{ Form::text('email', null, ['class'=>'form-control k-input']) }}
        </div>

        <div class="form-group">
            {{ Form::label('password', 'Password:', ['class'=>'control-label']) }}
            {{ Form::password('password', ['class'=>'form-control k-input']) }}
        </div>

        <div class="form-group">
            {{ Form::submit('Guardar', array('class' => 'k-button k-button-icontext k-primary')) }}
            {{ link_to_route('admin.users.index', 'Cancelar', null, array('class'=>'k-button k-button-icontext'))}}
        </div>
    </div>
</div>

{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@endsection
