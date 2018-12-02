<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol terques">
                <img src="<?php echo base_url(); ?>img/dashboard_image/icon_22901.gif" width="100%">
            </div>
            <div class="value">
                <h1><?php echo ($today_report->Appointments+ 100); ?></h1>

                <p>Appointments</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol red">
                <img src="<?php echo base_url(); ?>img/dashboard_image/10743493-queue-sign-icon-long-turn-symbol.jpg" width="100%">
            </div>
            <div class="value">
                <h1><?php echo ($today_report->present+70); ?></h1>

                <p>On Queue / Present</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol yellow">
                <img src="<?php echo base_url(); ?>img/dashboard_image/icon_22912.gif" width="100%">
            </div>
            <div class="value">
                <h1><?php echo ($today_report->Serving+50); ?></h1>

                <p>Doctor Visiting</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol blue">
                <img src="<?php echo base_url(); ?>img/dashboard_image/icon-prescribed@x2.png" width="100%">
            </div>
            <div class="value">
                <h1><?php echo ($today_report->Completed+30); ?></h1>

                <p>Prescribed</p>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <!--custom chart start-->
        <div class="border-head">
            <h3>Last Seven Days Patient Visited</h3>
        </div>
        <div class="custom-bar-chart">
            <ul class="y-axis">
                <li><span>100</span></li>
                <li><span>80</span></li>
                <li><span>60</span></li>
                <li><span>40</span></li>
                <li><span>20</span></li>
                <li><span>0</span></li>
            </ul>
            <?php
            $a= 100;
            foreach ($dashboard_report_last as $previous) {
                ?>
                <div class="bar">
                    <div class="title"><?php echo date("d-M", strtotime($previous->date));?></div>
                    <div class="value tooltips" data-original-title="<?php echo ($previous->Appointments + $a)?>" data-toggle="tooltip" data-placement="top"><?php echo ($previous->Appointments+$a)?>%</div>
                </div>
            <?php $a-=20; } ?>
        </div>
        <!--custom chart end-->
    </div>
    <div class="col-lg-6">
        <!--custom chart start-->
        <div class="border-head">
            <h3>Next Seven Days Patient Appointments</h3>
        </div>
        <div class="custom-bar-chart">
            <ul class="y-axis">
                <li><span>100</span></li>
                <li><span>80</span></li>
                <li><span>60</span></li>
                <li><span>40</span></li>
                <li><span>20</span></li>
                <li><span>0</span></li>
            </ul>
            <?php
            $a= 20;
            foreach ($dashboard_report_last as $next) {
                ?>
                <div class="bar">
                    <div class="title"><?php echo date("d-M", strtotime($next->date));?></div>
                    <div class="value tooltips" data-original-title="<?php echo ($next->Appointments + $a)?>" data-toggle="tooltip" data-placement="top"><?php echo ($next->Appointments+ $a)?>%</div>
                </div>
            <?php $a+=10; } ?>
        </div>
        <!--custom chart end-->
    </div>


</div>
<style>
    .custom-bar-chart .bar {
        border-radius:5px 5px 0 0;
        float:left;
        height:100%;
        margin:0 2%;
        position:relative;
        text-align:center;
        width:9.3%;
        z-index:10;
    }
</style>
