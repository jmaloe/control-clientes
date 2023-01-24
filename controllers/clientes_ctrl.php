<?php
 require_once("../db/AccessDB.php");
 require_once("../clases/CCliente.php");
 $clientes = new CCliente($db);
 $cliente_encontrado=false;
 $msg = "";
if(isset($_POST['id_cliente']) && $_POST['id_cliente']>0){
 	$clientes->setIdCliente($_POST['id_cliente']);
 	if($clientes->findById()){
 		$cliente_encontrado=true;
 	}
 }
 if(isset($_POST['btn_accion'])){	
 	if($_POST['btn_accion']=="buscar")
 	{
	    $clientes->setNombre($_POST['nombre']);
	    $clientes->setApellidoPaterno($_POST['apepat']);
	    $clientes->setApellidoMaterno($_POST['apemat']);
	    if($clientes->findByName()){
			$cliente_encontrado = true;
		}
 	}
 	else
 	{
	 	if($_POST['btn_accion']=="cancelar"){
	 		$cliente_encontrado=false; 		
	 	}
	 	else{
		 	if(isset($_POST['nombre']))
		 		$clientes->setNombre($_POST['nombre']);
		 	if(isset($_POST['apepat']))
		 		$clientes->setApellidoPaterno($_POST['apepat']);
		 	if(isset($_POST['apemat']))
		 		$clientes->setApellidoMaterno($_POST['apemat']);
		 	if(isset($_POST['direccion']))
		 		$clientes->setDireccion($_POST['direccion']);
		 	if(isset($_POST['telefono']))
		 		$clientes->setTelefono($_POST['telefono']);
		 	if(isset($_POST['ddp']))
		 		$clientes->setDiaDePago($_POST['ddp']);
		 	if($_POST['btn_accion']=="agregar"){
		 		$clientes->setSaldo(0.0);
		 		$clientes->agregarCliente();
		 		$msg = "Cliente agregado correctamente.";
		 		$cliente_encontrado=false;
		 	}else if($_POST['btn_accion']=="actualizar"){
		 		if($clientes->actualizarCliente()>0){	 			
		 			$msg = "Datos actualizados correctamente.";
		 		}
		 	}else if($_POST['btn_accion']=="dardebaja"){
		 		$clientes->darDeBaja();
		 		$cliente_encontrado=false;
		 		$msg="Se dio de baja al cliente: ".$clientes->getNombre()." ".$clientes->getApellidoPaterno()." ".$clientes->getApellidoMaterno();
		 	}
	 	}
 	}
 }
 ?>