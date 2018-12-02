<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Recursion Hospital Management system is a online based system for a hospital/clinic . booking ,prescribe ,billing all system is here ">
    <meta name="keyword" content="Hospital Management System, Hospital Management, Hospital Management Software, Hospital Management System Software, Online Hospital Management System, Medical Management Software, Hospital Management Systems,  Hospital Management Software Demo , Web Based Hospital Management System">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?>img/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>img/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>img/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>img/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>img/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>img/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>img/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>img/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url(); ?>img/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>img/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>img/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>img/icon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo base_url(); ?>img/icon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url(); ?>img/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>js/html5shiv.js"></script>
    <script src="<?php echo base_url(); ?>js/respond.min.js"></script>


    <![endif]-->
    <link href="<?php echo base_url(); ?>css/animate.css" rel="stylesheet">
    <style>
        .flat-carousal{
            background-color: #F1F2F7;
            padding: 0;
            padding-bottom: 10px;
        }

    </style>
</head>

  <body class="login-body">

    <div class="container">
        <h1 class="tlt text-center" style="font-family: 'Lobster', cursive;">Welcome to LifeLine - Patient Management System</h1>
        <?php echo form_open(current_url(), 'class="form-signin"');?>
        <div class="row">
            <div class="col-lg-12">
                <div class="flat-carousal">
                    <div id="owl-demo" class="owl-carousel owl-theme">
                        <div class="item">
                            <img class="img img-rounded"  src="<?php echo base_url(); ?>img/LifeLine_PatientManagement.jpg"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="form-signin-heading">sign in now</h2>
        <?php if (! empty($message)) { ?>
        <div class="alert alert-block alert-danger fade in">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong> <?php echo $message; ?></strong>
        </div>
        <?php } ?>
        <div class="login-wrap">
            <input type="text" name="login_identity" id="username" class="form-control" placeholder="Email or Username:" autofocus>
            <input type="password" name="login_password" id="password" class="form-control" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" id="remember_me" name="remember_me" value="1"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label>
            <button class="btn btn-lg btn-login btn-block" value="login_user" name="login_user" type="submit">Sign in</button>

            <div class="login-social-link">
                <span class="text-center" style="color: red;font-weight: bold;">**click to add user name password**</span>
                <table id="myTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Account</th>
                        <th>Password</th>
                        <th>role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>admin@admin.com</td>
                        <td>123456</td>
                        <td>Admin</td>
                    </tr>
                    <tr>
                        <td>doctor1@r-cis.com</td>
                        <td>123456</td>
                        <td>Doctor</td>
                    </tr>
                    <tr>
                        <td>doctor2@r-cis.com</td>
                        <td>123456</td>
                        <td>Doctor</td>
                    </tr>
                    <tr>
                        <td>nurse1@r-cis.com</td>
                        <td>123456</td>
                        <td>Nurse</td>
                    </tr>
                    <tr>
                        <td>nurse2@r-cis.com</td>
                        <td>123456</td>
                        <td>Nurse</td>
                    </tr>
                    </tbody>
                </table>
                <?php
                # Below are 2 examples, the first shows how to implement 'reCaptcha' (By Google - http://www.google.com/recaptcha),
                # the second shows 'math_captcha' - a simple math question based captcha that is native to the flexi auth library.
                # This example is setup to use reCaptcha by default, if using math_captcha, ensure the 'auth' controller and 'demo_auth_model' are updated.

                # reCAPTCHA Example
                # To activate reCAPTCHA, ensure the 'if' statement immediately below is uncommented and then comment out the math captcha 'if' statement further below.
                # You will also need to enable the recaptcha examples in 'controllers/auth.php', and 'models/demo_auth_model.php'.
                #/*
//                if (isset($captcha))
//                {
//                    echo "<li>\n";
//                    echo $captcha;
//                    echo "</li>\n";
//                }
                #*/

                /* math_captcha Example
                # To activate math_captcha, ensure the 'if' statement immediately below is uncommented and then comment out the reCAPTCHA 'if' statement just above.
                # You will also need to enable the math_captcha examples in 'controllers/auth.php', and 'models/demo_auth_model.php'.
               */
                if (isset($captcha))
                {
                    echo "<li>\n";
                    echo "<label for=\"captcha\">Captcha Question:</label>\n";
                    echo $captcha.' = <input type="text" id="captcha" name="login_captcha" class="width_50"/>'."\n";
                    echo "</li>\n";
                }
                #*/
                ?>
            </div>
        </div>
        </form>
          <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <?php echo form_open("auth/forgotten_password");?>
                      <div class="modal-body">
                          <p>Enter your e-mail address below to reset your password.</p>
                          <input type="text" name="forgot_password_identity" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                          <button class="btn btn-success" name="send_forgotten_password" value="Submit" type="submit">Submit</button>
                      </div>
                      </form>
                  </div>
              </div>
          </div>
          <!-- modal -->



    </div>



    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.lettering.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.textillate.js"></script>
    <script>
        $(document).ready(function(){
            $('#myTable tbody tr').click( function () {
                var usename =  ($(this).find('td:first').text());
                var pass = ($(this).find('td:nth-child(2)').text());
                $("#username").val(usename);
                $("#password").val(pass);
            } );

            $('.tlt').textillate({
                // the default selector to use when detecting multiple texts to animate
                selector: '.texts',

                // enable looping
                loop: true,

                // sets the minimum display time for each text before it is replaced
                minDisplayTime: 2000,

                // sets the initial delay before starting the animation
                // (note that depending on the in effect you may need to manually apply
                // visibility: hidden to the element before running this plugin)
                initialDelay: 0,

                // set whether or not to automatically start animating
                autoStart: true,

                // custom set of 'in' effects. This effects whether or not the
                // character is shown/hidden before or after an animation
                inEffects: [],

                // custom set of 'out' effects
                outEffects: [ 'hinge' ],

                // in animation settings
                in: {
                    // set the effect name
                    effect: 'fadeInLeftBig',

                    // set the delay factor applied to each consecutive character
                    delayScale: 1.5,

                    // set the delay between each character
                    delay: 50,

                    // set to true to animate all the characters at the same time
                    sync: false,

                    // randomize the character sequence
                    // (note that shuffle doesn't make sense with sync = true)
                    shuffle: false,

                    // reverse the character sequence
                    // (note that reverse doesn't make sense with sync = true)
                    reverse: false,

                    // callback that executes once the animation has finished
                    callback: function () {}
                },

                // out animation settings.
                out: {
                    effect: 'bounce',
                    delayScale: 1.5,
                    delay: 50,
                    sync: false,
                    shuffle: false,
                    reverse: false,
                    callback: function () {}
                },

                // callback that executes once textillate has finished
                callback: function () {}
            });
        });
    </script>

  </body>
</html>
