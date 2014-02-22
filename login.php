<?php
    session_start();
    // $_SESSION['login']=FALSE;
    if(isset($_SESSION['login']) && $_SESSION['login']==TRUE)
    {
        header('Location: index.html');
    }

    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    
    <link rel="stylesheet" href="css/login.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    

<style type="text/css">
    body
    {

        margin:0;
        padding:0;
        background:#77D28E;
        font-family: "century gothic";
        font-size:14px;
        color:#999;
        vertical-align:baseline;
        width:4800px;
        position:absolute;
        top:0px;
        left:0px;
        bottom:0px;
        overflow-x:hidden; /*This will hide the Horizontal Scroll bar*/
    }
</style>
  </head>
  <body style="display:none;">
        <script>
            $(document).ready(function()
            {
                
                $('body').fadeIn(1000);
                
            });

        </script>
        <!--Login Section-->
        <div class="section" id="section1" >


            <div class="login-form" style="margin-left:478px">
                <img src="images/main.png" height="60">
                <p>You already have an account? Great! Login here.</p>
                <div>
                    <form method="post" action="php/login.php" class="form-wrapper-01">
                          <input name="email" class="inputbox email" type="text" style="color:#747474"placeholder="Email id" />
                          <input name="password" class="inputbox password" style="color:#747474"type="password" placeholder="Password" />
                        <p style="padding-top:10px;">
                            <button type="submit">Login</button>
                            <a href="redirectLinkedin.php"><img src="linkedinbtn.png"></a>
                        </p>
                    </form>
                        <p style="padding-top:10px">Forget password? It's ok. 
                            <a class="scroll" href="#section3" style="color:#77D28E;text-decoration: none ">Recover here &raquo;
                            </a>
                        </p>
                </div>
                <hr />

                  <p>Or you can Login with one of the following</p>

                <div class="social" style="padding-top:10px">

                    <a href="#" class="facebook"><i class="icon-facebook"></i></a>
                    <a href="#" class="twitter"><i class="icon-twitter"></i></a>
                    <a href="#" class="google"><i class="icon-gplus"></i></a>
                </div>
                <div>
                </div>

                <p style="padding-top:10px">Don't have an account? <a class="scroll" href="#section2" style="color:#77D28E;text-decoration: none ">Register Now &raquo;</a></p>
            </div>
        </div>
        <!--END: Login Section-->

        <!--Signup Section-->
        <div class="section" id="section2">
        <div class="signup-form" style="margin-left:353px">
            <h1>Sign Up in Seconds!</h1>
            <p>Signup using your Email address</p>
            <div>
                <form method="post" action="php/register.php" class="form-wrapper-01">
                      <input name="name" class="inputbox name" style="color:#747474"type="text" placeholder="Your Name" />
                      <input name="email" class="inputbox email" style="color:#747474"type="text" placeholder="Email id" />
                      <input id="" class="inputbox password" style="color:#747474"type="password" placeholder="Password" />
                      <input name="password" class="inputbox password"style="color:#747474" type="password" placeholder="Confirm Password" />
                      <button type="submit">Create my account</button>
                      <!--<input id="" type="button" class="button" value="Sign up" />-->
                </form>
            </div>
            <hr />
              <p>Or you can Signup with one of the following</p>
            <div class="social" style="padding-top:20px">
                <a href="#" class="facebook" ><span style="padding-left:24px">Facebook</span></a>
                <a href="#" class="twitter"></i><span style="padding-left:24px">Twitter</span></a>
                <a href="#" class="google"></i><span style="padding-left:24px">Google</span></a>
            </div>
            <p style="padding-top:20px"><a class="scroll" href="#section1" style="color:#77D28E;text-decoration: none ">&laquo; Login here</a></p>
        </div>
        </div>
        <!--END: Signup Section-->

        <!--Forget Password Section-->
        <div class="section" id="section3">
        <div class="login-form" style="margin-left:478px">
            <h1>Lost password?</h1>
            <p>Ohk, don't panic. You can recover it here.</p>
            <div>
                <form method="post" action="php/lostPassword.php" class="form-wrapper-01">
                      <input name="email" class="inputbox email" style="color:#747474"type="text" placeholder="Email id" />
                    <button type="submit">Recover!</button>
                </form>
            </div>
            <hr />
              <p>You remember your Password? Brilliant!</p>
            <p><a class="scroll" href="#section1" style="color:#77D28E;text-decoration: none ">&laquo; Login here</a></p>
        </div>
        </div>
        <!--END: Forget Password Section-->  
<!--Script for Horizontal Scrolling-->

<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>

<script type="text/javascript">
            $(function() {
                $('a.scroll').bind('click',function(event){
                    var $anchor = $(this);
                    $('html, body').stop().animate({
                        scrollLeft: $($anchor.attr('href')).offset().left
                    }, 500,'easeInOutExpo');
                     /* Uncomment this for another scrolling effect */
                     /*
                    $('html, body').stop().animate({
                        scrollLeft: $($anchor.attr('href')).offset().left
                    }, 1000);*/
                    event.preventDefault();
                });
            });
        </script>


  </body>
</html>
