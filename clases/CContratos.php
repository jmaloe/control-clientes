<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
 require_once("../db/ConexionDB.php");

 class CContratos extends ConexionDB{
 	var $num_contrato=0,
 		$id_cliente,
		$descripcion,
		$fecha_inicio,
		$fecha_termino,
		$total=0,
		$vigente;

 	function __construct($db)
	{
    	parent::__construct($db); /*invocar el constructor de la clase padre*/
 	}

 	//setters
 	function setNumContrato($id){
 		$this->num_contrato = $id;
 	}

 	function setIdCliente($n){
 		$this->id_cliente = $this->scapeString($n);
 	}

 	function setDescripcion($d){
 		$this->descripcion = $this->scapeString($d);
 	}

 	function setFechaInicio($fi){
 		$this->fecha_inicio = $this->getFechaToMysql($fi);
 	}

 	function setFechaTermino($ft){
 		$this->fecha_termino = $this->getFechaToMysql($ft);
 	}

 	function setVigente($vig){
 		$this->vigente = $vig;
 	}

 	function setTotal($tot){
 		$this->total = $tot;
 	}

 	//getters
 	function getNumContrato(){
 		return $this->num_contrato;
 	}

 	function getIdCliente(){
 		return $this->id_cliente;
 	}

 	function getDescripcion(){
 		return $this->descripcion;
 	}

 	function getFechaInicio(){
 		return $this->fecha_inicio;
 	}

 	function getFechaTermino(){
 		return $this->fecha_termino;
 	}

 	function getVigente(){
 		return $this->vigente;
 	}

 	function getTotal(){
 		return $this->total;
 	}

 	function getNextContrato(){
 		$sql = "SELECT MAX(num_contrato)+1 AS next FROM Contratos;";
 		$resultado = $this->query($sql);
 		if($resultado){
 			$data = mysqli_fetch_assoc($resultado);
 			return $data['next']; 			
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}
 	}

 	function agregarContrato(){
 	 	$sql = "INSERT INTO Contratos(id_cliente,fecha_inicio,total,descripcion) VALUES(".$this->id_cliente.",'".$this->fecha_inicio."',".$this->total.",'".$this->descripcion."');";
 	 	if($this->query($sql)){
 	 		$this->num_contrato = $this->getInsertId();
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}
 	}

 	function agregarDetalle($idprod,$cant,$precio,$total){
 		$sql = "INSERT INTO DetalleContrato(num_contrato,id_producto,cantidad,precio,total) VALUES(".$this->num_contrato.",".$idprod.",".$cant.",".$precio.",".$total.");";
 		if($this->query($sql)){
 			return true;
 		}
 		else
 			echo $this->getError();
 	}
 	

 	function darDeBaja(){
 		$sql = "UPDATE Contratos SET vigente=0, fecha_termino=NOW() WHERE num_contrato=".$this->num_contrato;
 		$this->update($sql);
 		return $this->getAffectedRows();
 	}

 	function getListaContratos(){
 		$sql = "SELECT ctrt.num_contrato, cli.id_cliente, cli.nombre, cli.apellido_paterno, cli.apellido_materno, ctrt.fecha_inicio, ctrt.fecha_termino, ctrt.descripcion, ctrt.total, ctrt.vigente  FROM Contratos ctrt, Clientes cli, DetalleContrato dc WHERE ctrt.id_cliente=cli.id_cliente AND ctrt.num_contrato=dc.num_contrato GROUP BY ctrt.num_contrato order by ctrt.num_contrato DESC;";
 	 	$resultado = $this->query($sql);
 	 	$cont=0; 	 	
 	 	$flag=true;
 	 	while($data = mysqli_fetch_assoc($resultado)){
 	 		if($flag){
 	 			$flag=false;
 	 			echo '<tr class="contrato sombreado" id="'.$data['num_contrato'].'">';
 	 		}
 	 		else{
 	 		  echo '<tr class="contrato" id="'.$data['num_contrato'].'">';
 	 		  $flag=true;
 	 		}
 	 			echo '<td>'.$data['num_contrato'].'</td>';
 	 			echo '<td>'.$data['id_cliente'].'</td>';
 	 			echo '<td>'.$data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'].'</td>';
 	 			echo '<td>'.$data['fecha_inicio'].'</td>';
 	 			echo '<td>'.$data['fecha_termino'].'</td>';
 	 			echo '<td>'.$data['descripcion'].'</td>';
 	 			echo '<td>'.$data['total'].'</td>'; 	 			
 	 			echo '<td>'.($data['vigente']==1?'<button class="btn btn-danger" name="btn_accion" title="Finalizar contrato" value="dardebaja:'.$data['num_contrato'].'"><i class="fa fa-power-off"></i></button>':'<i class="fa fa-times-circle-o" style="color:red"></i>').'</td>';
 	 		echo '</tr>';
 	 		$cont++;
 	 	} 	 	
 	 	echo '<div class="total">Contrato:</div>';
 	}

 	function getContratosDelCliente($idc, $clausula){
 		$sql = "SELECT ctrt.num_contrato, cli.id_cliente, cli.nombre, cli.apellido_paterno, cli.apellido_materno, ctrt.fecha_inicio, ctrt.fecha_termino, ctrt.descripcion, (select sum(dc.total)) as total, ctrt.vigente  FROM Contratos ctrt, Clientes cli, DetalleContrato dc WHERE ctrt.id_cliente=cli.id_cliente AND ctrt.num_contrato=dc.num_contrato AND cli.id_cliente=$idc AND ctrt.vigente=1 $clausula GROUP BY ctrt.num_contrato order by ctrt.num_contrato DESC;";
 	 	$resultado = $this->query($sql);
 	 	$cont=0; 	 	
 	 	$flag=true;
 	 	while($data = mysqli_fetch_assoc($resultado)){
 	 		if($flag){
 	 			$flag=false;
 	 			echo '<tr class="contrato sombreado" id="'.$data['num_contrato'].'">';
 	 		}
 	 		else{
 	 		  echo '<tr class="contrato" id="'.$data['num_contrato'].'">';
 	 		  $flag=true;
 	 		}
 	 			echo '<td>'.$data['num_contrato'].'</td>';
 	 			echo '<td>'.$data['id_cliente'].'</td>';
 	 			echo '<td>'.$data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'].'</td>';
 	 			echo '<td>'.$data['fecha_inicio'].'</td>';
 	 			echo '<td>'.$data['fecha_termino'].'</td>';
 	 			echo '<td>'.$data['descripcion'].'</td>';
 	 			echo '<td>'.$data['total'].'</td>';
 	 			$this->setTotal($data['total']);
 	 			echo '<td><i class="fa fa-check-square-o"></i></td>';
 	 		echo '</tr>';
 	 		$cont++;
 	 	} 	 	
 	 	echo '<div class="total">Contrato:</div>';
 	}

 	function getDetalle(){
 		$sql = 'SELECT c.num_contrato,p.id_producto, p.nombre,p.descripcion, dc.cantidad, dc.precio, dc.total
FROM productos p, contratos c, detallecontrato dc
WHERE p.id_producto=dc.id_producto
AND c.num_contrato=dc.num_contrato
AND c.num_contrato='.$this->num_contrato;
		$resultado = $this->query($sql);
		if($resultado){
			echo '<table class="table-items"><thead><tr><td>N.C.</td><td>IDProd</td><td>Nombre</td><td>Descripcion</td><td>Cant.</td><td>Precio</td><td>Total</td></tr></thead>';
			while($dato = mysqli_fetch_assoc($resultado)){
				echo "<tr>";
				echo "<td>".$dato['num_contrato']."</td>";
				echo "<td>".$dato['id_producto']."</td>";
				echo "<td>".$dato['nombre']."</td>";
				echo "<td>".$dato['descripcion']."</td>";
				echo "<td>".$dato['cantidad']."</td>";
				echo "<td>".$dato['precio']."</td>";
				echo "<td>".$dato['total']."</td>";
				echo "</tr>";
			}
			echo '</table>';
		}
 	}

 	function findById(){
 		$sql = "SELECT num_contrato, id_cliente, fecha_inicio, fecha_termino, vigente, descripcion FROM Contratos WHERE num_contrato=".$this->num_contrato;
 	 	$resultado = $this->query($sql);
 	 	if($resultado){
 	 		$dato = mysqli_fetch_assoc($resultado);
 	 		$this->setNumContrato($dato['num_contrato']);
 	 		$this->setIdCliente($dato['id_cliente']);
 	 		$this->setDescripcion($dato['descripcion']);
 	 		$this->setFechaInicio($dato['fecha_inicio']);
 	 		$this->setFechaTermino($dato['fecha_termino']);
 	 		$this->setVigente($dato['vigente']);
 	 		return true;
 	 	}
 	 	else
 	 	{ 	 		
 	 		return false;
 	 	}
 	}
 }
?>