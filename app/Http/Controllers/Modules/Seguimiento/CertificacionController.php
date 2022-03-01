<?php 
namespace App\Http\Controllers\Modules\Seguimiento;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Modules\Seguimiento\SepParticipante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;

class CertificacionController extends Controller {
	public function __construct(){
		$this->middleware('auth');
		$this->middleware('control_roles');
	}
	public function getReporte(){
		return view('Modules.Seguimiento.Certificacion.reporte');
	}
	public function getIndex(){
		$sql = '
			select *
			from 	sep_participante par, sep_matricula mat
			where 	par.par_identificacion = mat.par_identificacion
			and 	rol_id = 1 order by par_nombres limit 1000,10 ';
		$aprendices = DB::select($sql);
		return view('Modules.Seguimiento.Certificacion.index', compact('aprendices'));
	}
	public function getDescargar(){
		$ruta = public_path() . '/Modules/Seguimiento/Educativa/certificacion/paz.xlsx';
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        return response()->download($ruta);
	}
	public function seguridad($array){
		// Quitamos los simbolos no permitidos de cada variable recibida, 
		// para evitar ataques XSS e Inyección SQL
		$caractereNoPremitidos = array('(',')','&gt;','&lt;','javascript','"',"'",'\\','/','<','>','=',';',':','--');
		$array = str_replace($caractereNoPremitidos,'',$array);
		return	$array;
	}
	public function getPazysalvo() {
		/* Consultamos la identificacion de la persona que inicia */
		$rol = \Auth::user()->participante->par_identificacion;
		/* Extremos esos datos de la persona si existen */
		$sql='select * from sep_certificado where cer_numero_documento="'.$rol.'" ';		
		$certificado = DB::select($sql);
		foreach($certificado as $cer){
			$ficha = $cer->cer_ficha;
		}
		$sql= 'select fic_numero from sep_matricula where par_identificacion ="'.$rol.'" and mat_id = (select MAX(mat_id) from sep_matricula where par_identificacion ="'.$rol.'")';
		$rolpro= DB::select($sql);
		foreach($rolpro as $rol){
			$rolpro = $rol->fic_numero;
		}
		if($certificado == false){
			return view('Modules.Seguimiento.Certificacion.indexpaz',  compact('rol')); 
		}			
		else{
			if ($rolpro == $ficha) {
				return redirect('seguimiento/certificacion/seguimiento');
			}else{
				return view('Modules.Seguimiento.Certificacion.indexpaz',  compact('rol')); 
			}
		}
    }
	public function postAprobarpaz() {
        extract($_POST);
		$hoy = date("j-n-Y");  
		// Reglas para validar los direfentes campos
		$reglas = Array(
			'nombre' => 'required|min:5',
			'tipo' => 'required',
			'numero' => 'required',
			'estado' => 'required',
			'correo' => 'email|required',
			'confirm_correo' => 'required',
			'direccion' => 'required|min:5', 
			'ciudad' => 'required|min:2', 
			'departamento' => 'required|min:2', 
			'telefono' => 'required|min:5', 
			'etapa' => 'required', 
			"diligenciar" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [
			'nombre.required' => 'Es obligatorio llenar el nombre y no contenga numeros',
			'tipo.required' => 'Es obligatorio marcar un tipo de documento',
			'numero.required' => 'El campo n&uacute;mero de identificaci&oacute;n es obligatorio',
			'estado.required' => 'Es obligatorio marcar un estado civil',
			'correo.required' => 'Es obligatorio marcar un correo',
			'confirm_correo.required' => 'Es obligatorio confirmar el correo y que coinsidan',
			'direccion.required' => 'Es obligatorio colocar tu direccion de vivienda',
			'ciudad.required' => 'Es obligatorio colocar tu ciudad',
			'departamento.required' => 'Es obligatorio colocar tu departamento',
			'telefono.required' => 'Es obligatorio colocar tu telefono',
			'etapa.required' => 'Es obligatorio marcar tu etapa productiva',
			'diligenciar.required' => 'Es obligatorio marcar si y llena el formulario APE'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}

		$rol = \Auth::user()->participante->par_identificacion;		
		
		$sql= 'select fic_numero from sep_matricula where par_identificacion ="'.$rol.'" and mat_id = (select MAX(mat_id) from sep_matricula where par_identificacion ="'.$rol.'")';
		$rolpro= DB::select($sql);		
		foreach($rolpro as $pro){		
			$ficha = $pro->fic_numero;
			$sql = 'select prog_codigo from sep_ficha where fic_numero ="'.$ficha.'" ';
			$programa = DB::select($sql);
			foreach($programa as $pra){
				$codigopro = $pra->prog_codigo;
				$sql='select niv_for_id from sep_programa where prog_codigo ="'.$codigopro.'" ';
				$nivel = DB::select($sql);
				/* dd($nivel,$programa,$rolpro);  */
				foreach($nivel as $ni){
				$nivel = $ni->niv_for_id;

				$sql='select * from sep_certificado where cer_numero_documento="'.$rol.'" ';
				$certificado = DB::select($sql);

					if($certificado == false){
						if($rol === $numero){
							if($correo === $confirm_correo and $diligenciar === "Si"){
								$sql = 'insert into	sep_certificado(cer_nombre, cer_tipo_documento, cer_numero_documento, cer_estado_civil, cer_correo, cer_direccion, cer_ciudad, cer_departamento, cer_telefono, cer_etapa, cer_prog_codigo, cer_ficha, cer_nivel, cer_estado, cer_fecha_solicitud)values("'.$nombre.'","'.$tipo.'","'.$numero.'","'.$estado.'","'.$correo.'","'.$direccion.'","'.$ciudad.'","'.$departamento.'","'.$telefono.'","'.$etapa.'","'.$codigopro.'","'.$ficha.'","'.$nivel.'","1","'.$hoy.'")';
								DB::insert($sql);		
								return redirect('seguimiento/certificacion/seguimiento')->with('type_message', 'success')->with('mensaje', trans('Se ha registrado correctamente la solicitud de certificacion este a la espera que el funcionario se comunique con usted'));
							}
							elseif($correo === $confirm_correo and $diligenciar === "No"){								
								return redirect()->back()
									->withErrors("No se ha podido enviar el formulario ya que no completaste el formulario de la agencia publica de empleo")->withInput();
							}
							else {								
								return redirect()->back()
									->withErrors("No se ha podido enviar el formulario ya que hay un error en el correo electronico")->withInput();
							}
						}else{						
							return redirect()->back()
								->withErrors("Porfavor diligencia correctamente tu numero de identificacion")->withInput();							
						}
					}
					else{						
						return redirect()->back()
								->withErrors("No se ha podido enviar el formulario ya que ya has enviado este formulario")->withInput();	
					}
				}
			}
		}
    }
	public function getListarpazysalvo($valor=false , $filtro=false) {
		extract($_GET);
		$rolidentificacion = \Auth::user()->participante->par_identificacion;
		$sql = "select * from sep_estado_procesos where est_pro_id in ('1','2','3','4','5','6','7','8','9','10','11') ";
		$estados = DB::select($sql);			
		$rol = \Auth::user()->participante->rol_id;
		$registroPorPagina = 10;	

        $limit = $registroPorPagina ;
        if(isset($pagina)){
            $hubicacionPagina = $registroPorPagina*($pagina-1);
            $limit = $hubicacionPagina.','.$registroPorPagina;
        }else{
            $pagina = 1;
        }
		$concatenarProyectoprimeraSql = '';
        $concatenarProyectosegundaSQL = '';
        if(isset($_GET['cer_id'])){
            $concatenarProyectoprimeraSql = ' and cer.cer_id = "'.$cer_id.'" ';
            $concatenarProyectosegundaSQL = ' and cer.cer_id = "'.$cer_id.'" ';
        }else{
            $cer_id = '';
        }
		if($valor==""){
			if($rol == 2){
				$sql = 'select  * from    sep_certificado cer, sep_seguimiento_productiva pro, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv  where   cer.cer_numero_documento = pro.par_identificacion_aprendiz and cer.cer_nivel=niv.niv_for_id and cer.cer_estado=est.est_pro_id and prog_codigo=cer_prog_codigo and pro.par_identificacion_responsable = "'.$rolidentificacion.'" order   by cer_id asc  limit '.$limit;
				// Consulta del tipo de filtro
				$sqlContador = 'select count(cer_estado) as total from sep_certificado cer, sep_seguimiento_productiva pro where cer_numero_documento = pro.par_identificacion_aprendiz';
			}
			else{
				$sql = 'select  * from    sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv where   cer.cer_nivel=niv.niv_for_id and cer.cer_estado=est.est_pro_id and prog_codigo=cer_prog_codigo	order   by cer_id asc  limit '.$limit;
				// Consulta del tipo de filtro
				if ($rol == 3) {
					$sqlContador = '
					select  count(cer_estado) as total 
					from  sep_certificado where cer_estado = 3 or cer_estado = 8';
				}elseif($rol == 22){
					$sqlContador = '
					select  count(cer_estado) as total 
					from  sep_certificado where cer_estado = 4 or cer_estado = 9';
				}elseif($rol == 19){
					$sqlContador = '
					select  count(cer_estado) as total 
					from  sep_certificado where cer_estado = 6 or cer_estado = 10';
				}elseif($rol == 23){
					$sqlContador = '
					select  count(cer_estado) as total 
					from  sep_certificado where cer_estado = 7 or cer_estado = 11 or cer_estado = 5';
				}
				else{
					$sqlContador = '
				select  count(cer_estado) as total 
				from  sep_certificado';
				}
			}
        }else{
            if($filtro == 4){
                $busqueda=" cer.cer_estado = '$valor'";
            }elseif ($filtro == 1) {
				$busqueda=" cer.cer_numero_documento like '%$valor%'";
			}elseif ($filtro == 2) {
				$busqueda=" cer.cer_nombre like '%$valor%'";
			}elseif ($filtro == 3) {
				$busqueda=" cer.cer_ficha like '%$valor%'";
			}elseif ($filtro == 0) {
				$busqueda=" cer.cer_ficha like '%$valor%'";
			}
			if ($rol == 2) {
				$sql = "select * from sep_certificado cer, sep_seguimiento_productiva pro, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv 
                    where cer.cer_numero_documento = pro.par_identificacion_aprendiz and cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.") and pro.par_identificacion_responsable = ".$rolidentificacion." order by cer_id asc limit ".$limit;
				$sqlContador = "select  count(cer_estado) as total from  sep_certificado cer, sep_seguimiento_productiva pro, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv	where cer.cer_numero_documento = pro.par_identificacion_aprendiz and cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.") and pro.par_identificacion_responsable = ".$rolidentificacion;
			}
			else{
            	$sql = "select * from sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv 
                    where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.")
                    order by cer_id asc
                    limit ".$limit;
				$sqlContador = "select  count(cer_estado) as total from  sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv	where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.")";
			}
        }
		$certificado= DB::select($sql);
       /*  dd($certificado); */
        $proyectosContador = DB::select($sqlContador);
		$contadorProyectos = $proyectosContador[0]->total;
        $cantidadPaginas = ceil($contadorProyectos/$registroPorPagina);
        $contador = (($pagina-1)*$registroPorPagina)+1;

        return view('Modules.Seguimiento.Certificacion.listarindexpaz', compact('certificado','rol','contadorProyectos','cantidadPaginas','contador','pagina','filtro','valor','estados','nivel')); 
    }	
	/* Estados reaprobados */
	public function postVolveraprobar1() {
		extract($_POST);
		$hoy = date("j-n-Y");  
		$sql = 'update sep_certificado set cer_estado= 3, cer_rechazo = "", cer_fecha_aprobado= "'.$hoy.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postVolveraprobar2() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 4, cer_rechazo = ""  where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postVolveraprobar3() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 6, cer_rechazo = ""  where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postVolveraprobar4() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 7, cer_rechazo = ""  where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postVolveraprobar5() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 5, cer_rechazo = ""  where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	/* Estados aprobados */
	public function postEstado1() {
		extract($_POST);
		$hoy = date("j-n-Y");  
		$sql = 'update sep_certificado set cer_estado= 3, cer_fecha_aprobado= "'.$hoy.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);
    }
	public function postEstado2() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 4 where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
    }
	public function postEstado3() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 6 where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
    }
	public function postEstado4() {
		extract($_POST);
		$sql = 'update sep_certificado set cer_estado= 7 where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
    }
	public function postEstado5() {
		extract($_POST);
		$hoy = date("j-n-Y");  
		$sql = 'update sep_certificado set cer_estado= 5, cer_fecha_certificado= "'.$hoy.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
    }
	/* Estados rechazados */
	public function postRechazo1() {
		extract($_POST);
		$reglas = Array(			
			"rechazo" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [			
			'rechazo.required' => 'Es obligatorio llenar el motivo del rechazo'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}
		$sql = 'update sep_certificado set cer_estado= 2, cer_rechazo="'.$rechazo.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postRechazo2() {
		extract($_POST);
		$reglas = Array(			
			"rechazo" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [			
			'rechazo.required' => 'Es obligatorio llenar el motivo del rechazo'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}
		$sql = 'update sep_certificado set cer_estado= 8, cer_rechazo="'.$rechazo.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postRechazo3() {
		extract($_POST);
		$reglas = Array(			
			"rechazo" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [			
			'rechazo.required' => 'Es obligatorio llenar el motivo del rechazo'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}
		$sql = 'update sep_certificado set cer_estado= 9, cer_rechazo="'.$rechazo.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postRechazo4() {
		extract($_POST);
		$reglas = Array(			
			"rechazo" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [			
			'rechazo.required' => 'Es obligatorio llenar el motivo del rechazo'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}
		$sql = 'update sep_certificado set cer_estado= 10, cer_rechazo="'.$rechazo.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function postRechazo5() {
		extract($_POST);
		$reglas = Array(			
			"rechazo" => "required"
		);
		// Mensajes de error para los diferentes campos
		$messages = [			
			'rechazo.required' => 'Es obligatorio llenar el motivo del rechazo'
		];

		$validacion = Validator::make($_POST, $reglas, $messages);
				
		if($validacion->fails()){
			return redirect()->back()
					->withErrors($validacion->errors())->withInput();
		}
		$sql = 'update sep_certificado set cer_estado= 11, cer_rechazo="'.$rechazo.'" where cer_id="'.$id.'"';
		$certificado = DB::update($sql);		
        return redirect('seguimiento/certificacion/listarpazysalvo');
    }
	public function getSeguimiento() {
		extract($_GET);
		$rol = \Auth::user()->participante->par_identificacion;
		$sql= 'select * from sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv 
		where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and cer_numero_documento='.$rol.'';
		$certificado= DB::select($sql);
		return view('Modules.Seguimiento.Certificacion.seguimiento', compact('certificado')); 
    }
	public function getDescargarcertificados($valor=false , $filtro=false)
	{
		extract($_GET);
		$rol = \Auth::user()->participante->rol_id;
		$rolidentificacion = \Auth::user()->participante->par_identificacion;
		if($valor==""){
			if($rol == 2){
				$sql = 'select  * from sep_certificado cer, sep_seguimiento_productiva pro, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv  where cer.cer_numero_documento = pro.par_identificacion_aprendiz and cer.cer_nivel=niv.niv_for_id and cer.cer_estado=est.est_pro_id and prog_codigo=cer_prog_codigo and pro.par_identificacion_responsable = '.$rolidentificacion;
			}else{
				$sql = 'select * from sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id ';
			}
        }else{
            if($filtro == 4){
                $busqueda=" cer.cer_estado = '$valor'";
            }elseif ($filtro == 1) {
				$busqueda=" cer.cer_numero_documento like '%$valor%'";
			}elseif ($filtro == 2) {
				$busqueda=" cer.cer_nombre like '%$valor%'";
			}elseif ($filtro == 3) {
				$busqueda=" cer.cer_ficha like '%$valor%'";
			}elseif ($filtro == 0) {
				$busqueda=" cer.cer_ficha like '%$valor%'";
			}
			if ($rol == 2) {
				$sql = "select * from sep_certificado cer, sep_estado_procesos est, sep_seguimiento_productiva pro, sep_programa, sep_nivel_formacion niv where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.") and cer.cer_numero_documento = pro.par_identificacion_aprendiz and pro.par_identificacion_responsable = ".$rolidentificacion;
			}
			else{
            	$sql = "select * from sep_certificado cer, sep_estado_procesos est, sep_programa, sep_nivel_formacion niv where cer.cer_nivel=niv.niv_for_id and prog_codigo=cer_prog_codigo and cer.cer_estado=est.est_pro_id and (".$busqueda.")";
			}
        }		
		$certificados =  DB::select($sql); 
			$filas="";					
				foreach ($certificados as $cer){
					$rechazo= $cer->cer_rechazo;
				$filas.="
				<tr>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_numero_documento)."</td>
				<td style='text-align: center;'>".utf8_decode($cer->cer_nombre)."</td>
				<td style='text-align: center;'>".utf8_decode($cer->cer_tipo_documento)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_estado_civil)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_correo)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_direccion)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_ciudad)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_departamento)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_telefono)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_etapa)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->prog_nombre)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->cer_ficha)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->niv_for_nombre)."</td>		
				<td style='text-align: center;'>".utf8_decode($cer->est_pro_nombre)."</td>";
				if (isset($cer->cer_rechazo)) {
					$filas.="<td style='text-align: center;'>".utf8_decode($cer->cer_rechazo)."</td>";	
				}
				else{
					$filas.="<td style='text-align: center;'> No aplica </td>";
				}
			}				
			$tabla = '
			<style>
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				font-family:Arial;
			}
			#campos{ background:#5e83ba; color:white; }
			</style>						
			<table cellspacing="0" cellpadding="0">
			<tr id="campos"><th>Nombre</th><th>Tipo de documento</th><th>Numero de documento</th><th>Estado civil</th>
			<th>Correo</th><th>Direccion</th><th>Ciudad</th><th>Departamento</th><th>Telefono</th><th>Etapa</th><th>Programa</th><th>Ficha</th><th>Nivel de formacion</th><th>Estado</th>';
		if(isset($rechazo)){
			$tabla.= '<th>Motivo del rechazo</th>';
		}
		else{
			$tabla.= '<th>Motivo del rechazo</th>';
		}
			$tabla.=$filas;
		//Hacemos que se exporte en formato xls
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=Certificados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $tabla;  
	} 
	/* echo "SELECT se_ap.id, se_ap.par_identificacion, par.par_nombres, bene.ben_sen_nombre, se_ap.fecha_inicio, se_ap.fecha_fin, se_ap.observacion, IF(se_ap.estado = 3,'vencido', if(se_ap.estado=2,'inactivo','activo')) as estado FROM sep_beneficios_sena bene, sep_beneficios_sena_aprendiz se_ap, sep_participante par WHERE se_ap.par_identificacion=par.par_identificacion and bene.id=se_ap.beneficio_sena_id"; */
}