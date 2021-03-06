<?php


// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();
    
}
// Include config file
require_once "../Require/config.php";


require_once('../PHPMailer/PHPMailerAutoload.php');



 
// Define variables and initialize with empty values
$numeroControl = $email = $confir_email = $cod = "";
$numeroControl_err = $email_err = "";
 
// Processing form data when form is submitted


if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if numero de control is empty
    if(empty(trim($_POST["numeroControl"]))){
        $numeroControl_err = "* El campo Número de Control está vacío.";
    } else{
        $numeroControl = trim($_POST["numeroControl"]);
    }
    
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "* ¡Olvidaste introducir tu correo electrónico!.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validate credentials
    if(empty($numeroControl_err) && empty($email_err)){
        // Prepare a select statement
        $sql = "SELECT id, numeroControl, email FROM alumnos WHERE numeroControl = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_numeroControl);
            
            // Set parameters
            $param_numeroControl = $numeroControl;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify contraseña
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $numeroControl, $confir_email);
                    if(mysqli_stmt_fetch($stmt)){
                        if(strcmp($email, $confir_email) == 0){
                            
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $email;
                            $_SESSION["numeroControl"] = $numeroControl;
                            
                            // email is correct, so send email
                            
                            $codigo = rand(100000, 999999);
                            
                            $sql = "INSERT INTO codigos_contra (identificador,  codigo)
                            VALUES ('{$numeroControl}', '{$codigo}')";

                            if ($link->query($sql) === TRUE) {
                              echo "New record created successfully";
                            } else {
                              echo "Error: " . $sql . "<br>" . $conn->error;
                            }

                            
                            $mail = new PHPMailer();
                            $mail->isSMTP();
                            $mail->SMTPAuth = true;
                            $mail->SMTPSecure = 'ssl';
                            $mail->Host = 'smtp.gmail.com';
                            $mail->Port = '465';
                            $mail->isHTML();
                            $mail->Username = 'academiainglesitsl@gmail.com';
                            $mail->Password = 'a98450153_-';
                            $mail->SetFrom('no-replay@itsl.edu');
                            $mail->Subject = 'No-contestar Código de seguridad Academia de Inglés ITSL';
                            $mail->Body = 'No-contestar\nEl código para reiniciar tu contraseña es: '.$codigo.'\n\n\nNo compartas tu código con nadie.';
                            $mail->AddAddress($email);
                            
                            //$mail->Send();
                            
                            header("location: codigoContra.php");
                            
                            
                        } else{
                            // Display an error message if password is not valid
                            $email_err = "El correo electrónico no coincide con el registrado en el sistema.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $numeroControl_err = "No se encontro un registro con ese número de control.";
                }
            } else{
                echo "Oops! Algo salio mal. Por favor intenta más tarde.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSL - Academia de Inglés</title>
    
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
       
		body{
            background-color: #F39C12;
            
            }
        .logo{
            width: 50%;
            height: auto;
            padding-top: 30px;
        }
        header{
            background-color: #000000;
               }

        footer{
            background: #000000;
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
        @media (max-width: 992px){
            .logo{
                width: 50%;
                height: auto;

            }
            
            
        }
        /* Para tablets*/
        @media screen and (max-width: 768px) {
            .logo{
                width: 80%;
                height: auto;
                padding: 10px 0 10px 0;
                
            }

        }
        
        /*Para dispositivos moviles*/
        @media screen and (max-width: 400px) {
            
            .logo{
                width: 80%;
                height: auto;
                padding: 10px 0 10px 0;
                
            }
            
        }




    </style>
</head>


<body>

    <header>
        
            
        <div class="container-fluid">

            <div class="row">
                
                
                <div class="col-lg-8 col-xs-12 col-sm-12 col-md-12">
                    <img class="logo" src="../imagenes/itslnobreLargo.png" >
                </div>
                <div class="col-sm-4" style="text-align:center; padding-top:20px; ">
                    <img src="../imagenes/TecNMwhite.png" width="150px" height="auto" >
                </div>
                
                
                
                
                <div class="row">
                    <div class="col-lg-10">
                    
                    </div>
                    <div class="col-lg-2" >


                        <div class="btn-group dropdown" role="group">

                            <a href="../home.php" class="btn btn-outline-warning" role="button" aria-pressed="true" >&nbsp;&nbsp;Inicio&nbsp;</a>

                            <div class=" btn-group dropdown" role="group" > 

                                <button class="btn btn-outline-warning dropdown-toggle active" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                    Estudiantes
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                    <a class="dropdown-item" href="inscripcionAlumn.php" "dropdown-item">Inscripción</a>
                                    <a class="dropdown-item" href="reinscripcionVeri.php" "dropdown-item">Reinscripción</a>
                                    <a class="dropdown-item" href="alumnos.php" "dropdown-item">Consulta calificaciones</a>
                                    <a class="dropdown-item active" href="recupeContra.php" "dropdown-item">Recuperación Contraseña</a>
                                </div>

                            </div >

                            <div class=" btn-group dropdown " role="group" > 

                                <button class="btn btn-outline-warning dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                    Docentes
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenu3">
                                    <a class="dropdown-item" href="../Docentes/IniciarSesionDo.php" "dropdown-item">Docentes</a>
                                </div>

                            </div >

                            <div class=" btn-group dropdown " role="group" > 

                                <button class="btn btn-outline-warning dropdown-toggle" type="button" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                    Administración
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenu4">
                                    <a class="dropdown-item" href="../Administradores/IniciarSesionAd.php" "dropdown-item">Administración</a>
                                </div>

                            </div >

                        </div>    
                    </div>
                
                
                </div>


            </div>
        </div>

    </header>



    
    
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm-4" style="text-align:center; padding:130px 0 0 0; ">
                <img src="../imagenes/contra1.png" width="200px" height="auto" >

            </div>
            
            <div class="col-sm-4" style="padding: 50px 0 100px 0; font-size: 14px;">
                <div class="p-4 mb-2 bg-light text-dark">
                
                    <p style="font-size:24px;">Recuperación de contraseña</p>
                    <p>Introduce tu número de control y correo electrónico asociados a tu registro.</p>
                    
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        
                        <div class="form-group <?php echo (!empty($numeroControl_err)) ? 'has-error' : ''; ?>">
                            <label>Número de Control</label>
                            <input type="text" name="numeroControl" class="form-control" value="<?php echo $numeroControl; ?>">
                            <span class="text-danger"><?php echo $numeroControl_err; ?></span>
                        </div>    
                        
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Correo Electrónico</label>
                            <input type="email" name="email" class="form-control">
                            <span class="text-danger"><?php echo $email_err; ?></span>
                            
                        </div>
                        
                        <div class="form-group">
                            <input type="submit" class="btn btn-warning btn-block" value="Enviar Código de seguridad">
                            
                        </div>
                        
                        
                    </form>
                    
                    
                </div>
                
            </div>
            <div class="col-sm-4" style="text-align:center; padding:130px 0 0 0; ">
                <img src="../imagenes/contra2.png" width="200px" height="auto" >

            </div>
            
        </div>
        
    </div>
    
    
    
    
    
    
    
    
    
    <!-- Large modal -->


    <div class="modal fade bd-example-modal-lg" id="modal1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="color:black">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Introduce tu código de seguridad</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

              <div class="container">
                <div class="row">
                    <div class = col-sm-12>


                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                            <div class="modal-body">
                                <div class="form-group row <?php echo (!empty($old_cod_err)) ? 'has-error' : ''; ?>">
                                    <label>Código de seguridad</label>
                                    <input type="number" name="cod" class="form-control" value="<?php echo $cod; ?>">
                                    <span class="help-block text-danger"><?php echo $old_cod_err; ?></span>
                                </div>
                                
                            </div>
                            <div class="form-group row modal-footer">
                                <input type="submit" name="btnPostMe2" class="btn btn-primary" value="Guardar cambios">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </form>


                    </div> 
                </div> 
            </div> 

        </div>
      </div>
    </div>
    
    
    
    

     <div class="container-fluid">
         <div class="row" style="background:#0a6d7a; ">
            <div class="col-md-3 bordes" >
                <p><br><br><br><br><br><br></p>
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
                    <p><br><br><br><br><br></p>
                </div>
                <div class="col-md-3 ">
                    <p></p>
                </div>
            </div>


        </div>


    </footer>
    
    <script>
        function upperCaseF(a){
            setTimeout(function(){
            a.value = a.value.toUpperCase();
            }, 1);
        }
    </script>
    

</body>
</html>