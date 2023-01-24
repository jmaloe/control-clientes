<?php
header("Content-type: text/html");
date_default_timezone_set('America/Mexico_City');
include_once('../clases/CProductos.php');
include_once("../db/AccessDB.php");
$productos = new CProductos($db);
$obj_encontrado=false;
$msg = "";
if(isset($_POST['id_producto'])){
 	$productos->setIdProducto($_POST['id_producto']);
 	if($productos->findById()){
 		$obj_encontrado=true;
 	}
 }
 if(isset($_POST['btn_accion']))
 {
 	if($_POST['btn_accion']=="cancelar"){
 		$obj_encontrado=false; 		
 	}
 	else{ 			
 		if(isset($_POST['id_producto']))
 		{
 			$productos->setIdProducto($_POST['id_producto']);
		 	$productos->setNombre($_POST['nombre']);	 	
		 	$productos->setDescripcion($_POST['descripcion']);
		 	$productos->setPrecioCompra($_POST['preciocompra']);
		 	$productos->setPrecioVenta($_POST['precioventa']);
	 	}
	 	if($_POST['btn_accion']=="agregar"){
	 		$productos->agregarProducto();
	 		$msg = "Producto agregado correctamente.";
	 		
	 	}else if($_POST['btn_accion']=="actualizar"){	 	
	 		if($productos->actualizarProducto()>0){	 			
	 			$msg = "Datos actualizados correctamente.";
	 		}
	 	}else if($_POST['btn_accion']=="eliminar"){	 		
	 		$productos->eliminar();
	 		$msg="Producto eliminado exitósamente";
	 	}	 	
	 }
 }
 ?>