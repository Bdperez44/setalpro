@extends('templates.devoops')

@section('content')

{!! getHeaderMod('Seguimiento a proyectos','Todas las fichas') !!}


<section class='content'>

    <div class="row">
        <div class="col-xs-12">
            <div class="box ui-draggable ui-droppable">

                <div class="box-header">
                    <div class="box-name ui-draggable-handle">
                        <i class="fa fa-table"></i>
                        <span>Listado de todas las fichas</span>
                    </div>
                    <div class="box-icons">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="expand-link">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="no-move"></div>
                </div>

                <div class="box-content">

                    <p>Listado de todas los <code>fichas</code> existentes en la base de datos. </p>
                    <form action="<?php echo url('seguimiento/ficha/index');?>" method="post">
                        <input type='submit' value="Buscar" class="pull-right">
                        <select class="form-control pull-right" style="width:10em;  margin-right: 3px;" name="filtro">
                            <option value="1">N&uacute;mero</option>
                            <option value="2">C&oacute;digo Programa</option>
                            <option value="3">Fecha Inicio</option>
                            <option value="4">Fecha Fin</option></select>
                            <input type="text" name="valor" placeholder="Digite el valor a buscar" class="form-control pull-right" style="width:15em; margin-right: 3px;">
                            <input type="hidden" name="_token" value="<?php echo csrf_token();?>"></form><span style="border-radius:0.25em; width:10em; height:1.90em;margin-right: 3px; cursor:pointer;" class="input-group-addon"><a href="{{ url('seguimiento/ficha/index') }}">Limpiar filtro</a></span><br><br>
                            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th># Ficha</th>
                                <th>Programa</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                @if($rol == 5 or $rol == 0)
                                <th colspan="2"><center>Acciones</center></th>
                                @else
                                <th ><center>Acci&oacute;n</center></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?php $inicioContador = $contador; ?>
                            @if($rol == 5 or $rol == 0)
                                @foreach ($tipos as $ficha)
                                    <tr>
                                        <td data-title="count">{{ $contador++ }}</td>
                                        <td data-title="Ficha">{{ $ficha->fic_numero }}</td>
                                        <td data-title="Programa">{{ $ficha->prog_codigo }}</td>
                                        <td data-title="Fecha Inicio">{{ $ficha->fic_fecha_inicio }}</td>
                                        <td data-title="Fecha Fin">{{ $ficha->fic_fecha_fin }}</td>
                                        <td><a title="Modal" href="#" data-estado="0" data-id="{{ $ficha->fic_numero }}" data-url="{{url("seguimiento/ficha/verdetalle")}}" class='cargarAjax' data-toggle="modal" data-target="#modal">Ver</a></td>
                                        <td><a href="{{ url("seguimiento/ficha/editar/".$ficha->fic_numero)}}">Editar</a></td>
                                    </tr>
                                @endforeach
                              @else      
                                @foreach ($tipos as $ficha)
                                    <tr>
                                        <td data-title="count">{{ $contador++ }}</td>
                                        <td data-title="Ficha">{{ $ficha->fic_numero }}</td>
                                        <td data-title="Programa">{{ $ficha->prog_codigo }}</td>
                                        <td data-title="Fecha Inicio">{{ $ficha->fic_fecha_inicio }}</td>
                                        <td data-title="Fecha Fin">{{ $ficha->fic_fecha_fin }}</td>
                                        <td><a title="Modal" href="#" data-estado="0" data-id="{{ $ficha->fic_numero }}" data-url="{{url("seguimiento/ficha/verdetalle")}}" class='cargarAjax' data-toggle="modal" data-target="#modal">Ver</a></td>
                                    </tr>
                                @endforeach
                           @endif
                        </tbody>
                    </table>
                    @if($cantidadPaginas > 1)
                    @if($cantidadPaginas <= 10)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if($cantidadPaginas > 1 )
                                    <small style="float:left;">
                                        Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorFichas }} registros
                                    </small>
                                @endif
                                @for($i=$cantidadPaginas; $i>0; $i--)
                                    <?php
                                        $style='';
                                        if($i == $pagina){
                                            $style=";background:#087b76; color:white;";
                                        }
                                    ?>
                                    <a href="{{ url('seguimiento/ficha/index') }}?pagina=<?php echo $i; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $i }}</button></a>
                                @endfor
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <small style="float:left;">
                                    Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorFichas }} registros
                                </small>
                                <?php
                                    $style='';
                                    if($cantidadPaginas == $pagina){
                                        $style=";background:#087b76; color:white;";
                                    }
                                    $cantidadInicia = 10;
                                    if($pagina >= 10){
                                        if($pagina == $cantidadPaginas){
                                            $cantidadInicia = $pagina;
                                        }else{
                                            $cantidadInicia = ($pagina+1);
                                        }
                                    }
                                ?>
                                @if($pagina < ($cantidadPaginas-1))
                                    <a href="{{ url('seguimiento/ficha/index') }}?pagina=<?php echo $cantidadPaginas; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;{{ $style }}">{{ $cantidadPaginas }}</button></a>
                                    <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                                @endif
                                @for($i=10; $i>0; $i--)
                                    <?php
                                        $style='';
                                        if($cantidadInicia == $pagina){
                                            $style=";background:#087b76; color:white;";
                                        }
                                    ?>
                                    <a href="{{ url('seguimiento/ficha/index') }}?pagina=<?php echo $cantidadInicia; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $cantidadInicia }}</button></a>
                                    <?php $cantidadInicia--; ?>
                                @endfor
                                @if($pagina >= 10)
                                    <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                                    <a href="{{ url('seguimiento/ficha/index') }}?valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">1</button></a> 
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                </div>

            </div>  
        </div>
        <div id="modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detalle</h4>
                </div>
                <div class="modal-body" id="modalBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    </div>
</section>

@endsection

