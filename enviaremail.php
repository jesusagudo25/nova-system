<?php

    include "./instalacion/conexion.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    if($_SERVER['REQUEST_METHOD']=='POST'){
        
        $emailForm = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;

        $consulta = $conect->prepare("SELECT nombre,apellido_paterno, email FROM usuario WHERE email= :email  ");
        $consulta->execute(['email'=>$emailForm]);
        $count = $consulta->rowCount();

        if($count){
            $usuario = $consulta->fetch();

            $token = bin2hex(openssl_random_pseudo_bytes(16));

            $actualizarDatos = $conect->prepare("UPDATE usuario SET token = :token, fecha_token = CURDATE() WHERE email = :email;");
            $resultado = $actualizarDatos->execute([
            'token'=>$token,
            'email'=>$usuario['email']

            ]);

            $mail = new PHPMailer(true);

            $mail->AddEmbeddedImage('./assets/img/h1.png', 'enca');
            $mail->AddEmbeddedImage('./assets/img/fb_1.png', 'fb');
            $mail->AddEmbeddedImage('./assets/img/tw_1.png', 'tw');

            $tokenEncode = urlencode($token);

            // Nuestro mensaje en HTML
            $mensaje = "
            <!DOCTYPE html>
            <html lang=\"es\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">
                <meta name=\"x-apple-disable-message-reformatting\">
                <title></title>
                <style>
                    table, td, div, h1, p {font-family: Arial, sans-serif;}
                </style>
            </head>
            <body style=\"margin:0;padding:0;\">
                <table role=\"presentation\" style=\"width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;\">
                    <tr>
                        <td align=\"center\" style=\"padding:0;\">
                            <table role=\"presentation\" style=\"width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;\">
                                <tr>
                                    <td align=\"center\" style=\"padding:40px 0 30px 0;background:#cccccc;\">
                                        <img src=\"cid:enca\" alt=\"\" width=\"300\" style=\"height:auto;display:block;\" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style=\"padding:36px 30px 42px 30px;\">
                                        <table role=\"presentation\" style=\"width:100%;border-collapse:collapse;border:0;border-spacing:0;\">
                                            <tr>
                                                <td style=\"padding:0 0 0 0;color:#153643;\">
                                                    <h1 style=\"font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;\">Ha solicitado restablecer su contraseña</h1>
                                                    <p style=\"margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;\">No podemos simplemente enviarle su contraseña anterior. Se ha generado un enlace único para restablecer su contraseña. Para restablecer su contraseña, haga clic en el siguiente enlace y siga las instrucciones.</p>
                                                    <p style=\"margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;\"><a href=\"https://inventory-nova-systems.herokuapp.com/cambiar-pass.php?token=$tokenEncode\" style=\"color:#ee4c50;text-decoration:underline;\">Cambiar contraseña</a></p>
                                                </td>
                                            </tr>
            
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style=\"padding:30px;background:#ba91f7;\">
                                        <table role=\"presentatio\" style=\"width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;\">
                                            <tr>
                                                <td style=\"padding:0;width:50%;\" align=\"left\">
                                                    <p style=\"margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#fff;\">
                                                        &reg;Nova Systems, 2021.<br/><a href=\"https://www.instagram.com/jagudo25/\" style=\"color:#ffffff;text-decoration:underline;\">Enviar a correos no deseados</a>
                                                    </p>
                                                </td>
                                                <td style=\"padding:0;width:50%;\" align=\"right\">
                                                    <table role=\"presentation\" style=\"border-collapse:collapse;border:0;border-spacing:0;\">
                                                        <tr>
                                                            <td style=\"padding:0 0 0 10px;width:38px;\">
                                                                <a href=\"https://www.instagram.com/jagudo25/\" style=\"color:#ffffff;\"><img src=\"cid:tw\" alt=\"Twitter\" width=\"38\" style=\"height:auto;display:block;border:0;\" /></a>
                                                            </td>
                                                            <td style=\"padding:0 0 0 10px;width:38px;\">
                                                                <a href=\"https://www.instagram.com/jagudo25/\" style=\"color:#ffffff;\"><img src=\"cid:fb\" alt=\"Facebook\" width=\"38\" style=\"height:auto;display:block;border:0;\" /></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            ";
            
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            
            $mail->isSMTP();            
                                   
            $mail->Host = "smtp.gmail.com";
            
            $mail->SMTPAuth = true;                          
             
            $mail->Username = "seminario1fiec@gmail.com";                 
            $mail->Password = "Panama507";                  

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;                                   

            $mail->From = "seminario1fiec@gmail.com";
            $mail->FromName = "Nova Systems";

            $mail->addAddress($usuario['email'], $usuario['nombre'].' '.$usuario['apellido_paterno']);

            $mail->isHTML(true);

            $mail->Subject = "Recupera tu contraseña";
            $mail->Body = $mensaje;
            $mail->AltBody = "We cannot simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions.";

            $mail->CharSet = 'UTF-8';

            try {
                $mail->send();
                echo "Message has been sent successfully";
            } catch (Exception $e) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }

        }
    }
    else{
        header("Location: login.php");
    }

?>