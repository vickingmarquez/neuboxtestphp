<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
    	return view('index');
    }

    public function marcador(){
    	return view('marcador');
    }

    public function encriptador(){
    	return view('encriptador');
    }

    public function marcadorCheck(Request $request){
    	if ($request->ajax()) {
			if ($request->isMethod('post')){
				if (isset($_FILES['file'])) {
					if($_FILES['file']['type'] == 'text/plain'){
						$array = explode("\n", file_get_contents($_FILES['file']['tmp_name']));
						$limit = null;
						$players = [0,0];
						$maxDif = 0;
						$puntero = 0;
						foreach ($array as $indice => $valor) {
							if( $indice == 0 ){
								$limit = intval($valor);
							}
							if( $limit >= $indice && $indice > 0 ){
								$puntuaciones = explode(" ", $valor);
								if(count($puntuaciones) > 2){
									return response()->json([
										'status' => 0,
										'respuesta' => 'Se excede el numero de jugadores en la ronda '.($indice+1),
									]);
									break;
								} else {
									$players[0] = $players[0] + intval($puntuaciones[0]);
									$players[1] = $players[1] + intval($puntuaciones[1]);
									$diferencia = max($players) - min($players); 
									if($diferencia > $maxDif){
										$maxDif = $diferencia;
										if( $players[0] > $players[1] ){
											$puntero = 1;
										} else {
											$puntero = 2;
										}
									}
								}								
							}
						}
						return response()->json([
							'status' => 1,
							'respuesta' => $puntero .' '. $maxDif,
						]);
					} else {
						return response()->json([
							'status' => 0,
							'respuesta' => 'El archivo cargado tienen que ser formato .txt',
						]);
					}					
				} else {
					return response()->json([
						'status' => 0,
						'respuesta' => 'No se recibio ningun archivo, asegurate de seleccionar uno',	    		
					]);
				}
				
			} else {
				return response()->json([
					'status' => 0,
					'respuesta' => 'Petición http incorrecta',	    		
				]);
			}	
	    } else {
	    	return response()->json([
				'status' => 0,
				'respuesta' => 'No fue recibido ajax request',	    		
	    	]);
	    }
	}
	
	public function encriptadorCheck(Request $request){
    	if ($request->ajax()) {
			if ($request->isMethod('post')){
				if (isset($_FILES['file'])) {
					if($_FILES['file']['type'] == 'text/plain'){
						$array = explode("\n", file_get_contents($_FILES['file']['tmp_name']));
						$lineOne = [];
						$instrucciones = [];
						$mensaje = null;
						foreach ($array as $indice => $valor) {
							switch($indice){
								case 0:
									$lineOne = explode(" ", $valor);
									//Minimo de caracteres
									if(intval($lineOne[0]) < 2 || intval($lineOne[1]) < 2){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El numero de caracteres en las instrucciones debe ser mayor a 2',
										]);
										break 2;
									}
									//Minimo de caracteres
									if(intval($lineOne[2]) < 3){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El numero de caracteres en el mensaje debe ser mayor a 3',
										]);
										break 2;
									}
									break 1;
								case 1:
									$instrucciones[0] = str_replace(array("\r", "\n"), '', $valor);
									//Cantidad de caracteres
									if(strlen($instrucciones[0]) != intval($lineOne[0])){
										return response()->json([
											'status' => 0,
											'respuesta' => 'La primer instrucción no tiene la cantidad de caracteres indicada',
										]);
										break 2;
									}
									break 1;
								case 2:
									$instrucciones[1] = str_replace(array("\r", "\n"), '', $valor);
									//Cantidad de caracteres
									if(strlen($instrucciones[1]) != intval($lineOne[1])){
										return response()->json([
											'status' => 0,
											'respuesta' => 'La segunda instrucción no tiene la cantidad de caracteres indicada',
										]);
										break 2;
									}
									break 1;
								case 3:
									$mensaje = str_replace(array("\r", "\n"), '', $valor);
									//Cantidad caracteres
									if(strlen($mensaje) != intval($lineOne[2])){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El mensaje no tiene la cantidad de caracteres indicada',
										]);
										break 2;
									}
									//Validacion de caracteres
									if(preg_match('/[^A-Za-z0-9]/', $mensaje)){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El mensaje contiene caracteres no permitidos',
										]);
										break 2;
									}
									//Limpiar mensaje
									$mensajeArray = str_split($mensaje);
									$mensaje = "";
									foreach($mensajeArray as $indice => $letra){
										if($indice > 0){
											if($mensajeArray[$indice-1] != $letra){
												$mensaje .= $letra; 
											}
										}
									}
									break 1;
							}
															
						}
						//Buscar instrucciones en mensaje
						$responseIns = [];
						foreach($instrucciones as $indice => $instruccion){							
							if(stristr($mensaje, $instruccion)){
								array_push($responseIns,'SI<br/>');
							} else{
								array_push($responseIns,'NO<br/>');
							}
						}
						return response()->json([
							'status' => 1,
							'respuesta' => $responseIns,
						]);
					} else {
						return response()->json([
							'status' => 0,
							'respuesta' => 'El archivo cargado tienen que ser formato .txt',
						]);
					}					
				} else {
					return response()->json([
						'status' => 0,
						'respuesta' => 'No se recibio ningun archivo, asegurate de seleccionar uno',	    		
					]);
				}
				
			} else {
				return response()->json([
					'status' => 0,
					'respuesta' => 'Petición http incorrecta',	    		
				]);
			}	
	    } else {
	    	return response()->json([
				'status' => 0,
				'respuesta' => 'No fue recibido ajax request',	    		
	    	]);
	    }
    }

}