@extends('templates.devoops')

@section('content')

{!! getHeaderMod('Listar','Paz y salvo') !!}

<div class="row">    
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
                    @include('errors.messages')
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
						<h2 style="font-family: cursive;font-weight: bold;">Formulario de certificacion</h2>
					</div><br><br>                        
                    @if (session('mensaje'))
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                        <div class="alert alert-danger text-center msg" style="width: 700px; margin: 0 auto;" id="message">
                            <strong>{{ session('mensaje') }}</strong>
                        </div>
                    </div>
                    @endif
                    <p style="margin-left: 30px; font-size: 20px;">Recuerde tener los datos actualizados en <a target="_blank" href="http://oferta.senasofiaplus.edu.co/">sofia plus</a></p>
                    <p style="margin-left: 30px; font-size: 20px;">Una vez verificado la actulizacion de los datos llene el siguiente formulario:</p>                  
                    <hr>      
                    <form action="{{ url('seguimiento/certificacion/aprobarpaz') }}" method="post" id="formulario">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Nombre completos (Tal cual esta en el documento de identidad): </label>
                                    <input minlength="5" type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre completo" value="{{old('nombre')}}" >
                                    <span id="valnumerico" style="color:red;display:none;"> No se permite numeros en el nombre </span>
                                    <span id="valnum" style="color:red;display:none;"> No se permite el nombre vacio </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Tipo de documento: </label>
                                    <div class="form-check">
                                        <input class="form-check-input documento" type="radio" name="tipo" id="tipodocumento1" value="Tarjeta" 
                                        @if (old('tipo')=='Tarjeta')
                                        checked
                                        @endif
                                        >
                                        <label class="form-check-label">Tarjeta de identidad</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input documento" type="radio" name="tipo" id="tipodocumento2" value="Cedula" 
                                        @if (old('tipo')=='Cedula')
                                        checked
                                        @endif
                                        >
                                        <label class="form-check-label">Cedula</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input documento" type="radio" name="tipo" id="tipodocumento3" value="Extranjeria" 
                                        @if (old('tipo')=='Extranjeria')
                                        checked
                                        @endif
                                        >
                                        <label class="form-check-label">Cedula de extranjeria</label>
                                    </div>
                                    <span id="tipodocum" style="color:red;display:none;"> Porfavor marca una opcion </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Numero de documento: </label>
                                    <input min="5" type="number" class="form-control" name="numero" id="numerodocumento" placeholder="Numero de documento" value="{{old('numero')}}" >
                                    <span id="numerodocu" style="color:red;display:none;"> Porfavor digita tu numero de documento </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Estado civil</label>
                                    <div class="form-check">
                                        <input class="form-check-input estadocivil" type="radio" name="estado" id="estadocivil1" value="Soltero" 
                                        @if (old('estado')=='Soltero')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Soltero</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input estadocivil" type="radio" name="estado" id="estadocivil2" value="Casado" 
                                        @if (old('estado')=='Casado')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Casado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input estadocivil" type="radio" name="estado" id="estadocivil3" value="Divorciado" 
                                        @if (old('estado')=='Divorciado')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Divorciado</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input estadocivil" type="radio" name="estado" id="estadocivil4" value="Viudo" 
                                        @if (old('estado')=='Viudo')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Viudo</label>
                                    </div>
                                    <span id="estadoci" style="color:red;display:none;"> Porfavor marca una opcion </span>
                                </div>                        
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Correo electronico: </label>
                                    <input minlength="5" type="email" class="form-control" name="correo" id="correo" value="{{old('correo')}}" placeholder="Correo electronico" >
                                    <span id="correoo" style="color:red;display:none;"> Porfavor digita tu correo </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Confirmar correo electronico: </label>
                                    <input minlength="5" type="email" class="form-control" name="confirm_correo" id="confirmcorreo" value="{{old('confirm_correo')}}" placeholder="Confirmar correo electronico" >
                                    <span id="confirmarcorreo" style="color:red;display:none;"> Porfavor confirma tu correo </span>
                                    <span id="iguales" style="color:red;display:none;"> El correo debe ser exactamente igual </span>
                                </div>
                            </div> 
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Dirección de Residencia: </label>
                                    <input minlength="5" type="text" class="form-control" name="direccion" id="direccion" value="{{old('direccion')}}" placeholder="Dirección de Residencia" >
                                    <span id="dire" style="color:red;display:none;"> La direccion no pueder estar vacia </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Ciudad: </label>
                                    <input minlength="3" type="text" class="form-control" name="ciudad" id="ciudad" value="{{old('ciudad')}}" placeholder="Ciudad" >
                                    <span id="ciu" style="color:red;display:none;"> La ciudad no pueder estar vacia </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Departamento: </label>
                                    <input minlength="5" type="text" class="form-control" name="departamento" id="departamento" value="{{old('departamento')}}" placeholder="Departamento" >
                                    <span id="departa" style="color:red;display:none;"> el departamento no pueder estar vacio </span>
                                </div>                                                
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label" >Telefono o Celular: </label>
                                    <input min="5" type="number" class="form-control" name="telefono" id="telefono" value="{{old('telefono')}}" placeholder="Telefono" >
                                    <span id="valtelefono" style="color:red;display:none;"> el telefono no pueder estar vacio </span>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Alternativa de etapa productiva: </label>
                                    <div class="form-check">
                                        <input class="form-check-input etapa" type="radio" name="etapa" id="alternativa" value="Contrato de aprendizaje" 
                                        @if (old('etapa')=='Contrato de aprendizaje')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Contrato de aprendizaje</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input etapa" type="radio" name="etapa" id="alternativa" value="Pasantia" 
                                        @if (old('etapa')=='Pasantia')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Pasantia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input etapa" type="radio" name="etapa" id="alternativa" value="Vinculo laboral" 
                                        @if (old('etapa')=='Vinculo laboral')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Vinculo laboral</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input etapa" type="radio" name="etapa" id="alternativa" value="Proyecto productivo" 
                                        @if (old('etapa')=='Proyecto productivo')
                                        checked
                                        @endif>
                                        <label class="form-label" for="estado">Proyecto productivo</label>
                                    </div>
                                    <span id="valetapa" style="color:red;display:none;"> Porfavor marca una opcion </span>
                                </div>   
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                                    <label class="form-label">Diligenciar Formulario APE<a target="_blank" href="https://forms.gle/tiFbvE365FQa6eX36"> click aqui </a>:</label>
                                    <div class="form-check">
                                        <input class="form-check-input for" type="radio" name="diligenciar" id="si" value="Si" 
                                        @if (old('diligenciar')=='Si')
                                        checked
                                        @endif>
                                        <label class="form-label" for="diligenciar">Se realizo</label>
                                    </div>                                
                                    <div class="form-check">
                                        <div id="mostrar"></div>
                                        <input class="form-check-input for" type="radio" name="diligenciar" id="no" value="No" 
                                        @if (old('diligenciar')=='No')
                                        checked
                                        @endif>
                                        <label class="form-label" for="diligenciar">No se realizo</label>                                                
                                    </div>  
                                    <span id="valfor" style="color:red;display:none;"> Porfavor marca una opcion </span>
                                </div>  
                            </div>
                        </div>
                </div>
                <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align: center;">
                            <h2 style="font-family: cursive;font-weight: bold;">Documentos de soporte</h2>
                        </div>
                    </div>
                    <div class="row" style="font-size: 20px;">
                        <div class="col-md-12" style="margin-left:10px;">
                            <label class="col-lg-12 ">Documento de certificacion: </label><p class="text text-success">Porfavor iniciar sesion con su correo y subir todos los archivos y/o imagenes que se solicitan .</p>                                                                             
                            <a target="_blank" href="https://forms.gle/gJ9oa9PmAqWGxsZg8" style="width: 100px; text-align:center;" class="btn btn-success"> Aqui </a>
                        </div>                                                
                    </div>
                    <div class="row">                        
                        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align: center;">
                            <input type="submit" value="Enviar" class="btn btn-success">    
                            <br><span id="valbutton" style="color:red;display:none;"> Porfavor diligencia correctamente el formulario </span>
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
            $(document).submit(function(e) {
                var nombre = $('#nombre').val().trim();
                var documento = $('#numerodocumento').val().trim();
                var correo = $('#correo').val().trim();
                var confirm_correo = $('#confirmcorreo').val().trim();
                var direccion = $('#direccion').val().trim();
                var ciudad = $('#ciudad').val().trim();
                var departamento = $('#departamento').val().trim();
                var telefono = $('#telefono').val().trim();
                var etapa = $('input[name=etapa]:checked').val();
                var formape = $('input[name=diligenciar]:checked').val();
                if (formape == undefined) {
                    e.preventDefault();
                    $('#valfor').show();
                    $('#valbutton').show();
                }else{
                    $('#valfor').hide();
                }  
                if (etapa == undefined) {
                    e.preventDefault();
                    $('#valetapa').show();
                    $('#valbutton').show();
                }else{
                    $('#valetapa').hide();
                }                
                if (nombre == '') {
                    e.preventDefault();
                    $('#valnum').show();
                    $('#valbutton').show();
                    $('#valnumerico').hide();
                }else if(parseFloat(nombre)){
                    e.preventDefault();
                    $('#valnum').hide();
                    $('#valnumerico').show();
                    $('#valbutton').show();
                }
                if($('#tipodocumento1').is(':checked')){                     
                    $('#tipodocum').hide();
                }else if($('#tipodocumento2').is(':checked')){
                    $('#tipodocum').hide();
                }else if($('#tipodocumento3').is(':checked')){
                    $('#tipodocum').hide();
                }else{
                    e.preventDefault();
                    $('#tipodocum').show();
                    $('#valbutton').show();
                }
                if($('#estadocivil1').is(':checked')){                     
                    $('#estadoci').hide();
                }else if($('#estadocivil2').is(':checked')){
                    $('#estadoci').hide();
                }else if($('#estadocivil3').is(':checked')){
                    $('#estadoci').hide();
                }else if($('#estadocivil4').is(':checked')){
                    $('#estadoci').hide();
                }else{
                    e.preventDefault();
                    $('#estadoci').show();
                    $('#valbutton').show();
                }
                if (documento == '') {
                    e.preventDefault();
                    $('#numerodocu').show();
                }else{
                    $('#numerodocu').hide();
                }
                if (correo == '') {
                    e.preventDefault();
                    $('#correoo').show();
                    $('#valbutton').show();
                }else{
                    $('#correoo').hide();
                }
                if (confirm_correo == '') {
                    e.preventDefault();
                    $('#confirmarcorreo').show();
                    $('#valbutton').show();
                }else if(correo != confirm_correo){
                    e.preventDefault();
                    $('#confirmarcorreo').hide();
                    $('#iguales').show();
                    $('#valbutton').show();
                }else{
                    $('#confirmarcorreo').hide();
                    $('#iguales').hide();
                }
                if (direccion == '') {
                    e.preventDefault();
                    $('#dire').show();
                    $('#valbutton').show();
                }else{
                    $('#dire').hide();
                }
                if (ciudad == '') {
                    e.preventDefault();
                    $('#ciu').show();
                    $('#valbutton').show();
                }else{
                    $('#ciu').hide();
                }
                if (departamento == '') {
                    e.preventDefault();
                    $('#departa').show();
                    $('#valbutton').show();
                }else{
                    $('#departa').hide();
                }
                if (telefono == '') {
                    e.preventDefault();
                    $('#valtelefono').show();
                    $('#valbutton').show();
                }else{
                    $('#valtelefono').hide();
                }
                
            });
            $(document).on('keyup','#nombre',function () {  
                $('#valnumerico').hide();
                $('#valnum').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('keyup','#direccion',function () {  
                $('#dire').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('keyup','#ciudad',function () {  
                $('#ciu').hide();	
                $('#valbutton').hide();
            });
            $(document).on('keyup','#departamento',function () {  
                $('#departa').hide();
                $('#valbutton').hide();	
            });
            $(document).on('keyup','#confirmcorreo',function () {  
                $('#confirmarcorreo').hide();
                $('#iguales').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('keyup','#numerodocumento',function () {  
                $('#numerodocu').hide();
                $('#valbutton').hide();
            }); 
            $(document).on('keyup','#telefono',function () {  
                $('#valtelefono').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('keyup','#correo',function () {  
                $('#correoo').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('click','.estadocivil',function () {  
                $('#estadoci').hide();	
                $('#valbutton').hide();
            }); 
            $(document).on('click','.documento',function () {  
                $('#tipodocum').hide();	
                $('#valbutton').hide();
            });

            $(document).on('click','.etapa',function () {  
                $('#valetapa').hide();	
                $('#valbutton').hide();
            });
            $(document).on('click','.for',function () {  
                $('#valfor').hide();	
                $('#valbutton').hide();
            });
            $("#nombre").bind('keypress',function (event) {  
                var regex = new RegExp("^[a-z]+$+ ");	
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $("#numerodocumento").bind('keypress',function (event) {  
                var regex = new RegExp("^[0-9]+$");	
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $("#telefono").bind('keypress',function (event) {  
                var regex = new RegExp("^[0-9]+$");	
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            

        });
    </script>
@endsection