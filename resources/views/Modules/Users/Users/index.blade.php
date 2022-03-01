@extends('templates.devoops')

@section('content')

{!! getHeaderMod('Gesti&oacute;n de Usuarios','Todos los usuarios') !!}

<section class='content'>
    <div class="row">
        <div class="col-xs-12">
            <div class="box ui-draggable ui-droppable">

                <div class="box-header">
                    <div class="box-name ui-draggable-handle">
                        <i class="fa fa-table"></i>
                        <span>Listado de todos los usuarios</span>
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
                    <div class="row" style="margin: 0px 0px 15px 0px;">
                        <form action="<?php echo url('users/users/index');?>" method="post">
                            <input type='submit' value="Buscar" class="pull-right">
                            <input type="text" id="valor" name="valor" placeholder="Digite el valor a buscar" class="form-control pull-right" style="width:15em; margin-right: 3px;">
                            <select  class="form-control pull-right otras_opciones" name="valor" id="option_4" style="width:15em;display:none;margin-right: 3px;">
                            <option value="">Seleccione...</option>
                                @foreach($rolesCreados as $val)
                                <option style="text-transform: capitalize;" value="{{$val->id_rol}}">{{($val->nombre_rol)}}</option>
                                @endforeach
                            </select>
                            <select class="form-control pull-right" style="width:10em;  margin-right: 3px;" name="filtro" id="filtro" data-filtro="{{$filtro}}" data-valor="{{$valor}}" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Identificaci&oacute;n</option>
                                <option value="2">Nombre</option>
                                <option value="3">Apellido</option>
                                <option value="4">Rol</option>
                            </select>
                            <input type="hidden" name="_token" value="<?php echo csrf_token();?>">
                        </form>
                        <span style="border-radius:0.25em; width:10em; height:1.90em;margin-right: 3px; cursor:pointer;" class="input-group-addon"><a href="{{ url('users/users/index') }}">Limpiar filtro</a></span>
                        <p style="display:none;">Listado de todos los <code>usuarios</code> existentes en la base de datos. 
                            <ul style="display:none;">
                                <li><small>Para activar un registro, presione sobre el estado <span class="tag tag-danger" title="Activar">Inactivo</span>
                                        </small>
                                </li>
                                <li><small>Para Inactivar un registro, presione sobre el estado <span class="tag tag-info" title="Inactivar">Activo</span>
                                        </small>
                                </li>
                            </ul>
                        </p>
                    </div>
                    
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th># identificaci&oacute;n</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th><center>Estado</center></th>
                                <th colspan="2" >Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $inicioContador = $contador; ?>

                            @foreach ($users as $user)

                            <tr style="font-size:13px;">
                                <td data-title="count">{{ $contador++ }}</td>
                                <td data-title="Numero">{{ $user->par_identificacion_actual }}</td>
                                <td data-title="Nombres">{{ $user->par_nombres }}</td>
                                <td data-title="Apellidos">{{ $user->par_apellidos }}</td>
                                <td data-title="Email">{{ $user->email }}</td>
                                <td data-title="Eliminar">
                                    <a href="#" style="text-decoration: none" data-url="{{ url("users/users/deleted/".$user->id ) }}" data-toggle="modal" data-target="#modalElimina" class="ajax-link">
                                        <center>
                                            {!! ($user->estado == 0)?'<span class="tag tag-danger" title="Activar">Inactivo</span>':'<span title="Inactivar" class="tag tag-info">Activo</span>' !!}
                                        </center>
                                    </a>
                                </td>
                                <td data-title="Ver">
                                    <a href="#" data-url="{{ url("users/users/show/".$user->id ) }}" data-toggle="modal" data-target="#modal" class="ajax-link" title="Ver">
                                        Ver
                                    </a>
                                </td>
                                <td data-title="Editar">
                                    <a href="{{ url("users/users/edit/".$user->id ) }}" class="ajax-link" title="Editar">
                                        Editar
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>  
                    @if($cantidadPaginas > 1)
                    @if($cantidadPaginas <= 10)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if($cantidadPaginas > 1 )
                                    <small style="float:left;">
                                        Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorUsers }} registros
                                    </small>
                                @endif
                                @for($i=$cantidadPaginas; $i>0; $i--)
                                    <?php
                                        $style='';
                                        if($i == $pagina){
                                            $style=";background:#087b76; color:white;";
                                        }
                                    ?>
                                    <a href="{{ url('users/users/index') }}?pagina=<?php echo $i; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&jaiber=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $i }}</button></a>
                                @endfor
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <small style="float:left;">
                                    Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorUsers }} registros
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
                                    <a href="{{ url('users/users/index') }}?pagina=<?php echo $cantidadPaginas; ?>&valor=<?php echo $valor; ?>&jaiber=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;{{ $style }}">{{ $cantidadPaginas }}</button></a>
                                    <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                                @endif
                                @for($i=10; $i>0; $i--)
                                    <?php
                                        $style='';
                                        if($cantidadInicia == $pagina){
                                            $style=";background:#087b76; color:white;";
                                        }
                                    ?>
                                    <a href="{{ url('users/users/index') }}?pagina=<?php echo $cantidadInicia; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&valor=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $cantidadInicia }}</button></a>
                                    <?php $cantidadInicia--; ?>
                                @endfor
                                @if($pagina >= 10)
                                    <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                                    <a href="{{ url('users/users/index') }}?valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&valor=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">1</button></a> 
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                </div>
            </div>  
        </div>
    </div>
</section>


<div id="modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalle</h4>
            </div>
            <div class="modal-body" id="modalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modalElimina" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ url("users/users/deleted") }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer">
                    ¿Esta seguro que desea <code>activar / Inactivar</code> el usuario?
                    <button type="submit" class="btn btn-success" >Aceptar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins-js')
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "a[data-toggle='modal']", function () {
            //e.prevntDefault();
            var destino = $(this).attr("data-target");
            var url = $(this).attr("data-url");

            $(destino + " .modal-body").html("Cargando ...");

            $.ajax({
                url: url,
                type: "GET",
                success: function (data) {
                    $(destino + " .modal-body").html(data);
                }
            });            
        });
        // seleccionar automaticamente
        $( window).load(function () {
            var filtro = $("#filtro").attr("data-filtro");
            var valor = $("#filtro").attr("data-valor");
            if (filtro !="" && valor !=""){
                $("#filtro option[value="+filtro+"]").attr("selected",true);
                if (filtro == 4){
                    $("#valor").removeAttr("name");
                    $("#valor").css("display","none");
                    $("#option_"+filtro+"").attr("name","valor");
                    $("#option_"+filtro+"").css("display","block");
                    $("#option_"+filtro+" option[value="+valor+"]").attr("selected",true);
                }else{
                    $(".otras_opciones").removeAttr("name");
                    $(".otras_opciones").css("display","none");
                    $("#valor").attr("name","valor");
                    $("#valor").css("display","block");
                    $("#valor").attr("value",valor);
                }
            }
        });
        $(document).on("change","#filtro",function() {
            var option = $(this).val();
            $(".otras_opciones").removeAttr("name");
            $(".otras_opciones").css("display","none");
            if (option == 4) {
                $("#valor").removeAttr("name");
                $("#valor").css("display","none");
                $("#option_"+option+"").attr("name","valor");
                $("#option_"+option+"").css("display","block");
            }else{
                $(".otras_opciones").removeAttr("name");
                $(".otras_opciones").css("display","none");
                $("#valor").attr("name","valor");
                $("#valor").css("display","block");
            }
        });
    });
</script>
@endsection