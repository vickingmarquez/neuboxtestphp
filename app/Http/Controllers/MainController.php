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
							$valor = str_replace(array("\r", "\n"), '', $valor);							
							if( $indice == 0 ){								
								if(is_numeric($valor)){
									$limit = intval($valor);
								} else {
									return response()->json([
										'status' => 0,
										'respuesta' => 'El valor indicado para el numero rondas debe ser numerico (renglon 1)',
									]);
									break;
								}	
							} else {
								if( $limit >= $indice ){
									$puntuaciones = explode(" ", $valor);
									if(count($puntuaciones) > 2){
										return response()->json([
											'status' => 0,
											'respuesta' => 'Se excede el numero de jugadores en la ronda '.$indice,
										]);
										break;
									} else {
										if(is_numeric($puntuaciones[0]) && is_numeric($puntuaciones[1])){
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
										} else {
											return response()->json([
												'status' => 0,
												'respuesta' => 'Los valores en las puntuaciones deben ser numericos (verifica ronda '.$indice.')',
											]);
											break;
										}										
									}								
								}
							}						
						}
						//Limite y mínimo de rondas
						if(count($array) > ($limit+1)){
							return response()->json([
								'status' => 0,
								'respuesta' => 'Se ha excedido el numero de rondas "'.$limit.'" indicado en el archivo (renglón 1)',
							]);
						}
						if(count($array) < ($limit+1)){
							return response()->json([
								'status' => 0,
								'respuesta' => 'Se deben colocar el numero de "'.$limit.'" rondas con puntuaciones, como se indico en el archivo (renglon 1)',
							]);
						}
						//Todo OK
						return response()->json([
							'status' => 1,
							'respuesta' => $puntero .' '. $maxDif,
							'check' => $players
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
									if(intval($lineOne[0]) < 2 || intval($lineOne[0]) > 50){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El numero de caracteres indicado para la instrucción 1 (renglón 1 del archivo) debe ser mayor a 1 y menor a 51',
										]);
										break 2;
									}
									if(intval($lineOne[1]) < 2 || intval($lineOne[1]) > 50){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El numero de caracteres indicado para la instrucción 2 (renglón 1 del archivo) debe ser mayor a 1 y menor a 51',
										]);
										break 2;
									}
									//Minimo de caracteres
									if(intval($lineOne[2]) < 3  || intval($lineOne[2]) > 5000){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El numero de caracteres indicado para el mensaje (renglón 1 del archivo) debe ser mayor a 2 y menor a 5001',
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
											'respuesta' => 'La primera instrucción (segundo renglón del archivo) debe tener una longitud de '.$lineOne[0].' caracteres',
										]);
										break 2;
									}
									//Caracteres repetidos
									$insArray = str_split($instrucciones[0]);
									foreach($insArray as $indice => $letra){
										if($indice > 0){
											if($insArray[$indice-1] == $letra){
												return response()->json([
													'status' => 0,
													'respuesta' => 'La primera instrucción (segundo renglón del archivo) no debe contener caracteres iguales seguidos',
												]);
												break 2; 
											}
										}
									}
									break 1;
								case 2:
									$instrucciones[1] = str_replace(array("\r", "\n"), '', $valor);
									//Cantidad de caracteres
									if(strlen($instrucciones[1]) != intval($lineOne[1])){
										return response()->json([
											'status' => 0,
											'respuesta' => 'La segunda instrucción (tercer renglón del archivo) debe tener una longitud de '.$lineOne[1].' caracteres',
										]);
										break 2;
									}
									//Caracteres repetidos
									$insArray = str_split($instrucciones[1]);
									foreach($insArray as $indice => $letra){
										if($indice > 0){
											if($insArray[$indice-1] == $letra){
												return response()->json([
													'status' => 0,
													'respuesta' => 'La segunda instrucción (tercer renglón del archivo) no debe contener caracteres iguales seguidos',
												]);
												break 2; 
											}
										}
									}
									break 1;
								case 3:
									$mensaje = str_replace(array("\r", "\n"), '', $valor);
									//Cantidad caracteres
									if(strlen($mensaje) != intval($lineOne[2])){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El mensaje (cuarto renglón del archivo) debe tener una longitud de '.$lineOne[2].' caracteres',
										]);
										break 2;
									}
									//Validacion de caracteres
									if(preg_match('/[^A-Za-z0-9]/', $mensaje)){
										return response()->json([
											'status' => 0,
											'respuesta' => 'El mensaje (cuarto renglón del archivo) solo debe contener caracteres alfanuméricos',
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
										} else {
											$mensaje .= $letra;
										}
									}
									break 1;
								default:
									return response()->json([
										'status' => 0,
										'respuesta' => 'El archivo no debe contener más de 4 renglones',
									]);
									break 1;
							}
															
						}
						//Buscar instrucciones en mensaje
						$responseIns = [];
						$noInsInMsg = 0;
						foreach($instrucciones as $indice => $instruccion){							
							if(stristr($mensaje, $instruccion)){
								array_push($responseIns,'SI<br/>');
								$noInsInMsg++;
							} else{
								array_push($responseIns,'NO<br/>');
							}
						}
						//Numero de instrucciones en mensaje
						if($noInsInMsg > 1){
							return response()->json([
								'status' => 0,
								'respuesta' => 'El mensaje no debe contener más de una instrucción',
							]);
						} else {
							return response()->json([
								'status' => 1,
								'respuesta' => $responseIns,
							]);
						}
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