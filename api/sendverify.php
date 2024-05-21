
    
    <?php
    //errors
    require_once('pdf/fpdf.php');
    require 'phpmailer/includes/PHPMailer.php';
    require 'phpmailer/includes/SMTP.php';
    require 'phpmailer/includes/Exception.php'; 
    
    
    
    //Define name spaces
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception; 

//function sendAuth($mailSend, $name, $mailS, $mailB, $file_name, $fromName){
function    sendAuth($mailSend, $mailS, $mailB, $fromName, $bursaryNo1, $date1, $title, $surname, $first_name, $dept, $leaveDay, $leaveMonth, $year, $nextYear, $noLeaveDays, $endDate, $resumptionDate, $signature, $fullName, $designation){



    /* generate pdf file */
    
    // Instanciation of inherited class
     $pdf = new FPDF();
     
 

    //$pdf = new PDF();
    $pdf->SetLeftMargin(25);
    $pdf->SetRightMargin(23);
    
    $pdf->AddPage();
    // Logo
    $pdf->Image('../uploads/logo.png',95,6,20);
    // Arial bold 15
    $pdf->SetFont('Arial','B',15);
    $pdf->Ln(20);
    // Move to the right
    $pdf->Cell(80);
    // Title
    $pdf->Cell(10,10,'UNIVERSITY OF BENIN BENIN CITY, NIGERIA',0,0,'C');
    // Line break
    $pdf->Ln(20);
    $pdf->SetFont('Times','B',12);
    $pdf->cell(120  ,9, 'Our Ref. REG/'.$bursaryNo1, 0,0);
    $pdf->cell(35  ,9, 'Date: '.$date1, 0,0);
    $pdf->Ln(10);
    $pdf->SetFont('Times','',12);
    $pdf->cell(120  ,9, $title.' '.$surname.' '.$first_name, 0,0);
    $pdf->Ln(6);
    $pdf->cell(120  ,9, $dept, 0,0);
    $pdf->Ln(6);
    $pdf->cell(120  ,9, 'University of Benin', 0,0);
    $pdf->Ln(6);
    $pdf->cell(120  ,9, 'Benin city', 0,0);
    $pdf->Ln(10);
    $pdf->SetFont('Times','B',12);
    $pdf->cell(120  ,9, 'Dear '.$title.' '.$first_name.',', 0,0);
    $pdf->Ln(10);
    $pdf->SetFont('Times','B',12);
    $pdf->cell(165,10,'RE: ANNUAL LEAVE',0,0,'C');
    $pdf->Ln(10);
    $pdf->SetFont('Times','',12);
    $pdf->MultiCell(165  ,6,'With reference to the letter REG/RO/C 261 dated 30th July, 2003 on the above subject, You are hereby notified that your annual leave is due from '.$leaveDay.' '.$leaveMonth.', '.$year.'',0,0);
   
    //$pdf->Ln(6);
    $pdf->SetFont('Times','',12);
    $pdf->MultiCell(165  ,6,'Consequently, approval is hereby given to you to proceed on your '.$year.'/ '. $nextYear.' Annual Leave of '.$noLeaveDays.' working days with effect from '.$leaveDay.' '.$leaveMonth.', '.$year.'
    ',0,0);
    $pdf->cell(80  ,9, 'The leave will expire on ');
    $pdf->SetFont('Times','B',12);
    $pdf->cell(24  ,9, $endDate, 0,0);
    $pdf->SetFont('Times','',12);
    $pdf->cell(20  ,9, ' and you are expected to');
    $pdf->Ln(5);
    $pdf->cell(80  ,9, 'Resume duty on');
    $pdf->SetFont('Times','B',12);
    $pdf->cell(24  ,9, $resumptionDate, 0,0);
    $pdf->Ln(10);
    $pdf->SetFont('Times','',12);
    $pdf->MultiCell(165  ,6,'Please note that your physical is subject to the approval of your Head of Department. Every staff is required to take his/her leave during the year that it falls due. Any leave or part thereof not taken during the year shall be forfeited unless otherwise deferred.',0,0);
    $pdf->Ln(6);
    $pdf->MultiCell(165  ,6,'It would be appreciated if you could leave your contact address with your Head of Department before you proceed on leave.',0,0);
    $pdf->Ln(6);
    $pdf->MultiCell(165  ,6,'On behalf of the University, I wish you and members of your family a happy and enjoyable leave.',0,0);
    $pdf->Ln(10);
    
    $pdf->cell(165,10,'Yours sincerely',0,0,'C');
    $pdf->Ln(15);
   
    $pdf->Image('../uploads/'.$signature,92,224,30);
    $pdf->Ln(10);
    $pdf->SetFont('Times','B',12);
    $pdf->cell(165,10,$fullName,0,0,'C');
    $pdf->Ln(5);
    $pdf->cell(165,10,$designation,0,0,'C');
    $pdf->Ln(5);
    $pdf->cell(165,10,'For: Registrar',0,0,'C');
    ob_start(); 
    $pdf->Output('F', 'php://output');
    $pdfContent = ob_get_contents();
    //$pdfContent = ob_get_clean();
    ob_end_clean();
    
    
    
    
    
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
    $mail->From     = "noreply@uniben.edu";
    $mail->FromName = $fromName;

        //Set email subject
        $mail -> Subject = $mailS;
        $emailFrom1="noreply@uniben.edu";
        
        $mail -> addEmbeddedImage('email_assets/logo.png', 'Uniben_logo');
       
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
                       
                        <h1>$mailS</h1>
                            <h2>Hello $first_name,</h2>
                            <h4>
                                $mailB
                                
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

     $mail->IsHTML(true);
    
    $mail->addStringAttachment($pdfContent, $first_name.' '.$year.' Annual Leave.pdf', 'base64', 'application/pdf');
        
                
        //if sent successful
        if($mail -> Send()){

        //Close smtp connection
        $mail -> smtpClose();
        return true;
    }

}
   
    ?>

    
    