<?php
 require_once("../db/AccessDB.php");
 require_once("../clases/CContratos.php");
 $contrato = new CContratos($db);
 $contrato_encontrado=false;
 $msg = "";

if(isset($_POST['num_contrato'])){
 	$contrato->setNumContrato($_POST['num_contrato']);
 	if($contrato->findById()){
 		$contrato_encontrado=true;
 	}
 }
 if(isset($_POST['btn_accion'])){
 	if($_POST['btn_accion']=="mostrar_detalle")
 	{
	    $contrato->getDetalle();
 	}
 	else
 	{
	 	if($_POST['btn_accion']=="cancelar"){
	 		$contrato_encontrado=false; 		
	 	}
	 	else if($_POST['btn_accion']=="listacontratos"){
	 		$cliente_encontrado=false; //para que no se efectue la busqueda del cliente
	 	}
	 	else{
	 		if(isset($_POST['id_cliente']))
	 			$contrato->setIdCliente($_POST['id_cliente']);
	 		if(isset($_POST['fecha_i']))
	 			$contrato->setFechaInicio($_POST['fecha_i']);
	 		if(isset($_POST['descripcion']))
		 		$contrato->setDescripcion($_POST['descripcion']);
		 	if(isset($_POST['total']))
		 		$contrato->setTotal($_POST['total']);
		 	if($_POST['btn_accion']=="nuevocontrato"){
		 		$contrato->agregarContrato();
		 		$totalelems = count($_POST['producto']['id']);
			 	$cont=0;
			 	while($cont<$totalelems){
			 		$contrato->agregarDetalle($_POST['producto']['id'][$cont], $_POST['producto']['cantidad'][$cont], $_POST['producto']['precio'][$cont], $_POST['producto']['total'][$cont]);			 		
			 		$cont++;
			 	}
		 		$msg = "Contrato creado satisfactoriamente.";
		 		$contrato_encontrado=false;
		 	}else if($_POST['btn_accion']=="actualizar"){
		 		if($contrato->actualizarContrato()>0){
		 			$msg = "Datos actualizados correctamente.";
		 		}
		 	}else{
		 		$dato = explode(":", $_POST['btn_accion']);
		 		if($dato[0]=='dardebaja'){
		 			$contrato->setNumContrato($dato[1]);
		 			$contrato->darDeBaja();
		 			$_POST['btn_accion']="listacontratos";
		 			$cliente_encontrado=false;
		 			$msg="¡Contrato finalizado!";
		 		}		 		
		 	}
	 	}
 	}
 }
 ?>