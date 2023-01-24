<?php
 require_once("../db/AccessDB.php");
 require_once("../clases/CAbonos.php");
 $abonos = new CAbonos($db);
 $abono_encontrado=false;
 $msg = "";

 if(isset($_POST['btn_accion'])){
 	if($_POST['btn_accion']=="mostrar_detalle"){
 		$abonos->setNumPago($_POST['no_pago']);
 		$abonos->mostrarDetallePago();
 	}
 	if($_POST['btn_accion']=="cancelar"){
 		$abono_encontrado=false; 		
 	}
 	else{
	 	if($_POST['btn_accion']=="registrar_abono"){
	 		if(isset($_POST['mes']))
	 		{
		 		$abonos->setIdUsuario($_SESSION["ID_USER"]);
		 		
		 		$clientes->setIdCliente($_POST['id_cliente']);
		 		$clientes->setSaldo($_POST['saldo']);
		 		$clientes->actualizarSaldo();

		 		$abonos->setMonto($_POST['total_abono']);
		 		$abonos->agregarAbono($_POST['num_contrato']);
		 		//echo $abonos->getIdUsuario();
		 		@$totalelems = count($_POST['mes']);
			 	$cont=0;

			 	while($cont<$totalelems){
			 		$dato = explode(":", $_POST['mes'][$cont]);
			 		//echo $dato[0]." - ".$dato[1];
			 		$abonos->agregarDetalleDeAbono($dato[0], $dato[1]); //mes[0], año[1]
			 		$cont++;
			 	}		 		
		 		$msg = "Abono registrado correctamente.";
		 		$abono_encontrado=false;
	 		}
	 	}else if($_POST['btn_accion']=="dardebaja"){
	 		$abonos->darDeBaja();
	 		$abono_encontrado=false;
	 		$msg="¡Abono finalizado!";
	 	}
 	} 	
 }
 ?>