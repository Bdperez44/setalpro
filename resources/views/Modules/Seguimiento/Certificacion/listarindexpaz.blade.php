@extends('templates.devoops')

@section('content')

{!! getHeaderMod('Listar','Paz y salvo') !!}


<div class="row">
    @include('errors.messages')
    <div class="box-content">
        <div class="box ui-draggable ui-droppable">
            <div class="box-header">
                <div class="box-name ui-draggable-handle">
					<span>Certificación</span>
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
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 style="font-family: cursive;font-weight: bold;text-align:center;">Listado de certificacion</h2>
                        </div>
                    </div>
                    <div class="row">
                        <form action="{{ url('seguimiento/certificacion/listarpazysalvo') }}" method="GET">
                            <span style="width:10em; margin-right: 16px; margin-left: 5px;border-radius:5px;" class="input-group-addon pull-right btn btn-info"><a style="color:white;" href="<?php echo url('seguimiento/certificacion/listarpazysalvo');?>">Limpiar filtro</a></span>
                            <input style="border-radius:5px;width:5em; margin-right: 3px; cursor:pointer; margin-left: 5px;" type='submit'class="input-group-addon btn btn-success pull-right" value="Buscar" class="pull-right">                            
                            <!-- Cuando la persona desee aplicar el filtro de identificacion -->                           
                            <input type='text' class='form-control pull-right' style='width:15em;  margin-right: 3px;' name='valor' id='valor' placeholder='Digite el valor a buscar'>
                            <!-- El valor del filtro (valor)-->                          
                            <select class='form-control pull-right otras_opciones' style="width:15em;display:none;margin-right: 3px;" name='valor' id="option_4" >
                                @foreach($estados as $est)
                                    <option value='{{$est->est_pro_id}}'> {{$est->est_pro_nombre}} </option>                                
                                @endforeach
                            </select>                           
                            <!-- El tipo de flitro (filtro) -->
                            @if($rol == 20)
                                @if($certificado == true)
                                    <a style="margin-left: 10px;" href="descargarcertificados?valor={{$valor}}&filtro={{$filtro}}" class="btn btn-info pull-left btn-xs">Descargar</a>	
                                @endif
                            @endif
                            <select class="form-control pull-right" style="width:15em;  margin-right: 3px;" name="filtro" id="filtro" data-filtro="{{$filtro}}" data-valor="{{$valor}}" required>
                                <option value="">Seleccione el filtro</option>                                                          
                                <option value="1">Identificacion</option>                               
                                <option value="2">Nombre</option>                               
                                <option value="3">Ficha</option>    
                                @if($rol == 20)
                                <option value="4">Estado</option> 
                                @endif
                            </select>                            
                            <input type="hidden" name="_token" value="<?php echo csrf_token();?>">
                        </form>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered table-responsive">
                            <thead class="thead-inverse">
                                <tr>                          
                                    <th style="text-align: center;">#</th>                 
                                    <th style="text-align: center;">Numero de documento</th>      
                                    <th style="text-align: center;">Nombre</th>        
                                    <th style="text-align: center;">Ciudad</th>                  
                                    <th style="text-align: center;">Ficha</th>                  
                                    <th style="text-align: center;">Programa</th> 
                                    <th style="text-align: center;">Nivel</th> 
                                    <th style="text-align: center;">Estado</th>                    
                                    @if($rol == 20 or $rol == 3 or $rol == 2 or $rol == 19 or $rol == 22 or $rol == 23)
                                    <th colspan="3" style="text-align: center;">Acciones</th>  
                                    @endif                                    
                                </tr>                       
                            </thead>
                            
                            <tbody>  
                                <?php $c = 1; ?>
                                <?php $inicioContador = $contador; ?>               
                                @foreach ($certificado as $cer)
                                <!-- Rol de instructor lider de etapa prodcutiva -->
                                @if($rol == 2 and $cer->cer_estado == 1 or $rol == 2 and $cer->cer_estado == 2)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 2)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif 
                                        <!-- Rol de instructor -->
                                        <!-- Aprobado instructor -->
                                        @if($rol == 2 and $cer->cer_estado == 1)                  
                                            <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                            <td id="aproins" style="text-align:center;"><input id="aprobar" data-fil="{{$c}}" data-estado="{{$cer->cer_estado}}" data-id="{{$cer->cer_id}}" data-url="estado1" type="submit" value="Aprobar" class="btn btn-success"> </td>                                                                       
                                            <td id="rechains" style="text-align:center;"><input type="submit" id="rechazar1" data-id="{{$cer->cer_id}}" data-estado="{{$cer->cer_estado}}" value="Rechazar" class="btn btn-danger"> </td>                                            
                                        @endif      
                                        <!-- Reaprobar instructor -->
                                        @if($rol == 2 and $cer->cer_estado == 2)                                            
                                            <form action="{{url('seguimiento/certificacion/volveraprobar1')}}" method="post">
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">  
                                                <input type="hidden" name="id" value="{{$cer->cer_id}}">
                                            <td style="text-align:center;"><input onclick="return confirm('Estas seguro que deseas aprobar este aprendiz?');" type="submit" value="Reaprobar" class="btn btn-info"> </td>
                                            </form>                                     
                                        @endif  
                                        </td>
                                    </tr>     
                                    <?php $c++; ?>          
                                @endif   
                                <!-- Rol de coordinador -->
                                @if($rol == 3 and $cer->cer_estado == 3 or $rol == 3 and $cer->cer_estado == 8)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 20 or $rol == 2 or $rol == 3 or $rol == 22 or $rol == 19 or $rol == 23)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif 
                                        <!-- Rol de coordinador -->
                                        <!-- Aprobado coordinador -->
                                        @if($rol == 3 and $cer->cer_estado == 3)             
                                            <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                            <td id="aproins" style="text-align:center;"><input id="aprobar" data-fil="{{$c}}" data-estado="{{$cer->cer_estado}}" data-id="{{$cer->cer_id}}" data-url="estado2" type="submit" value="Aprobar" class="btn btn-success"> </td>                                                                
                                            <td id="rechains" style="text-align:center;"><input type="submit" id="rechazar1" data-id="{{$cer->cer_id}}" data-estado="{{$cer->cer_estado}}" value="Rechazar" class="btn btn-danger"> </td>                                            
                                        @endif      
                                        <!-- Reaprobar coordinador -->
                                        @if($rol == 3 and $cer->cer_estado == 8)                                            
                                            <form action="{{url('seguimiento/certificacion/volveraprobar2')}}" method="post">
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">  
                                                <input type="hidden" name="id" value="{{$cer->cer_id}}">
                                            <td style="text-align:center;"><input onclick="return confirm('Estas seguro que deseas aprobar este aprendiz?');" type="submit" value="Reaprobar" class="btn btn-info"> </td>
                                            </form>                                     
                                        @endif                                             
                                        </td>
                                    </tr>     
                                    <?php $c++; ?> 
                                @endif 
                                <!-- Rol de biblioteca -->
                                @if($rol == 22 and $cer->cer_estado == 4 or $rol == 22 and $cer->cer_estado == 9)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 20 or $rol == 2 or $rol == 3 or $rol == 22 or $rol == 19 or $rol == 23)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif
                                        <!-- Rol de biblioteca -->
                                        <!-- Aprobado biblioteca -->
                                        @if($rol == 22 and $cer->cer_estado == 4)                                            
                                            <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                            <td id="aproins" style="text-align:center;"><input id="aprobar" data-fil="{{$c}}" data-estado="{{$cer->cer_estado}}" data-id="{{$cer->cer_id}}" data-url="estado3" type="submit" value="Aprobar" class="btn btn-success"> </td>                                                  
                                            <td id="rechains" style="text-align:center;"><input type="submit" id="rechazar1" data-id="{{$cer->cer_id}}" data-estado="{{$cer->cer_estado}}" value="Rechazar" class="btn btn-danger"> </td>                                            
                                        @endif      
                                        <!-- Reaprobar biblioteca -->
                                        @if($rol == 22 and $cer->cer_estado == 9)                                            
                                            <form action="{{url('seguimiento/certificacion/volveraprobar3')}}" method="post">
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">  
                                                <input type="hidden" name="id" value="{{$cer->cer_id}}">
                                            <td style="text-align:center;"><input onclick="return confirm('Estas seguro que deseas aprobar este aprendiz?');" type="submit" value="Reaprobar" class="btn btn-info"> </td>
                                            </form>                                     
                                        @endif         
                                        </td>
                                    </tr>     
                                    <?php $c++; ?> 
                                @endif  
                                <!-- Rol de Bienestar -->    
                                @if($rol == 19 and $cer->cer_estado == 6 or $rol == 22 and $cer->cer_estado == 10)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 20 or $rol == 2 or $rol == 3 or $rol == 22 or $rol == 19 or $rol == 23)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif
                                        <!-- Rol de Bienestar -->  
                                        <!-- Aprobado Bienestar -->
                                        @if($rol == 19 and $cer->cer_estado == 6)                                            
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                                <td id="aproins" style="text-align:center;"><input id="aprobar" data-fil="{{$c}}" data-estado="{{$cer->cer_estado}}" data-id="{{$cer->cer_id}}" data-url="estado4" type="submit" value="Aprobar" class="btn btn-success"> </td>                                                                                     
                                            <td id="rechains" style="text-align:center;"><input type="submit" id="rechazar1" data-id="{{$cer->cer_id}}" data-estado="{{$cer->cer_estado}}" value="Rechazar" class="btn btn-danger"> </td>                                            
                                        @endif      
                                        <!-- Reaprobar Bienestar -->
                                        @if($rol == 19 and $cer->cer_estado == 10)                                            
                                            <form action="{{url('seguimiento/certificacion/volveraprobar4')}}" method="post">
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">  
                                                <input type="hidden" name="id" value="{{$cer->cer_id}}">
                                            <td style="text-align:center;"><input onclick="return confirm('Estas seguro que deseas aprobar este aprendiz?');" type="submit" value="Reaprobar" class="btn btn-info"> </td>
                                            </form>                                     
                                        @endif       
                                        </td>
                                    </tr>     
                                    <?php $c++; ?> 
                                @endif
                                <!-- Rol de estefani -->
                                @if($rol == 20)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 20 or $rol == 2 or $rol == 3 or $rol == 22 or $rol == 19 or $rol == 23)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif
                                        </td>
                                    </tr>     
                                    <?php $c++; ?> 
                                @endif
                                <!-- Rol de certificacion -->
                                @if($rol == 23 and $cer->cer_estado == 7 or $rol == 23 and $cer->cer_estado == 11 or $rol == 23 and $cer->cer_estado == 5)
                                    <tr>                         
                                        <td >{{ $c }}</td>                            
                                        <td >{{ $cer->cer_numero_documento }}</td>                            
                                        <td>{{ $cer->cer_nombre }}</td>                            
                                        <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                        <td>{{ $cer->cer_ficha }}</td>                            
                                        <td>{{ $cer->prog_nombre }}</td> 
                                        <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                        @if($cer->cer_estado == 1)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 3)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 4)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 6)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 7)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        @if($cer->cer_estado == 5)                        
                                        <td id="estado_{{$c}}"><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                        @endif
                                        <td style="text-align: center;">                
                                        @if($rol == 20 or $rol == 2 or $rol == 3 or $rol == 22 or $rol == 19 or $rol == 23)<a id="modal" data-fecha-solicitud="{{$cer->cer_fecha_solicitud}}" data-fecha-aprobado="{{$cer->cer_fecha_aprobado}}" data-fecha-certificado="{{$cer->cer_fecha_certificado}}" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> 
                                        @endif
                                        <!-- Rol de certificacion -->  
                                        <!-- Aprobado educativa -->
                                        @if($rol == 23 and $cer->cer_estado == 7)                                            
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                                <td id="aproins" style="text-align:center;"><input id="aprobar" data-fil="{{$c}}" data-estado="{{$cer->cer_estado}}" data-id="{{$cer->cer_id}}" data-url="estado5" type="submit" value="Aprobar" class="btn btn-success"> </td>                                                      
                                            <td id="rechains" style="text-align:center;"><input type="submit" id="rechazar1" data-id="{{$cer->cer_id}}" data-estado="{{$cer->cer_estado}}" value="Rechazar" class="btn btn-danger"> </td>                                            
                                        @endif      
                                        <!-- Reaprobar educativa -->
                                        @if($rol == 23 and $cer->cer_estado == 11)                                            
                                            <form action="{{url('seguimiento/certificacion/volveraprobar5')}}" method="post">
                                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">  
                                                <input type="hidden" name="id" value="{{$cer->cer_id}}">
                                            <td style="text-align:center;"><input onclick="return confirm('Estas seguro que deseas aprobar este aprendiz?');" type="submit" value="Reaprobar" class="btn btn-info"> </td>
                                            </form>                                     
                                        @endif       
                                        </td>
                                    </tr>     
                                    <?php $c++; ?> 
                                @endif
                                @endforeach
                            </tbody>                            
                        </table>    
                        </div>
                    </div>
			    </div>
            </div>
        </div>
    </div>
</div>
    @if($cantidadPaginas > 1)
        @if($cantidadPaginas <= 10)
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if($cantidadPaginas > 1 )
                        <small style="float:left;">
                            Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorProyectos }} registros
                        </small>
                    @endif
                    @for($i=$cantidadPaginas; $i>0; $i--)
                        <?php
                            $style='';
                            if($i == $pagina){
                                $style=";background:#087b76; color:white;";
                            }
                        ?>

                        <a href="{{ url('seguimiento/certificacion/listarpazysalvo') }}?pagina=<?php echo $i; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&jaiber=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $i }}</button></a>
                        
                    @endfor
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <small style="float:left;">
                        Mostrando {{ $inicioContador }} a {{ --$contador }} de {{ $contadorProyectos }} registros
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
                        <a href="{{ url('seguimiento/certificacion/listarpazysalvo') }}?pagina=<?php echo $cantidadPaginas; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&jaiber=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;{{ $style }}">{{ $cantidadPaginas }}</button></a>
                        <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                    @endif
                    @for($i=10; $i>0; $i--)
                        <?php
                            $style='';
                            if($cantidadInicia == $pagina){
                                $style=";background:#087b76; color:white;";
                            }
                        ?>
                        <a href="{{ url('seguimiento/certificacion/listarpazysalvo') }}?pagina=<?php echo $cantidadInicia; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&valor=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px{{$style}}">{{ $cantidadInicia }}</button></a>
                        <?php $cantidadInicia--; ?>
                    @endfor
                    @if($pagina >= 10)
                        <a href=""><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">...</button></a>
                        <a href="{{ url('seguimiento/certificacion/listarpazysalvo') }}?pagina=1<?php echo $paz_id; ?>&valor=<?php echo $valor; ?>&filtro=<?php echo $filtro; ?>&valor=<?php echo $valor; ?>"><button  style="float:right;border: 1px solid black;margin:0px 1px 0px 0px;">1</button></a> 
                    @endif
                </div>
            </div>
        @endif
    @endif     
    <!-- MODAL EDITAR -->
    <div id="datos" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 1000px; height: auto;">            
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header text-center">
                            <h2>Detalle certificado</h2><br>
                        </div>                        
                    </div>
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>                                         
                                <th style="text-align: center;">Nombre</th>
                                <th style="text-align: center;">Tipo de documento</th>
                                <th style="text-align: center;">Numero de documento</th>     
                                <th style="text-align: center;">Estado civil</th>
                                <th style="text-align: center;">Correo</th>
                                <th style="text-align: center;">Direccion</th>     
                                <th style="text-align: center;">Ciudad</th>                                    
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                           
                                <td><p style="text-align: center;" id="nombre"></p></td>    
                                <td><p style="text-align: center;" id="tipo_documento"></p></td>                        
                                <td><p style="text-align: center;" id="numero_documento"></p></td> 
                                <td><p style="text-align: center;" id="estado"></p></td>                            
                                <td><p style="text-align: center;" id="correo"></p></td>                            
                                <td><p style="text-align: center;" id="direccion"></p></td>                            
                                <td><p style="text-align: center;" id="ciudad"></p></td>                            
                            </tr>                           
                        </tbody>
                    </table>             
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>               
                                <th style="text-align: center;">Departamento</th>                           
                                <th style="text-align: center;">Telefono</th> 
                                <th style="text-align: center;">Documentos</th>                                       
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                             
                                <td><p style="text-align: center;" id="departamento"></p></td>    
                                <td><p style="text-align: center;" id="telefono"></p></td>   
                                <td style="text-align: center;"><a target="_blank" href="https://docs.google.com/spreadsheets/d/1gNbiLPvLB-Iau6LwGeAeGLsDUcdF601ZJFiZpak_lfo/edit?resourcekey#gid=1410338880"> Documentos </a></td>                                                   
                            </tr>                           
                        </tbody>
                    </table>                     
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>               
                                <th style="text-align: center;">Etapa</th>                               
                                <th style="text-align: center;">Programa</th>                                     
                                <th style="text-align: center;">Ficha</th>                                     
                                <th style="text-align: center;">Nivel</th>  
                                <th style="display:none; text-align: center;" id="motivo">Motivo del rechazo</th>                                       
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                             
                                <td><p style="text-align: center;" id="etapa"></p></td>    
                                <td><p style="text-align: center;" id="programa"></p></td>                        
                                <td><p style="text-align: center;" id="ficha"></p></td> 
                                <td><p style="text-align: center;" id="nivel"></p></td>   
                                <td style="display:none; text-align: center;" id="pra"><p id='text_rechazo'></p></td>
                            </tr>                           
                        </tbody>
                    </table>        
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>               
                            <th style="text-align: center;" id="motivo">Fecha solicitud</th>     
                                <th style="text-align: center;" id="fe_apro">Fecha aprobado</th>     
                                <th style="text-align: center;" id="fe_cer">Fecha certificado</th>                                          
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                             
                                <td><p style="text-align: center;" id='text_solicitado'></p></td>  
                                <td><p style="text-align: center;" id='text_aprobado'></p></td>  
                                <td><p style="text-align: center;" id='text_certificado'></p></td>                                                     
                            </tr>                           
                        </tbody>
                    </table>              
                        <div class="modal-footer">
                            <button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div id="rechazo" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 500px; height: auto;">            
            <div class="modal-content">     
                <div id="rechazoinstructor" style="display:none;">
                    <form action="{{url('seguimiento/certificacion/rechazo1')}}" method="post">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h2>Rechazo del certificado</h2><br>
                                </div>                        
                            </div>                                                                                                               
                            <div style="text-align: center;">
                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">                        
                                <input type="hidden" name ="id" id="idmodal" >                        
                                <textarea style="resize: none;" name="rechazo" id="rechaz" cols="60" placeholder="Digite los motivos del rechazo del certificado" rows="3" minlength="4" required></textarea>
                            </div>                                           
                            <div class="modal-footer">
                                <input style="margin-left:0px;" onclick="return confirm('Estas seguro que deseas rechazar este aprendiz?');" value="Enviar" type="submit" class="btn btn-info pull-left"><button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="rechazocoordinacion" style="display:none;">
                    <form action="{{url('seguimiento/certificacion/rechazo2')}}" method="post">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h2>Rechazo del certificado</h2><br>
                                </div>                        
                            </div>                                                                                                               
                            <div style="text-align: center;">
                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">                        
                                <input type="hidden" name="id" id="idcoor" >                        
                                <textarea style="resize: none;" name="rechazo" id="rechaz" cols="60" placeholder="Digite los motivos del rechazo del certificado" rows="3" minlength="4" required></textarea>
                            </div>                                           
                            <div class="modal-footer">
                                <input style="margin-left:0px;" onclick="return confirm('Estas seguro que deseas rechazar este aprendiz?');" value="Enviar" type="submit" class="btn btn-info pull-left"><button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="rechazobiblioteca" style="display:none;">
                    <form action="{{url('seguimiento/certificacion/rechazo3')}}" method="post">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h2>Rechazo del certificado</h2><br>
                                </div>                        
                            </div>                                                                                                               
                            <div style="text-align: center;">
                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">                        
                                <input type="hidden" name ="id" id="idbibli" >                        
                                <textarea style="resize: none;" name="rechazo" id="rechaz" cols="60" placeholder="Digite los motivos del rechazo del certificado" rows="3" minlength="4" required></textarea>
                            </div>                                           
                            <div class="modal-footer">
                                <input style="margin-left:0px;" onclick="return confirm('Estas seguro que deseas rechazar este aprendiz?');" value="Enviar" type="submit" class="btn btn-info pull-left"><button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="rechazobienestar" style="display:none;">
                    <form action="{{url('seguimiento/certificacion/rechazo4')}}" method="post">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h2>Rechazo del certificado</h2><br>
                                </div>                        
                            </div>                                                                                                               
                            <div style="text-align: center;">
                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">                        
                                <input type="hidden" name ="id" id="idbiene" >                        
                                <textarea style="resize: none;" name="rechazo" id="rechaz" cols="60" placeholder="Digite los motivos del rechazo del certificado" rows="3" minlength="4" required></textarea>
                            </div>                                           
                            <div class="modal-footer">
                                <input style="margin-left:0px;" onclick="return confirm('Estas seguro que deseas rechazar este aprendiz?');" value="Enviar" type="submit" class="btn btn-info pull-left"><button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="rechazocertificacion" style="display:none;">
                    <form action="{{url('seguimiento/certificacion/rechazo5')}}" method="post">
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h2>Rechazo del certificado</h2><br>
                                </div>                        
                            </div>                                                                                                               
                            <div style="text-align: center;">
                                <input type="hidden" class="form-control" name="_token" id="_token" value="{{ csrf_token() }}">                        
                                <input type="hidden" name ="id" id="idcer" >                        
                                <textarea style="resize: none;" name="rechazo" id="rechaz" cols="60" placeholder="Digite los motivos del rechazo del certificado" rows="3" minlength="4" required></textarea>
                            </div>                                           
                            <div class="modal-footer">
                                <input style="margin-left:0px;" onclick="return confirm('Estas seguro que deseas rechazar este aprendiz?');" value="Enviar" type="submit" class="btn btn-info pull-left"><button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>      
                </div>
            </div>
        </div>
    </div>
@endsection
@section('plugins-js')
    <script type="text/javascript">
        $(document).ready(function () {
        $(document).on('click','#modal',function () {  
            var nombre= $(this).attr('data-nombre');
            var tipo_documento = $(this).attr('data-tipo-documento'); 
            var numero_documento = $(this).attr('data-numero-documento'); 
            var estado = $(this).attr('data-estado'); 
            var correo = $(this).attr('data-correo'); 
            var direccion = $(this).attr('data-direccion');
            var ciudad = $(this).attr('data-ciudad');
            var departamento = $(this).attr('data-departamento');
            var telefono = $(this).attr('data-telefono');           
            var etapa = $(this).attr('data-etapa');            
            var etapa = $(this).attr('data-etapa');
            var programa = $(this).attr('data-programa'); 
            var ficha = $(this).attr('data-ficha'); 
            var nivel = $(this).attr('data-nivel');             
            var rechazo = $(this).attr('data-rechazo');             
            var solicitado = $(this).attr('data-fecha-solicitud');             
            var aprobado = $(this).attr('data-fecha-aprobado');             
            var certificado = $(this).attr('data-fecha-certificado');             
            $("#nombre").text(nombre);
            $("#tipo_documento").text(tipo_documento);
            $("#numero_documento").text(numero_documento);
            $("#estado").text(estado);
            $("#correo").text(correo);
            $("#direccion").text(direccion);
            $("#ciudad").text(ciudad);
            $("#departamento").text(departamento);
            $("#telefono").text(telefono);          
            $("#etapa").text(etapa);
            $("#programa").text(programa);
            $("#ficha").text(ficha);
            $("#nivel").text(nivel);            
            $("#text_rechazo").text(rechazo);            
            $("#text_solicitado").text(solicitado);      
            if (aprobado == "") {
                $("#text_aprobado").text('No ha sido aprobado');    
            }else {
                $("#text_aprobado").text(aprobado);   
            }
            if (certificado == "") {
                $("#text_certificado").text('No ha sido certificado');      
            }else {
                $("#text_certificado").text(certificado);   
            }
            if (nivel == "Tecnologo") {
                $("#titu").show(); 
                $("#pre").show(); 
                $("#pru").text();    
            }else{
                $("#titu").hide();
                $("#pre").hide(); 
            }
            if (rechazo === "") {
                $("#motivo").hide();
                $("#pra").hide();                    
            }else{
                $("#motivo").show(); 
                $("#pra").show(); 
                $("#text_rechazo").text(rechazo); 
            }
            $('#datos').modal();      
        });
        $(document).on('click','#rechazar1',function () {  
            var id = $(this).attr('data-id');
			var estado = $(this).attr('data-estado');
            if(estado == 1){
                $('#rechazo').modal(); 
                $('#rechazoinstructor').show();            
                $('#idmodal').val(id);     
            }if(estado == 3){
                $('#rechazo').modal(); 
                $('#rechazocoordinacion').show();    
                $('#idcoor').val(id);  
            }
            if(estado == 4){
                $('#rechazo').modal();
                $('#rechazobiblioteca').show();                          
                $('#idbibli').val(id);         
            }
            if(estado == 6){
                $('#rechazo').modal(); 
                $('#rechazobienestar').show();       
                $('#idbiene').val(id);          
            }
            if(estado == 7){
                $('#rechazo').modal();
                $('#rechazocertificacion').show();                             
                $('#idcer').val(id);      
            }     
        });
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
        $(document).on('click','#aprobar',function(){
            var result = confirm("Estas seguro de aprobar este aprendiz?");
            if (result) {
                var url= $(this).attr('data-url');
                var estado = $(this).attr('data-estado');    
                var id = $(this).attr('data-id');  
                var _token = $('#_token').val();
                var fil = $(this).attr('data-fil');    
                $.ajax({
                    url: url, 
                    type: "POST",
                    data: "id="+id+"&_token="+_token,
                    success: function(data){
                        if (estado == 1) {
                            $("#estado_"+fil+"").html("<p class='tag tag-success' style='text-align: center;'>Aprobado instructor</p>");
                            $("#aproins").remove();
                            $("#rechains").remove();
                        }
                        else if(estado == 3){
                            $("#estado_"+fil+"").html("<p class='tag tag-primary' style='text-align: center;'>Aprobado coordinacion</p>");
                            $("#aproins").remove();
                            $("#rechains").remove();
                        }
                        else if(estado == 4){
                            $("#estado_"+fil+"").html("<p class='tag tag-muted' style='text-align: center;'>Aprobado biblioteca</p>");
                            $("#aproins").remove();
                            $("#rechains").remove();
                        }
                        else if(estado == 6){
                            $("#estado_"+fil+"").html("<p class='tag tag-warning' style='text-align: center;'>Aprobado bienestar</p>");
                            $("#aproins").remove();
                            $("#rechains").remove();
                        }
                        else if(estado == 7){
                            $("#estado_"+fil+"").html("<p class='tag tag-white' style='text-align: center;'>Certificado</p>");
                            $("#aproins").remove();
                            $("#rechains").remove();
                        }       
                    }
                });
            }else{
                return false;
            }
        });
    });
    </script>
@endsection