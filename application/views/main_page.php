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

    <title>
        <?php if (isset($title)) { ?>
            Hospital Management System | <?php echo $title; ?>
        <?php } else { ?>
            Hospital Management System
        <?php } ?>
    </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/datepicker.css"/>
    <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.css"/>
    <link href="<?php echo base_url(); ?>css/dataTables.tableTools.css" rel="stylesheet" type="text/css"/>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>js/html5shiv.js"></script>
    <script src="<?php echo base_url(); ?>js/respond.min.js"></script>

    <![endif]-->    
    <script src="<?php echo base_url(); ?>js/angular.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>js/jquery-2.1.3.min.js" type="text/javascript"></script>
    
    <script src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>    
    <script src="<?php echo base_url(); ?>js/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.dataTables.columnFilter.js"></script>
    <script src="<?php echo base_url(); ?>js/dataTables.tableTools.js" type="text/javascript"></script>
</head>

<body>

<section id="container">
    <!--header start-->
    <header class="header white-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
        </div>
        <!--logo start-->
        <a href="<?php echo base_url(); ?>" class="logo"><img height="100" width="100" class="img img-responsive" src="<?php echo base_url(); ?>img/Health_Record_System1.png"  alt=""/></a>
        <!--logo end-->
        <div class="top-nav ">
            <!--search & user info start-->
            <ul class="nav pull-right top-menu">

                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <?php
                        if (!($this->flexi_auth->is_logged_in())) {
                            echo img(base_url() . 'img/noimage.png');
                        } else {
                            ?>
                            <img height="29" width="29"
                                 src="<?php //echo base_url(); ?>img/<?php //echo $infoResult->photo_avater ?>">
                        <?php } ?>
                        <span class="username">
                            <?php
                            if (($this->flexi_auth->is_logged_in())) {
                                //echo $infoResult->upro_first_name . " " . $infoResult->upro_last_name;
                            } else {
                                echo 'unregistered';
                            }
                            ?>
                        </span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <div class="log-arrow-up"></div>
                        <li><a href="<?php echo base_url(); ?>auth_public/update_account"><i
                                    class=" fa fa-suitcase"></i>Profile</a></li>
                        <li><a href="<?php echo base_url(); ?>auth_public/change_password"><i class="fa fa-cog"></i>
                                pasword change</a></li>
                        <li><a href="<?php echo base_url() . "auth/logout" ?>"><i class="fa fa-key"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a href="<?php echo base_url(); ?>dashboard">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php
                if($this->flexi_auth->in_group('Master Admin')) {
                    ?>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-h-square"></i>
                            <span>Admin Configuration</span>
                        </a>
                        <ul class="sub">
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/register_account">Create Users</a>
                            </li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/manage_user_groups">Manage
                                    Group</a></li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/manage_user_accounts">Manage All
                                    Account</a></li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/manage_privileges">Manage
                                    Privileges</a></li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/list_user_status/active">List
                                    Active Users</a></li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/list_user_status/inactive"> List
                                    Inactive Users</a></li>
                            <li><a class="" href="<?php echo base_url(); ?>auth_admin/failed_login_users">List Failed
                                    Logins</a></li>
                            <li>
                                <a href="<?php echo base_url(); ?>clinic_info/setup">Setup</a>
                            </li>
                        </ul>
                    </li>
                <?php
                }

                if($this->flexi_auth->is_privileged('Manage Schedule')) {
                    ?>
                    <li>
                        <a class="" href="<?php echo base_url(); ?>schedule">
                            <i class="fa fa-map-marker"></i>
                            <span>Manage Schedule</span>
                        </a>
                    </li>
                <?php
                }
                if($this->flexi_auth->is_privileged('set appoinment')) {
                    ?>

                    <li>
                        <a href="<?php echo base_url(); ?>dashboard/drawSearchResult">
                            <i class="fa fa-map-marker"></i>
                            <span>Set Appoinment </span>
                        </a>
                    </li>

                <?php
                }
                if($this->flexi_auth->is_privileged('queue display')) {
                    ?>
                    <li>
                        <a class="" href="<?php echo base_url(); ?>schedule/view_queue">
                            <i class="fa fa-map-marker"></i>
                            <span>Queue Display</span>
                        </a>
                    </li>
                <?php
                }
                if($this->flexi_auth->is_privileged('patient management')) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>appoinment">
                            <i class="fa fa-map-marker"></i>
                            <span>Patient Management</a></span>
                        </a>
                    </li>
                <?php
                }
                if($this->flexi_auth->is_privileged('prescription')) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>prescription/index">
                            <i class="fa fa-map-marker"></i>
                            <span>Prescription </span>
                        </a>
                    </li>
                <?php
                }               
                if($this->flexi_auth->is_privileged('account_view')) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>clients_account/view">
                            <i class="fa fa-map-marker"></i>
                            <span>Clients Account</span>
                        </a>
                    </li>
                <?php
                }
                ?>

            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <?php $this->load->view($container); ?>
        </section>
    </section>
    <!--main content end-->
    <!--footer start-->

    <!--footer end-->
</section>
</section>

<footer class="site-footer navbar navbar-default navbar-fixed-bottom">
    <div class="text-center">
        2015 &copy; Hospital Management System By Recursion Technologies Ltd.
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
</section>
<!-- js placed at the end of the document so the pages load faster -->

<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>js/owl.carousel.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.customSelect.min.js"></script>
<script src="<?php echo base_url(); ?>js/respond.min.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>js/common-scripts.js"></script>
<!--script for this page-->
<script src="<?php echo base_url(); ?>js/sparkline-chart.js"></script>
<script src="<?php echo base_url(); ?>js/easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>js/count.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>


<script>

    //owl carousel

    $(document).ready(function () {
        $("#owl-demo").owlCarousel({
            navigation: true,
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true

        });
    });

    //custom select box

    $(function () {
        $('select.styled').customSelect();
    });


    $(document).ready(function () {
        $('.sidebar-menu a').each(function (index) {
            if (this.href.trim() == window.location) {
                var classSelected = 'active';
                $("#dash").removeClass('active');
                $(this).parent('li').addClass(classSelected).parents('li').addClass('active');
                ;
            }
        });

    });


</script>

</body>
</html>
