<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
        <link rel="stylesheet" href="./email-style.css">
        <link rel="shortcut icon" type="images/x-icon" href="./assets/favicon.ico" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"/>
        <style>

*,
*::after,
*::before {
  padding: 0%;
  margin: 0%;
  box-sizing: border-box;
}

body {
  display: flex;
  background-color: #e5e5e5;
  margin-top: 50px;
  margin-bottom: 50px;
  justify-content: center;
  font-family: "Mulish", sans-serif;
}

.container {
  display: flex;
  flex-direction: column;
  width: 420px;
  height: 650px;
  background-color: white;
}

.header {
  display: flex;
  justify-content: center;
  width: 420px;
  height: 50px;
  background: #ffffff;
  padding: 5px;
  box-shadow: 0px 4px 5px -2px rgba(0, 0, 0, 0.25);
}

.logo img {
  width: 40px;
  height: 30px;
}

.entry-title {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    flex-direction: column;
    padding: 1em;
}

.head img {
    height: 7em;
}

.text-head h4{
    line-height: 20px;
    font-weight: 650;
    text-align: center;
    padding: 12px;
    margin-top: 0.9em;
}

.text-head p{
    font-size: 0.7rem;
    text-align: center;
}

.action-bar {
    display: flex;
    justify-content: center;
    margin-top: 35px;
}

 .btn1 {
    width: 130px;
    height: 32px;
    border: none;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: Mulish, sans-serif;
    font-weight: 600;
    background-color: red;
    text-decoration-color: #e5e5e5;
}



hr {
    margin-top: 15px;
    margin-bottom: 15px;
}

footer {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px;
    flex-direction: column;
}

.foot-logo img{

    width: 40px;
    height: 30px;
}

.foot-text p {
    font-size: 0.6rem;
    text-align: center;
    padding: 25px;
}

.visit-links {
    padding: 8px;
}

.visit-links img{
    height: 1.4em;
}

.links {
    display: flex;
    padding: 5px;
}

.links a {
    display: flex;
    text-decoration: none;
}

.links p {

    font-size: 0.8rem;
    font-weight: 600;
}
        </style>
</head>
<body>
    <section>
        <div class="container">
            <div class="header">
                 <span class="logo"><img src="../email_assets/logo.png" alt=""></span>
            </div>
            <div class="entry-title" id="subject-title">
                <div class="head" id="subject-icon">
                    <img src="../email_assets/Profile confirmed.png" alt="">
                </div>
                <div class="text-head" id="subject-description">
                    <h4>Hello Osadebamen, your profile as partner Organization has been approved</h4>
                    <p>You can now start managing and sending your reports of 
                        your assigned Beneficiaries</p>
                </div>
                
            </div>
            <hr>
            <footer>
                <div class="foot-logo">
                    <img src="../email_assets/logo.png" alt="">
                </div>
                <div class="foot-text">
                    <p>The objective of the Pro-Poor Growth and Promotion of Employment in Nigeria - 
                        SEDIN Programme of the Deutsche Gesellschaft für Internationale Zusammenarbeit (GIZ) is to increase sustained employment and income generation in MSMEs.</p>
                </div>
                <div class="visit-links">
                    <a href="#"><img src="../email_assets/linkedin.png" alt=""></a>
                    <a href="#"><img src="../email_assets/facebook-filled.png" alt=""></a>
                    <a href="#"><img src="../email_assets/twitter icon.png" alt=""></a>
                </div>
                <div class="links">
                    
                    <a href="#"><p>Contact Us</p></a>
                </div>
            </footer>
        </div>
    </section>
   
</body>
</html>
