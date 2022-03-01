<?php

namespace App\Http\Controllers\Modules\Seguimiento;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use \Illuminate\Pagination\LengthAwarePaginator;

// Modelos del modulo usuarios
//use App\Http\Models\Modules\Users\User;

class PracticaController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('control_roles');

    }
	public function getIndex($id = false) {
        $page = Input::get('page', 1);
        $perPage = 40;
        $offset = ($page * $perPage) - $perPage;	                  
		
        if($id!=""){
			   $sql=" 
				select 
					sep_participante.par_identificacion_actual,fic_numero,
					par_nombres,par_apellidos, users.*
				from 
					sep_matricula, sep_participante, users 
				where 
					sep_matricula.par_identificacion = sep_participante.par_identificacion and
					sep_participante.par_identificacion=users.par_identificacion and 
					fic_numero = '$id' and 
					sep_matricula.est_id in(2,10)
					ORDER BY sep_participante.par_nombres ASC";
				
				$sql1="
				select 
					prog_nombre,par_nombres, par_apellidos,sep_ficha.fic_numero,
				   fic_fecha_inicio,fic_fecha_fin,par_telefono,par_correo,
				   par_identificacion_productiva
				from 
					sep_participante,sep_ficha,sep_programa,sep_planeacion_ficha 
				where   
					sep_programa.prog_codigo=sep_ficha.prog_codigo and 
					sep_planeacion_ficha.fic_numero=sep_ficha.fic_numero and
					sep_planeacion_ficha.pla_ins_lider=sep_participante.par_identificacion and
					sep_ficha.fic_numero = '$id' 
					ORDER BY sep_participante.par_nombres ASC";
			}
		else{
				$sql=" select sep_matricula.par_identificacion,fic_numero,par_nombres,par_apellidos, users.*  from sep_matricula, sep_participante, users "
					." where sep_matricula.par_identificacion = sep_participante.par_identificacion and sep_participante.par_identificacion=users.par_identificacion and fic_numero=0  ORDER BY sep_participante.par_nombres ASC";
				$sql1="
				select 
					prog_nombre,par_nombres, par_apellidos,sep_ficha.fic_numero,
					fic_fecha_fin,par_identificacion_productiva
				from 
					sep_participante,sep_ficha,sep_programa,sep_planeacion_ficha 
				where   
					sep_programa.prog_codigo=sep_ficha.prog_codigo
					and sep_planeacion_ficha.fic_numero=sep_ficha.fic_numero 
					and sep_planeacion_ficha.pla_ins_lider=sep_participante.par_identificacion
					and sep_ficha.fic_numero=0 ORDER BY sep_participante.par_nombres ASC";
			}	
			
		$instructores = DB::select("select * from sep_participante where rol_id=2");
		
		$users = DB::select($sql);
		$datos = DB::select($sql1);
       	$fecha_tiempo = date("Y-m-d",strtotime($datos[0]->fic_fecha_fin."+ 18 month"));
        
        $users = new LengthAwarePaginator(
                array_slice(
                        $users, $offset, $perPage, true
                ), count($users), $perPage, $page);
        
        $users->setPath("index");
        //dd($datos);
        return view("Modules.Seguimiento.Practica.index", compact("users","offset","id","datos","instructores","fecha_tiempo"));
    }
	
	public function postIndex(){
		return $this->getIndex($_POST['cedula']);
	}
	
	public function getModal(){	
		$id=$_GET['id'];			
		$nombre=$_GET['nombre'];			
		$apellido=$_GET['apellido'];			
        
		$alternativasA = DB::select("select * from sep_opcion_etapa order by ope_id");

		$alternativas = array();
		
		foreach ($alternativasA as $alternativa) {
			$alternativas[$alternativa->ope_id] = $alternativa->ope_descripcion;
		}
		
		$seguiProductiva = DB::select("select * from sep_seguimiento_productiva where par_identificacion_aprendiz='$id'");
		
		$bitacoras = array();
		$visitas = array();
		
		$seg_pro_nombre_empresa = "";
		$seg_pro_jefe = "";
		$seg_pro_jefe_correo = "";
		$seg_pro_jefe_telefono = "";
		$seg_pro_fecha_ini = "";
		$seg_pro_fecha_fin = "";
		$ope_id = "";
		$seg_pro_obs_lider_productiva = "";
		$seg_pro_obs_instructor_seguimiento = "";
		$observaciones_instructor = "";
		$observaciones_lider = "";
		$disabled = "";
		$readonly = "";
		
		if(count($seguiProductiva)>0){
			$seguiBitacora = DB::select("select * from sep_seguimiento_bitacora where seg_pro_id=" . $seguiProductiva[0]->seg_pro_id);
			
			$seguiVisita = DB::select("select * from sep_seguimiento_visita where seg_pro_id=" . $seguiProductiva[0]->seg_pro_id . " order by seg_vis_visita");
			//dd($seguiVisita);
			foreach ($seguiBitacora as $bitacora) {
				$bitacoras[$bitacora->seg_bit_id] = $bitacora->seg_bit_bitacora;
			}

			foreach ($seguiVisita as $visita) {
				$visitas[$visita->seg_vis_id] = $visita->seg_vis_visita;
				$fechas[$visita->seg_vis_id] = $visita->seg_vis_fecha;
			}
			
			foreach ($seguiProductiva as $datos) {
				$seg_pro_nombre_empresa = $datos->seg_pro_nombre_empresa;
				$seg_pro_jefe = $datos->seg_pro_jefe;
				$seg_pro_jefe_correo = $datos->seg_pro_jefe_correo;
				$seg_pro_jefe_telefono = $datos->seg_pro_jefe_telefono;
				$seg_pro_fecha_ini = $datos->seg_pro_fecha_ini;
				$seg_pro_fecha_fin = $datos->seg_pro_fecha_fin;
				$ope_id = $datos->ope_id;
				$seg_pro_obs_lider_productiva = $datos->seg_pro_obs_lider_productiva;
				$seg_pro_obs_instructor_seguimiento = $datos->seg_pro_obs_instructor_seguimiento;
			}
		}
		$rol = DB::select("select rol_id as rol from sep_participante where par_identificacion = ". \Auth::user()->par_identificacion ."");
		
		if($rol[0]->rol == 2){
			$observaciones_lider = "readonly";
		}else{
			$disabled = "disabled";
			$readonly = "readonly";
		}
		
		return view("Modules.Seguimiento.Practica.modales",compact("observaciones_lider","fechas","rol","seguiVisita","readonly","disabled","seg_pro_obs_instructor_seguimiento","ope_id","seg_pro_fecha_fin","seg_pro_fecha_ini","seg_pro_nombre_empresa","seg_pro_obs_lider_productiva","alternativas","id","nombre","apellido","seguiProductiva","visitas","bitacoras","seg_pro_jefe","seg_pro_jefe_correo","seg_pro_jefe_telefono"));
	} 
	public function postSeguimiento(Request $request){
		extract($_POST);
		//Consultamos que rol inicio sesi贸n
		$rol = DB::select("select rol_id as rol from sep_participante where par_identificacion = ". \Auth::user()->par_identificacion ."");
		$sql="select seg_pro_id, seg_pro_obs_lider_productiva from sep_seguimiento_productiva where par_identificacion_aprendiz = '$id'";
		$aprendiz = DB::select($sql);
		$observaciones_lider_productiva = "";
		if($ope_id == ""){
		    $ope_id = 11;
		}
		
		if( $rol[0]->rol != 7){
			if(count($aprendiz)>0){				
				$id_viejo = $aprendiz[0]->seg_pro_id;
				$observaciones_lider_productiva = $aprendiz[0]->seg_pro_obs_lider_productiva;
				
				$sqlDelete = "delete from sep_seguimiento_productiva where seg_pro_id = $id_viejo";
				DB::delete($sqlDelete);
				
				$sqlDelete = "delete from sep_seguimiento_visita where seg_pro_id = $id_viejo";
				DB::delete($sqlDelete);

				$sqlDelete = "delete from sep_seguimiento_bitacora where seg_pro_id = $id_viejo";
				DB::delete($sqlDelete);
			}

			$sql="INSERT INTO sep_seguimiento_productiva (
					fic_numero,par_identificacion_responsable,par_identificacion_aprendiz,"
					. "ope_id,"
					. "seg_pro_nombre_empresa,"
					. "seg_pro_jefe,"
					. "seg_pro_jefe_correo,"
					. "seg_pro_jefe_telefono,"
					. "seg_pro_fecha_ini,"
					. "seg_pro_fecha_fin,"
					. "seg_pro_obs_lider_productiva,"
					. "seg_pro_obs_instructor_seguimiento)"
					. "values("
					. "'$ficha','" . Auth::user()->par_identificacion . "','$id',$ope_id,'$empresa','$jefe','$jefe_correo','$jefe_telefono','$fecha_inicio',"
					. "'$fecha_fin','$observaciones_lider_productiva','$seg_pro_obs_instructor_seguimiento')";
            /*print_r($sql);
	    	dd();*/
			DB::insert($sql);
			
		   
			$idSeguimiento = DB::getPdo()->lastInsertId();
			
			// Inserci贸n de las visitas
			$sql = "insert into sep_seguimiento_visita (seg_pro_id,seg_vis_visita,seg_vis_fecha) values ";
					
			if(isset($visita1)){
				$sql_visita = $sql."($idSeguimiento,1,'$fecha1')";
				DB::insert($sql_visita);
			}
			
			if(isset($visita2)){
				$sql_visita = $sql."($idSeguimiento,2,'$fecha2')";
				DB::insert($sql_visita);
			}
			
			if(isset($visita3)){
				$sql_visita = $sql."($idSeguimiento,3,'$fecha3')";
				DB::insert($sql_visita);
			}
			
			//iNSERCION DE LAS BITACORAS
			if(isset($bitacora)){
				foreach($bitacora[$id] as $bit){
					$sql3="
					INSERT INTO sep_seguimiento_bitacora 
					values (".$idSeguimiento.",'$bit',null)";
					$insert = DB::insert($sql3);
				}
			}
			
            //validar si ya tiene las 12 bitacoras y la visita final para actualizar el estado del aprendiz
			$sql= "select COUNT(vis.seg_vis_visita) as final 
			from sep_seguimiento_visita as vis , sep_seguimiento_productiva as pro
			where pro.fic_numero = $ficha
			and pro.par_identificacion_aprendiz = $id
			and vis.seg_pro_id = pro.seg_pro_id
			and vis.seg_vis_visita = 3";
			$visita_final=DB::select($sql);

			$sql = "
			select COUNT(bit.seg_bit_bitacora) as bitacora, bit.seg_pro_id
			from  sep_seguimiento_bitacora as bit , sep_seguimiento_productiva as pro
			where pro.seg_pro_id = bit.seg_pro_id
			and   pro.fic_numero = $ficha
			and   pro.par_identificacion_aprendiz = $id
			and   bit.seg_bit_bitacora = 12";
			$bitacora=DB::select($sql);

			if ($bitacora[0]->bitacora == 1 && $visita_final[0]->final == 1) {
				$sql="
				update sep_matricula set est_id = 3
		        where par_identificacion = ".$id." 
		        and   fic_numero = ".$ficha."";
		        DB::update($sql);
			}
			
			$sqlConsultaAprendiz = "
				select count(*) as total 
				from sep_etapa_practica
				where par_identificacion = '$id'";
			$consultaAprendiz = DB::select($sqlConsultaAprendiz);
			
			$fechaActual = date('d/m/Y');
			
			if($consultaAprendiz[0]->total > 0){
				$sqlActualizar = "
					update sep_etapa_practica
					set ope_id = '$ope_id'
					where par_identificacion = '$id'";
					
				DB::update($sqlActualizar);
			}else{
				$sqlInsertar = "
					insert into sep_etapa_practica
					(par_identificacion,ope_id,etp_fecha_registro)
					values ('$id','$ope_id','$fechaActual')";
					
				DB::insert($sqlInsertar);
			}
		}else{
			if(count($aprendiz)>0){
				$sqlActualizar = "
					update sep_seguimiento_productiva
					set seg_pro_obs_lider_productiva = '$seg_pro_obs_lider_productiva'
					where par_identificacion_aprendiz = '$id'";
					
				DB::update($sqlActualizar);
			}else{
				echo$sqlRegistrar = "
				insert into sep_seguimiento_productiva 
					(fic_numero,par_identificacion_aprendiz,
					par_identificacion_responsable,seg_pro_obs_lider_productiva,ope_id)
				values
					('$ficha','$id','" . Auth::user()->par_identificacion . "',
					'$seg_pro_obs_lider_productiva',11)";

				DB::insert($sqlRegistrar);
			}
		}
    }
	public function getAjaxinstructorliderpractica(){
		extract($_GET);
		
		$sqlUpdate = DB::update("update sep_ficha set par_identificacion_productiva = '$vIngresado' where fic_numero = '$ficha'");
		
		$sqlInstructor = DB::select("select par_nombres,par_apellidos from sep_participante where par_identificacion = '$vIngresado'");
		
		echo $sqlInstructor[0]->par_nombres." ".$sqlInstructor[0]->par_apellidos;
	}
	
	public function getReporte()
	{
		extract($_GET);
        if (is_numeric($ficha)) {
			$sql="
			select par.par_identificacion , par.par_nombres, par.par_apellidos , par.par_correo, 
			par.par_telefono, fic.fic_numero , prog.prog_nombre , niv.niv_for_nombre, pla_fic.pla_ins_lider as instructor ,
			pla_fic.fecha_inicio_productiva , pla_fic.fecha_fin_productiva , est.est_descripcion 
			from sep_matricula mat
			left join sep_ficha fic on fic.fic_numero = mat.fic_numero
			left join sep_programa prog on prog.prog_codigo = fic.prog_codigo
			left join sep_nivel_formacion niv on niv.niv_for_id = prog.niv_for_id
			left join sep_participante par on par.par_identificacion = mat.par_identificacion
			left join sep_planeacion_ficha pla_fic on pla_fic.fic_numero = mat.fic_numero
			left join sep_estado est on est.est_id = mat.est_id
			where mat.fic_numero = ".$ficha;
			$ficha = DB::select($sql);

			$sql = "
			select concat(par.par_nombres,' ',par.par_apellidos) as nombres
			from sep_participante par 
			left join sep_seguimiento_productiva pro on par.par_identificacion = pro.par_identificacion_responsable
			where pro.fic_numero = ".$ficha[0]->fic_numero." limit 1";
			$instructor_productiva = DB::select($sql);
            if (count($instructor_productiva) != 0) {
				$instructor_productiva = $instructor_productiva[0]->nombres;
			}else{
				$instructor_productiva = "Sin asignar";
			}
            
			$filas = "";
			$c=1;
            foreach ($ficha as $val) {
				$filas.="
				<tr>
				<td>".$c++."</td>
				<td>".utf8_decode($val->par_identificacion)."</td>".
				"<td>".utf8_decode($val->par_nombres)."</td>
				<td>".utf8_decode($val->par_apellidos)."</td>
				<td>".utf8_decode($val->par_correo)."</td>
				<td>".utf8_decode($val->par_telefono)."</td>
				<td>".utf8_decode($val->est_descripcion)."</td>";

				$sql = "
				select opt.ope_descripcion, pro.seg_pro_nombre_empresa, pro.seg_pro_jefe , pro.seg_pro_jefe_telefono,
				pro.seg_pro_jefe_correo, pro.seg_pro_fecha_ini ,
				pro.seg_pro_fecha_fin, count(bita.seg_bit_bitacora) as total 
				from sep_seguimiento_productiva pro 
				left join sep_seguimiento_bitacora bita on bita.seg_pro_id = pro.seg_pro_id 
				left join sep_opcion_etapa opt on opt.ope_id = pro.ope_id 
				where pro.par_identificacion_aprendiz = ".$val->par_identificacion."
				and pro.fic_numero = ".$val->fic_numero;
				$total = DB::select($sql);
				
				if (count($total) > 0) {
					
					$filas.= 
					"<td>".$total[0]->ope_descripcion."</td>".
					"<td>".$total[0]->seg_pro_nombre_empresa."</td>".
					"<td>".$total[0]->seg_pro_jefe."</td>".
					"<td>".$total[0]->seg_pro_jefe_correo."</td>".
					"<td>".$total[0]->seg_pro_jefe_telefono."</td>".
					"<td>".$total[0]->seg_pro_fecha_ini."</td>".
					"<td>".$total[0]->seg_pro_fecha_fin."</td>";	
					$total = $total[0]->total;
					for ($i=1; $i <= 12; $i++) { 
						if ($i <= $total) {
							$filas.="<td>X</td>";
						}else{
							$filas.="<td> </td>";
						}
					}				      				
				}else{
					$filas.= "<td>No tiene asignada la alternativa</td>
					<td> </td> <td></td> <td></td> <td> </td> <td> </td><td> </td>";
					for ($i=1; $i <= 12; $i++) { 
						$filas.="<td> </td>";
					}
				}
				
			}

			$instructor_lider = $ficha[0]->instructor;
			if ($instructor_lider != "") {
				$sql = "select concat(par_nombres,' ',par_apellidos) as nombres from sep_participante where par_identificacion = ".$instructor_lider;
				$instructor_lider = DB::select($sql);
				$instructor_lider = $instructor_lider[0]->nombres;
			}else{
				$instructor_lider = "Sin asignar";
			}
			
			//Exportamos la tabla
			$tabla = '
			<style>
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				font-family:Arial;
			}
			#campos{ background:#5e83ba; color:white; }
			</style>
			<h2>LISTADO DE APRENDICES</h2>
			<h3>Ficha: '.$ficha[0]->fic_numero.'</h3>
			<h3 style="text-transform: uppercase !important;">Nivel: '.utf8_decode($ficha[0]->niv_for_nombre).'</h3>
			<h3>Programa: '.utf8_decode($ficha[0]->prog_nombre).'</h3>
			<h3>Instructor lider: '.$instructor_lider.'</h3>
			<h3>Instructor de productiva: '.$instructor_productiva.'</h3>
			<h3>Inicio productiva: '.$ficha[0]->fecha_inicio_productiva.'</h3>
			<h3>Fin productiva: '.$ficha[0]->fecha_fin_productiva.'</h3>
			<table cellspacing="0" cellpadding="0">
			<tr id="campos"><th>C&oacute;digo</th><th>Documento</th><th>Nombres</th><th>Apellidos</th>
			<th>Correo</th><th>Telefono</th><th>Estado</th><th>Alternativa</th><th>Empresa</th>
			<th>Jefe</th><th>Correo</th><th>Telefono</th><th>fecha inicio</th><th>fecha fin</th><th>1</th>
			<th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th>';
			$tabla.=$filas."</table><h4>Power By Setalpro ".date('Y')."</h4>";
			//echo $tabla;
			//dd();
		    header('Content-type: application/vnd.ms-excel; charset=utf-8');
			header("Content-Disposition: attachment; filename=LISTADO_APRENDICES_".$ficha[0]->fic_numero.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $tabla;
		}
	}
}
