<?php 

error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-Type: text/html; Charset=UTF-8");
date_default_timezone_set('America/Mexico_City');

session_start();

if (isset($_SESSION['codeMd5']) && !empty($_SESSION['codeMd5'])) {

	############### Inicia Consulta a CORREO Existente ###############
	$con = new SQLite3("../data/nom035.db") or die("Problemas para conectar!");

	$busCorreo = $con -> query("SELECT codeMd5 FROM registroUsr WHERE codeMd5 = '$_SESSION[codeMd5]'");

	while ($usrCrypt = $busCorreo->fetchArray()) {
		$resBus = $usrCrypt['codeMd5'];
	}

	$con -> close();
	############### Termina Consulta a CORREO Existente ###############

	$resBus = (isset($resBus)) ? $resBus : '';

	############### Inicia Comprobación de si existe correo ###############
	if ($resBus === $_SESSION['codeMd5']) {

		$ocultar = '';
		$fechaIniCap = date("d-m-Y");
		$horaIniCap = date("g:i:s a");
		$fechaHoraIni = $fechaIniCap.' '.$horaIniCap;
		$_SESSION['fechaHoraIni'] = $fechaHoraIni;

	}else{
		$ocultar = 'style="display: none;"'; // --> recuerda agregar esto en "container"
		echo "<script> window.location='../error/alerta.aspx?error=404';</script>";
	}

}else{
	$ocultar = 'style="display: none;"'; // --> recuerda agregar esto en "container"
	echo "<script> window.location='../error/alerta.aspx?error=404';</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>NOM-035-STPS-2018</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/all.css">
	<link rel="stylesheet" href="../css/animate.css">
	<link rel="stylesheet" href="../css/style.css">

	
</head>
<body oncontextmenu='return false' onkeydown='return false'>
<!-- <body> -->

	 <div class="container-fluid animated fadeIn" <?php echo $ocultar; ?>>
	 	<div class="abs-center">
	 		
		 		<div class="bg-muted p-3" style="width: 800px;">
		 			<div class="row">
		 				<div class="col-md-10 mx-auto text-center jumbotron">
				 			<h1>Factores de riesgo psicosocial</h1>
				 			<h3>NOM-035-STPS-2018</h3>
				 			<!-- <img src="../img/cerebro.svg" class="img-fluid" style="width: 380px;"> -->
				 			<br>
				 			<div>

				 			<?php 
									// Conexión a la base de datos
									$con = new SQLite3("../data/nom035.db") or die("Problemas para conectar!");

									// MANUAL: Configuración de cuestionarios activos
									// Puedes cambiar estos valores manualmente a true o false para activar/desactivar cuestionarios

									require_once "activarCuestionarios.php";

									// CONSULTA BASE DE DATOS: Verificar si los cuestionarios ya han sido completados

									// Guía I
									$busUsrRUno = $con->query("SELECT codeMd5_R1a FROM nom035R1a WHERE codeMd5_R1a = '$_SESSION[codeMd5]'");
									$codeMd5_R1a = $busUsrRUno->fetchArray() ? true : false;

									// Guía II
									$busUsrRDos = $con->query("SELECT codeMd5_R2a FROM nom035R2a WHERE codeMd5_R2a = '$_SESSION[codeMd5]'");
									$codeMd5_R2a = $busUsrRDos->fetchArray() ? true : false;

									// Guía III
									$busUsrRTres = $con->query("SELECT codeMd5_R3a FROM nom035R3a WHERE codeMd5_R3a = '$_SESSION[codeMd5]'");
									$codeMd5_R3a = $busUsrRTres->fetchArray() ? true : false;

									// Cerrar la conexión a la base de datos
									$con->close();

									// Lógica para mostrar/ocultar los cuestionarios
									// Un cuestionario se mostrará si está activado manualmente y no ha sido completado
									$ocultarOptMenuUno = ($cuestionarioGuia1Activo && !$codeMd5_R1a) ? '' : 'style="display: none;"';
									$ocultarOptMenuDos = ($cuestionarioGuia2Activo && !$codeMd5_R2a) ? '' : 'style="display: none;"';
									$ocultarOptMenuTres = ($cuestionarioGuia3Activo && !$codeMd5_R3a) ? '' : 'style="display: none;"';

									$acordeonUno = '';
									$acordeonDos = '';
									$acordeonTres = '';
								
									if ($cuestionarioGuia1Activo && !$codeMd5_R1a) {
										// Mostrar el acordeón de la Guía I
										$acordeonUno = 'class="collapse show"';
										$acordeonDos = 'class="collapse"';
										$acordeonTres = 'class="collapse"';
									} elseif ($cuestionarioGuia2Activo && !$codeMd5_R2a) {
										// Mostrar el acordeón de la Guía II
										$acordeonUno = 'class="collapse"';
										$acordeonDos = 'class="collapse show"';
										$acordeonTres = 'class="collapse"';
									} elseif ($cuestionarioGuia3Activo && !$codeMd5_R3a) {
										// Mostrar el acordeón de la Guía III
										$acordeonUno = 'class="collapse"';
										$acordeonDos = 'class="collapse"';
										$acordeonTres = 'class="collapse show"';
									} else {
										// Si no falta ningún cuestionario por completar, todos colapsados
										$acordeonUno = 'class="collapse"';
										$acordeonDos = 'class="collapse"';
										$acordeonTres = 'class="collapse"';
									}

									// Verificación de cuestionarios activos y completados
									$totalCuestionariosActivos = 0;
									$totalCuestionariosCompletados = 0;

									// Contar los cuestionarios activos y completados
									if ($cuestionarioGuia1Activo) {
										$totalCuestionariosActivos++;
										if ($codeMd5_R1a) {
											$totalCuestionariosCompletados++;
										}
									}

									if ($cuestionarioGuia2Activo) {
										$totalCuestionariosActivos++;
										if ($codeMd5_R2a) {
											$totalCuestionariosCompletados++;
										}
									}

									if ($cuestionarioGuia3Activo) {
										$totalCuestionariosActivos++;
										if ($codeMd5_R3a) {
											$totalCuestionariosCompletados++;
										}
									}

									// Lógica para el mensaje de finalización
									// Mostrar el mensaje si todos los cuestionarios activos han sido completados
									if ($totalCuestionariosActivos > 0 && $totalCuestionariosActivos === $totalCuestionariosCompletados) {
										$cuestionarioFinal = 'Has concluido satisfactoriamente los cuestionarios';
										$ocultarCuestionario = '';
									} else {
										$cuestionarioFinal = '';
										$ocultarCuestionario = 'style="display: none;"';
									}

							?>
                            <div class="accordion" id="menuGuias">
                                
                                <!-- Cuestionario Guía I -->
                                <div class="card" <?php echo $ocultarOptMenuUno; ?>>
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#guia01" aria-expanded="true">
                                                Guía de Referencia I
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="guia01" <?php echo $acordeonUno; ?> data-parent="#menuGuias">
                                        <div class="card-body">
                                            Cuestionario para identificar a los trabajadores que fueron sujetos a acontecimientos traumáticos severos.
                                            <br>
                                            <img src="../img/cuestionario.svg" class="img-fluid">
                                            <div class="mt-3">
                                                <a href="../guia01/diagnostico.aspx" class="btn btn-secondary">Iniciar cuestionario</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cuestionario Guía II -->
                                <div class="card" <?php echo $ocultarOptMenuDos; ?>>
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#guia02" aria-expanded="false">
                                                Guía de Referencia II
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="guia02" <?php echo $acordeonDos; ?> data-parent="#menuGuias">
                                        <div class="card-body">
                                            Cuestionario para identificación y análisis de los factores de riesgo psicosocial.
                                            <br>
                                            <img src="../img/cuestionario2.svg" class="img-fluid">
                                            <div class="mt-3">
                                                <a href="../guia02/diagnostico.aspx" class="btn btn-secondary">Iniciar cuestionario</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cuestionario Guía III -->
                                <div class="card" <?php echo $ocultarOptMenuTres; ?>>
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#guia03" aria-expanded="false">
                                                Guía de Referencia III
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="guia03" <?php echo $acordeonTres; ?> data-parent="#menuGuias">
                                        <div class="card-body">
                                            Cuestionario para identificación y análisis de los factores de riesgo psicosocial y evaluación del entorno organizacional en los centros de trabajo.
                                            <br>
                                            <img src="../img/cuestionario3.svg" class="img-fluid">
                                            <div class="mt-3">
                                                <a href="../guia03/diagnostico.aspx" class="btn btn-secondary">Iniciar cuestionario</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mensaje de finalización de cuestionarios -->
                                <div <?php echo $ocultarCuestionario; ?>>
                                    <h3 class="text-danger"><?php echo $cuestionarioFinal; ?></h3>
                                </div>
                            </div>
                        </div>
                        <br>
                        <a href="../" class="btn btn-secondary btn-lg form-md-control">Click aquí para regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/forms.js"></script>
    <script src="../js/info.js"></script>
</body>
</html>


