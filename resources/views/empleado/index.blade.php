@extends('layouts.adminDashboard')
<!--Contenido del dashboard-->
<!--Hacer el extend del adminDashboard para activar todas las opciones dependiendo del rol-->
@section('module_name')
    <h1 style="color: white;" id="module_text">Empleados</h1>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('/empleado/create') }}"><Button class="btn btn-outline-primary btn-block">Agregar</Button></a>

            <input class="input" type="text" placeholder="Ingrese su búsqueda" id="mInput">

        </div>
        <div class="card-content col-md-12">
            <table class="table is-striped" id="registros">
                <thead>
                    <tr>
                        <th>Id</th>                
                        <th>Codigo Empleado</th>
                        <th>Nombre</th>
                        <th>Apellidos</th> 
                        <th>Fecha nacimiento</th>
                        <th>Telefono</th>
                        <th>RFC</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleado as $item)
                        <tr>
                            <td>{{$item->idEmpleado}}</td>
                            <td>{{$item->codigoEmpleado}}</td>
                            <td>{{$item->nombre}}</td>
                            <td>{{$item->apellidoPaterno.' '.$item->apellidoMaterno}}</td>
                            <td>{{$item->fechaNacimiento}}</td>
                            <td>{{$item->telefono}}</td>
                            <td>{{$item->rfc}}</td>   
                            <td>
                                <a href="/empleado/{{$item->idEmpleado}}/edit"><i
                                    class="material-icons">edit</i></a>
                                <a href="{{ route('deleteEmpleado', $item->idEmpleado) }}"><i class="material-icons"
                                    style="color: #e3342f; margin-left: 15px;">delete_forever</i></a>
                            </td>                          
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
