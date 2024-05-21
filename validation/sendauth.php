<?php
function sendAuth($auth2, $mailSend, $name, $mailS, $mailB){
   // $FromName = "GIZ-Initiative";
        $to = "$mailSend";
        $subject =$mailS;;


        $mail -> addEmbeddedImage('../email_assets/logo.png', 'Uniben_logo');


        echo $message = "
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
                            <p>Knowledge for service.</p>
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
    
        // Always set content-type when sending HTML email
        $headers = "Uniben Staff Profile" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <noreply@uniben.edu>' . "\r\n";
        //$headers .= 'Cc: myboss@example.com' . "\r\n";

        mail($to,$subject,$message,$headers);
}
?>