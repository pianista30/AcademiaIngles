<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedinAd"]) || $_SESSION["loggedinAd"] !== true ){
    header("location: logoutAd.php");
    
}


// Check if the user is logged in, if not then redirect to login page
if($_SESSION["roll"] !== 1){
    header("location: logoutAd.php");
}
 
// Include config file
require_once "../Require/config.php";


//=================================================================================================================================================================================
date_default_timezone_set('America/Mexico_City');

   // $peri = "";  
    $per = "";  
    $mes = date("n"); 
    $year = date("Y"); 

if ($mes >= 1 && $mes <= 6){
    $per = 1;
    //$peri = "Periodo: ".$per."-".$year;
}else if($mes >= 8 && $mes <= 12){
    $per = 2;
    //$peri = "Periodo: ".$per."-".$year;
    
}


$periodoActu = $per."-".$year;


if ($stmt3 = $link->prepare("SELECT  periodo FROM periodoactual")) {
    $stmt3->execute();

    /* bind variables to prepared statement */
    $stmt3->bind_result($periActuBD);

    /* fetch values */
$stmt3->fetch();

    /* close statement */
    $stmt3->close();
}



if( $periActuBD !== $periodoActu ){
    
    $sql2 = "UPDATE periodoactual SET periodo ='$periodoActu', periodoAnterior ='$periActuBD';";
    
    if($stmt4 = mysqli_prepare($link, $sql2)){
        $stmt4->execute();
    }
    //echo("<p><script>alert('¡Periodo actualizado!');</script></p>");
}
//=================================================================================================================================================================================


 
    // Close connection
    mysqli_close($link);


//=========================================================================================================



//====================================================================================================
$param_contrase = "";


require_once 'utilities/certi.entidad.php';
require_once 'utilities/certi.model.php';

// Logica
$alm = new certi();
$model = new certiModel();

if(isset($_REQUEST['action']))
{
    
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$alm->__SET('id',               $_REQUEST['id']);
			$alm->__SET('liberacion',    $_REQUEST['liberacion']);
            $alm->__SET('descripcion',      $_REQUEST['descripcion']);

			$model->Actualizar2($alm);
			header('Location: ModLibera.php');
			break;
            
            
        case 'registrar':
			$alm->__SET('liberacion',    $_REQUEST['liberacion']);
            $alm->__SET('descripcion',      $_REQUEST['descripcion']);

			$model->Registrar2($alm);
			header('Location: ModLibera.php');
			break;
            


		case 'editar':
			$alm = $model->Obtener2($_REQUEST['id']);
			break;
    }
    
}


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modificar Tipo de Liberaciones</title>
    
    <link rel="stylesheet" href="../bootstrap/4.5.3/dist/css/bootstrap.min.css" 
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" 
          crossorigin="anonymous">
    
        <script src="../jquery/3.5.1/jquery-3.5.1.slim.min.js" 
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
            crossorigin="anonymous"></script>
    
        <script src="../bootstrap/4.5.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" 
            crossorigin="anonymous"></script>  
    
    <link rel="icon" href="../imagenes/itsl2.png">
    
    <style type="text/css">
        body  { 
			  
			  background-color: ghostwhite;
			  }
		header {
              background-color: #21476F;

              }

        .logo{
            width: 50%;
            height: auto;
            padding-top: 30px;
        }
        .redes{
            padding-left: 10px;
            color: white;
            font-size: 13px;
            line-height: 2;
                 }
        .tituloRedes{
            color: lightgray;
            padding-left: 10px;
            font-size: 16px;
        }
        .acerca{
            color: white;
            font-size: 13px;
            padding-left: 10px;
            line-height: 1.7;
               }
        .pie-letras{
            color: white;
            font-size: 13px;
            line-height: 1.7;
            padding-bottom: 20px;
         }
        .iconos{
            width: 18px;
            height: auto;
            padding-bottom: 3px;
        }
        .bordes{
            border-right-style:dotted;
            border-right-color:lightgray;
            border-right-width: 1px;
            margin: 20px 0 20px 0;
            height: 150px;
        }
        
        
        /*para pantallas de PC*/
        @media (max-width: 1100px){

            
        }
        
        
        /*para pantallas de PC*/
        @media (max-width: 992px){
 
        }
        /* Para tablets*/
        @media screen and (max-width: 768px) {
            
        }
        /* Para tablets*/
        @media screen and (max-width: 616px) {

            
        }
        
        /*Para dispositivos moviles*/
        @media screen and (max-width: 400px) {
            
            
        }
        div.ex3 {
          background-color: black;
          width: 100%;
          height:400px;
          overflow: auto;
        }
        
    </style>
    
        <script>
        

        // scroll position.
            $(window).scroll(function () {
                sessionStorage.scrollTop = $(this).scrollTop();
            });
            $(document).ready(function () {
                if (sessionStorage.scrollTop != "undefined") {
                    $(window).scrollTop(sessionStorage.scrollTop);
                }
            });

    </script>
    

    
</head>
<body>
    <header>
          <div class="container-fluid">

            <div class="row">
                
                

                         
                    <div class="col-sm-12" style="text-align: center;">


                        <div class="btn-group" role="group">
                            
                            <a href="ModLibera.php" class="btn btn-outline-light active" role="button" >Modificar / Agregar Tipo de Liberación</a>
                            
                            <a href="Administrador.php" class="btn btn-outline-light" role="button">Regresar</a>
                            
                            <a href="logoutAd.php" class="btn btn-outline-light" role="button">Cerrar Sesión</a>
                            
                            <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target=".bd-example-modal-lg"> ? </button>

                        </div>    
                    </div>
                
                
                


            </div>
        </div>
</header>
    
                        <!-- Large modal -->
        

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Instrucciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="padding:35px;">
                  
                <p>Esta página te permite registrar el nombre de algún tipo de liberación para los estudiantes, lo cual después podrá ser utilizado como opción en el registro y actualización de la información de los alumnos. <br><br>Para registrar un nuevo tipo de liberación, solo tienes que poner el nombre en el cuadro de texto denominado "Tipo Liberación" y poner una breve descripción del mismo. Presiona el botón azul para guardar cambios. <br>Para modificar alguno previamente registrado, solo debes seleccionarlo de la tabla de la derecha, dándole clic al botón verde que le corresponde. Esto cargará la información en la tabla de la izquierda donde podrás hacer modificaciones. Presiona el botón azul para guardar cambios. </p>


                      
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

    <div class="container">
        <div class="row"> 
            <div class="col-sm-12" style="padding-top:30px; font-size:20px;">
                
                <?php foreach($model->Listar2() as $r): ?> 
                
                <p><b>Bienvenido: </b><?php echo $r->__GET('titulo')." ".$r->__GET('nombre')." ".$r->__GET('paterno')." ".$r->__GET('materno'); ?></p>
                
                <?php endforeach; ?>
                
                <hr>
                 
            </div>
           <hr>
        <div class="col-sm-6" style="font-size:16px;">
   
              <div>  
                  <p>Modificar Tipo de Liberación</p>
                <form action="?action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" onsubmit="return confirm('Presiona OK para continuar.');">
                    
                    
                    
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />
                    
                    <table class="table table-bordered table-dark table-sm"  >
                       
                        <tr>
                            <th>
                                
                               Tipo de Liberación
                            </th>
                            <th>
                                
                               Función
                            </th>
                            </tr>
                        <tr>
                            <td><input type="text" name="liberacion" class="form-control" value="<?php  echo $alm->__GET('liberacion');?>" required></td>
                            <th style="text-align:right;">
                                <a href="ModLibera.php" class="btn btn-danger btn-sm">Limpiar campos</a>
                               <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                            </th>
                        </tr>
                        <tr>
                            
                            <th colspan="2">Descripción:  </th>
                            
                        </tr>
                        
                        <tr>
                            
                            <td rowspan="2" colspan="2">  
                                 <textarea class="form-control" rows="5" id="descripcion" name="descripcion"><?php  echo $alm->__GET('descripcion');?></textarea>
                            </td>
                            
                        </tr>
                    </table>
                </form>
				</div>
            
            
         </div>

           
        <div class="col-sm-6 "style="font-size:16px;">
            
           
            <p>Tabla Certificaciones</p>
            <div>
                <input type="text" id="myInput" class="form-control" placeholder="Filtrar por Nombre...">
            </div>
				
				
             <div class="ex3">  
			 
                <table class="table table-bordered table-dark table-sm"  id="myTable" >
				
                    <thead style="text-align:center;">
					
                        <tr>
                            <th>Tipo de Liberación</th>
                            <th>ID </th>
                            <th>Selector</th>


                        </tr>
                    </thead>
                    <?php foreach($model->Listar3() as $r): ?>
                    <tbody id="tab-id" style="text-align:center;">
                        <tr>
                            <td><?php echo $r->__GET('liberacion'); ?></td>
                            <td><?php echo $r->__GET('id'); ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="?action=editar&id=<?php echo $r->id; ?>">Seleccionar</a>
                            </td>
                        </tr>
                    </tbody>
                        
                    <?php endforeach; ?>
                </table>     
              </div>
           
        </div>

    </div>
 </div>
   

    
    <hr>
    
    <div class="container-fluid" style="padding-top:300px;">
        <hr>
         <div class="row" style="background:#0a6d7a; ">
            <div class="col-md-3 bordes" >
                
            </div>
            <div class="col-md-6 bordes" >

                <div class="row">
                    <div class="col-md-6" >
                        
                    </div>

                    <div class="col-md-6 " >
                         <p class="tituloRedes">Siguenos en redes sociales</p>
                         <p class="redes">
                            <img class="iconos" src="../imagenes/face.png">&emsp;Facebook<br>
                            <img class="iconos" src="../imagenes/insta.png">&emsp;Instagram<br>
                            <img class="iconos" src="../imagenes/twi.png">&emsp;Twitter<br>
                            <img class="iconos" src="../imagenes/yt.png">&emsp;YouTube<br>
                        </p>
                    </div>
                </div>
            </div>


        <div class="col-md-3 tituloRedes" style=" margin: 20px 0 20px 0;">
            <p style="color: lightgray; padding-left:10px;">Acerca de está Página Web</p>
            <p class="acerca">
                Cookies<br>
                Privacy policy<br>
            </p>
        </div>
    </div>

    </div>


    <footer>
            <div class="container-fluid">
             <div class="row" style="background: black; color:white; text-align:center; ">
                <div class="col-md-3 bordes ">
                    <p><img src="../imagenes/itslnobreLargo.png" style="width:60%; height:auto; padding-top:20px; "></p>
                    <p class="pie-letras">© 2020 ITSL - English Academy</p>
                </div>
                <div class="col-md-6 bordes">

                </div>
                <div class="col-md-3 ">
                    <p></p>
                </div>
            </div>


        </div>


    </footer>

    
        <script>

    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tab-id tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
        
        </script>
    
    
</body>
</html>