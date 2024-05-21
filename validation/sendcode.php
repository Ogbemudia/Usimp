
    
    <?php
    //errors
       
    //Include required phpmailer files
require 'phpmailer/includes/PHPMailer.php';
require 'phpmailer/includes/SMTP.php';
require 'phpmailer/includes/Exception.php';
//Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendAuth($auth2, $mailSend, $name, $mailS, $mailB){
    //Create instance of phpmailer
    $mail = new PHPMailer();
    //Set mailer to use smtp
    $mail -> isSMTP();
    //Define smtp host
    $mail -> Host = "smtp.gmail.com";
    //Enable smtp authentication
    $mail -> SMTPAuth = "true";
    //Set type of encription (ssl/tls)
    $mail -> SMTPSecure = "tls";
    //Set port to connect smtp
    $mail -> Port = "587";
    //Set gmail username
    $mail -> Username = "kingshome40@gmail.com";
    //Set gmail password
    $mail -> Password = "phcegssrvuhwkjxg";
    $mail -> isHTML(true);
    //Add reciving email
    $mail -> addAddress($mailSend);
    $mail->From     = "noreply@Uniben.edu";
    $mail->FromName = "Uniben Staff Profile";

        //Set email subject
        $mail -> Subject = $mailS;
        $emailFrom1="noreply@uniben.edu";
        //Set sender email
        //$mail -> setFrom("noreply@uniben.edu", "GIZ");
       // $mail -> addEmbeddedImage('../logo/glogo.jpeg', 'Uniben_logo');
        //Set email body
        $mail -> addEmbeddedImage('../email_assets/logo.png', 'Uniben_logo');
        /* $mail -> addEmbeddedImage('../email_assets/Profile confirmed.png', 'profile_pic'); */
        $mail -> Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$mailS</title>
        </head>
        <body>
            <div style='background:gray; width: 100%; hieght: 100px;'>
                <div style = 'background:white; margin-left:17%; margin-right:17%; padding:5%; width:50%; hieght: 18%; text-align: center;'>
                    <div><img src='cid:Uniben_logo' style='width: 40px; height: 30px'> </div>
                    <hr style='height:5px; margin-top: 2%'>
                </div>
                
                <div style = 'background:white; margin-left:17%; margin-right:17%; padding:5%; width:50%; hieght: 50%; text-align: center;'>
                        <img src='cid:profile_pic' alt=''>
                        <h1>$mailS</h1>
                            <h2>Welcome back $name</h2>
                            <h4>
                                $mailB <span style='color:#1A1817; font-weight: 750;'>$auth2</span> <br />
                                This code will expire after 10mins.
                            </h4>
                            <hr style='height:5px; margin-top: 5%'>
                <div/>
                
                    <footer style = 'background:white; margin-left:17%; margin-right:17%; padding:5%; width:50%; hieght: 29%; text-align: center;'>
                        <div>
                            <img src='cid:Uniben_logo' style='width: 40px; height: 30px'>
                        </div>
                        <div>
                            <p>Knoledge for service.</p>
                        </div>
                        <hr />
                        <div>
                            <a href='#'><p>Contact Us</p></a>
                        </div>
                    </footer>
               
            </div>    
        </body>
        </html>
        ";
    
   $mail->IsHTML(true);
        
                
        //if sent successful
        if($mail -> Send()){

        //Close smtp connection
        $mail -> smtpClose();
        return true;
    }

}
   
    ?>

    
    