@extends('templates.devoops')

@section('content')

{!! getHeaderMod('seguimiento','Paz y salvo') !!}

<div class="row">
    @if (session('mensaje'))
        <div class="alert alert-success text-center msg" style="width: 1000px; margin: 0 auto;" id="message">
            <strong>{{ session('mensaje') }}</strong>
        </div>
    @endif
    <div class="box-content">
		<div class="col-xs-12 col-sm-12">
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
				</div>
				<div class="box-content">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12" style="text-align: center;">
							<h2 style="font-family: cursive;font-weight: bold;">Seguimiento de certificacion</h2>
						</div><br><br>
                    <div>
                    
                    
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>                                          
                                <th style="text-align: center;">Numero de documento</th>      
                                <th style="text-align: center;">Nombre</th>        
                                <th style="text-align: center;">Ciudad</th>                  
                                <th style="text-align: center;">Ficha</th>                  
                                <th style="text-align: center;">Programa</th> 
                                <th style="text-align: center;">Nivel</th> 
                                <th style="text-align: center;">Estado</th>   
                                <th style="text-align: center;">Acciones</th>   
                            </tr>                       
                        </thead>
                        <tbody>               
                            @foreach ($certificado as $cer)
                                <tr>                         
                                    <td>{{ $cer->cer_numero_documento }}</td>                            
                                    <td>{{ $cer->cer_nombre }}</td>                            
                                    <td style="text-align: center;">{{ $cer->cer_ciudad }}</td>                            
                                    <td>{{ $cer->cer_ficha }}</td>                            
                                    <td>{{ $cer->prog_nombre }}</td> 
                                    <td style="text-align: center;">{{ $cer->niv_for_nombre }}</td>   
                                    @if($cer->cer_estado == 1)                        
                                    <td><p class="tag tag-info" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 2 or $cer->cer_estado == 8 or $cer->cer_estado == 9 or $cer->cer_estado == 10 or $cer->cer_estado == 11)                        
                                    <td><p class="tag tag-danger" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 3)                        
                                    <td><p class="tag tag-success" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 4)                        
                                    <td><p class="tag tag-primary" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 6)                        
                                    <td><p class="tag tag-muted" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 7)                        
                                    <td><p class="tag tag-warning" style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif
                                    @if($cer->cer_estado == 5)                        
                                    <td><p class="tag tag-white " style="text-align: center;">{{ $cer->est_pro_nombre }}</p></td> 
                                    @endif        
                                    <td style="text-align:center"><a id="modal" data-rechazo="{{$cer->cer_rechazo}}" data-nombre="{{$cer->cer_nombre}}" data-tipo-documento="{{$cer->cer_tipo_documento}}" data-numero-documento="{{$cer->cer_numero_documento}}" data-estado="{{$cer->cer_estado_civil}}" data-correo="{{$cer->cer_correo}}" data-direccion="{{$cer->cer_direccion}}" data-ciudad="{{$cer->cer_ciudad}}" data-departamento="{{$cer->cer_departamento}}" data-telefono="{{$cer->cer_telefono}}" data-etapa="{{$cer->cer_etapa}}" data-programa="{{$cer->prog_nombre}}" data-ficha="{{$cer->cer_ficha}}" data-nivel="{{$cer->niv_for_nombre}}" class="text-info" >Detalle </a> </td>                                
                                </tr>                         
                            @endforeach
                        </tbody>
                    </table>
                </div>         
            </div>
        </div> 
    </div> 
</div>
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
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                           
                                <td><p style="text-align: center;" id="nombre"></p></td>    
                                <td><p style="text-align: center;" id="tipo_documento"></p></td>                        
                                <td><p style="text-align: center;" id="numero_documento"></p></td> 
                                <td><p style="text-align: center;" id="estado"></p></td>                            
                                <td><p style="text-align: center;" id="correo"></p></td>                                                      
                            </tr>                           
                        </tbody>
                    </table>             
                    <table class="table table-bordered table-responsive">
                        <thead class="thead-inverse">
                            <tr>               
                                <th style="text-align: center;">Departamento</th>                           
                                <th style="text-align: center;">Telefono</th> 
                                <th style="text-align: center;">Direccion</th>     
                                <th style="text-align: center;">Ciudad</th>                                        
                            </tr>                                               
                        </thead>
                        <tbody>                   
                            <tr>                                                             
                                <td><p style="text-align: center;" id="departamento"></p></td>    
                                <td><p style="text-align: center;" id="telefono"></p></td> 
                                <td><p style="text-align: center;" id="direccion"></p></td>                            
                                <td><p style="text-align: center;" id="ciudad"></p></td>                                                    
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
                        <div class="modal-footer">
                            <button style="margin:0px;" class="btn btn-danger btn-xs" data-dismiss="modal">Cerrar</button>
                        </div>
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
    });
    </script>
@endsection