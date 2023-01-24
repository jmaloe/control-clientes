<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
 require_once("../db/ConexionDB.php");

 class CAbonos extends ConexionDB{
 	var $num_pago,
 		$fecha,
		$monto,
		$idUsuario,
		$mes_liquidado,
		$anio_liquidado;

 	function __construct($db)
	{
    	parent::__construct($db); /*invocar el constructor de la clase padre*/
 	}

 	//setters
 	function setNumPago($id){
 		$this->num_pago = $id;
 	}

 	function setFecha($f){
 		$this->fecha = $this->getFechaToMysql($f);
 	}

 	function setMonto($d){
 		$this->monto = $this->scapeString($d);
 	}

 	function setIdUsuario($iu){
 		$this->idUsuario = $iu;
 	}

 	function setMesLiquidado($ml){
 		$this->mes_liquidado = $ml;
 	}

 	function setAnioLiquidado($al){
 		$this->anio_liquidado = $al;
 	}

 	//getters
 	function getNumPago(){
 		return $this->num_pago;
 	}

 	function getFecha(){
 		return $this->fecha;
 	}

 	function getMonto(){
 		return $this->monto;
 	}

 	function getIdUsuario(){
 		return $this->idUsuario;
 	}

 	function getMesLiquidado(){
 		return $this->mes_liquidado;
 	}

 	function getAnioLiquidado(){
 		return $this->anio_liquidado;
 	}

 	function agregarAbono($num_contrato){
 	 	$sql = "INSERT INTO Pagos(num_contrato,monto,idUser) VALUES(".$num_contrato.",".$this->monto.",".$this->idUsuario.");";
 	 	if($this->query($sql)){
 	 		$this->num_pago = $this->getInsertId();
 	 		return true;
 	 	}
 	 	else
 	 	{
 	 		echo "agregarAbono():".$this->getError();
 	 		return false;
 	 	}
 	}

 	function agregarDetalleDeAbono($mesliq, $anioliq){
 		$sql = "INSERT INTO DetalleDePago(no_pago,mes_liq,anio_liq) VALUES(".$this->num_pago.",".$mesliq.",".$anioliq.");";
 	 	if($this->query($sql)){ 	 		
 	 		return true;
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 		return false;
 	 	}
 	} 	

 	function getMesesPorCobrar($numcontrato){
 	 	$sql = "SELECT (((YEAR(NOW())-YEAR(fecha_inicio)+1)*12)-(12-MONTH(NOW())))-MONTH(fecha_inicio) AS meses_transcurridos, YEAR(NOW()) AS anio_actual, YEAR(fecha_inicio) AS anio_inicial, MONTH(NOW()) AS mes_actual FROM contratos c, detallecontrato dc WHERE c.num_contrato=dc.num_contrato AND c.num_contrato=$numcontrato AND c.vigente=1";
 	 	$resultado = $this->query($sql);
 	 	if($resultado)
 	 	{
 	 		$dato = mysqli_fetch_assoc($resultado);
 	 		$mesestot = $dato['meses_transcurridos'];
 	 		$cont_meses = $dato['mes_actual']-1; 	 		
 	 		$cont_anhos = $dato['anio_actual'];
 	 			 	
	 	 	$flag=true;
	 	 	while($mesestot>0){
	 	 		if($flag){
	 	 			$flag=false;
	 	 			echo '<tr class="mes sombreado" id="mes'.$mesestot.'">';
	 	 		}
	 	 		else{
	 	 		  echo '<tr class="mes" id="mes'.$mesestot.'">';
	 	 		  $flag=true;
	 	 		}
	 	 			if($cont_meses==-1){
	 	 				$cont_meses=11;
	 	 				$cont_anhos--;
	 	 			}
	 	 			echo '<td>'.$mesestot.'</td>';
	 	 			echo '<td>'.$this->getMes($cont_meses).'</td>';
	 	 			echo '<td>'.$cont_anhos.'</td>';
	 	 			$this->checarPago($numcontrato, $cont_meses+1, $cont_anhos, $mesestot);	 	 			
	 	 		echo '</tr>';
	 	 		$mesestot--;
	 	 		$cont_meses--;
	 	 	} 
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 		return;
 	 	}
 	}

 	function checarPago($numcontrato, $mes, $anio, $mesestot){
 		$sql = 'SELECT p.fecha 
				FROM contratos c, pagos p, detalledepago dp 
				WHERE p.num_contrato=c.num_contrato
				AND dp.no_pago = p.no_pago 
				AND c.num_contrato='.$numcontrato.' 
				AND mes_liq='.$mes.'
				AND anio_liq='.$anio.'
				ORDER BY dp.mes_liq DESC, dp.anio_liq desc;';
 	 	$resultado = $this->query($sql);
 	 	if($dato = mysqli_fetch_assoc($resultado))
 	 	{ 	 		
 	 		echo '<td>'.$dato['fecha'].'</td>';
 	 		echo '<td><input type="checkbox" id="chk'.$mesestot.'" name="mes[]" value="'.$mes.':'.$anio.'" onclick="return false;" checked="checked" disabled></td>';
 	 	}
 	 	else{
 	 		echo '<td>Pago pendiente</td>';
 	 		echo '<td><input type="checkbox" id="chk'.$mesestot.'" name="mes[]" value="'.$mes.':'.$anio.'" onclick="return false;"></td>';
 	 	}
 	}

 	function getMes($num){
 		$meses = array(0=>"Enero",1=>"Febrero",2=>"Marzo",3=>"Abril",4=>"Mayo",5=>"Junio",6=>"Julio",7=>"Agosto",8=>"Septiembre",9=>"Octubre",10=>"Noviembre",11=>"Diciembre");
 		return $meses[$num];
 	}

 	function getHistorialAbonos(){
 		$sql = 'SELECT  p.no_pago,
 			p.num_contrato, 
			cli.id_cliente, 
			cli.nombre, 
			cli.apellido_paterno, 
			cli.apellido_materno,
			c.descripcion, 
			p.monto, 
			p.fecha 
			FROM pagos p, contratos c, clientes cli
			WHERE c.num_contrato=p.num_contrato
			AND cli.id_cliente=c.id_cliente
			order BY fecha desc;';
		$resultado = $this->query($sql);		
		if($resultado){
			echo '<table class="table-items"><thead><tr><th title="Número de contrato">N.C.</th><th>IDCliente</th><th>Nombre</th><th>Descripcion</th><th>Pago</th><th>fecha</th></tr></thead>';
			echo "<tbody>";
			$flag=true;
			while($dato = mysqli_fetch_assoc($resultado)){
				if($flag){
	 	 			$flag=false;
	 	 			echo '<tr class="cliente sombreado" id="'.$dato['no_pago'].'">';
	 	 		}
	 	 		else{
	 	 		  echo '<tr class="cliente" id="'.$dato['no_pago'].'">';
	 	 		  $flag=true;
	 	 		}				
				echo "<td>".$dato['num_contrato']."</td>";
				echo "<td>".$dato['id_cliente']."</td>";
				echo "<td>".$dato['nombre']." ".$dato['apellido_paterno']." ".$dato['apellido_materno']."</td>";				
				echo "<td>".$dato['descripcion']."</td>";
				echo "<td>".$dato['monto']."</td>";
				echo "<td>".$dato['fecha']."</td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo '</table>';
		}
 	}

 	function mostrarDetallePago(){ 		
 		$sql = 'SELECT
				p.fecha, 
				dp.mes_liq, 
				dp.anio_liq 
				FROM pagos p, detalledepago dp, contratos c 
				WHERE p.no_pago=dp.no_pago 
				AND p.num_contrato=c.num_contrato
				AND p.no_pago='.$this->num_pago.' ORDER BY dp.mes_liq desc, dp.anio_liq desc';
		$resultado = $this->query($sql);
		if($resultado){
			echo '<table class="table-items"><thead><tr><th>#</th><th>Fecha</th><th>Mes</th><th>Año</th></tr></thead>';
			$cont=1;
			while($dato = mysqli_fetch_assoc($resultado)){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$dato['fecha']."</td>";
				echo "<td>".$this->getMes($dato['mes_liq']-1)."</td>";
				echo "<td>".$dato['anio_liq']."</td>";				
				echo "</tr>";
				$cont++;
			}
			echo '</table>';
		} 	
 	}

 	function getPagosVencidos(){
 		$sql ='SELECT c.num_contrato, c.descripcion,cli.nombre, cli.apellido_paterno, cli.apellido_materno, 
				(((YEAR(NOW())-YEAR(fecha_inicio)+1)*12)-(12-MONTH(NOW())))-MONTH(fecha_inicio) AS meses_transcurridos,
				count(dp.mes_liq) AS meses_pagados 
				FROM contratos c, pagos p, detalledepago dp, clientes cli 
				WHERE p.num_contrato=c.num_contrato
				AND c.id_cliente=cli.id_cliente
				AND p.no_pago = dp.no_pago 
				AND c.vigente=1
				GROUP BY p.num_contrato';
		$resultado = $this->query($sql);
		if($resultado){
			echo '<table class="table-items"><thead><tr><th># Contrato</th><th>Descripcion</th><th>Cliente</th><th>Meses atrasados</th></tr></thead>';
			$cont=1;
			while($dato = mysqli_fetch_assoc($resultado)){
				echo "<tr>";
				echo "<td>".$dato['num_contrato']."</td>";
				echo "<td>".$dato['descripcion']."</td>";
				echo "<td>".$dato['nombre']." ".$dato['apellido_paterno']." ".$dato['apellido_materno']."</td>";
				echo "<td>".($dato['meses_transcurridos']-$dato['meses_pagados'])."</td>";
				echo "</tr>";
				$cont++;
			}
			echo '</table>';
		} 	
 	}
 }
?>