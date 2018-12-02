<div class="row">
    <?php
    if($this->flexi_auth->get_user_group_id()!=4){
    ?>
    <div class="col-lg-12">
        <!--work progress start-->
        <section class="panel">
            <div class="panel-body progress-panel">
                <div class="task-progress">
                    <h1>Select Doctor</h1>
                    <?php
                    if(isset($doctorInfo)){
                        echo $doctorInfo->upro_first_name." ".$doctorInfo->upro_last_name;
                    }else{
                        echo 'no doctor selected';
                    }
                    ?>
                </div>
                <div class="task-option">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'edit_profile', 'role' => 'form');
                    echo form_open('schedule/view_queue_others', $attributes);
                    ?>
                    <select class="styled" name="doctorId" onchange="this.form.submit()">
                        <option value="">Select a Doctor</option>
                        <?php
                        foreach ($doctorList as $aDoctor) {
                            $mm = "";
                            if($aDoctor->uacc_id ==$dccId ){
                                $mm = " selected ";
                            }
                            echo '<option' .$mm.'  value="' . $aDoctor->uacc_id . '">' . $aDoctor->upro_first_name." ".$aDoctor->upro_last_name . '</option>';
                        }
                        ?>
                    </select>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </section>
        <!--work progress end-->
    </div>
    <?php } ?>
    <div class="col-lg-12">

        <section class="panel panel-primary">

            <div class="col-lg-1"></div>
            <div class="col-lg-5">
                <section class="panel text-center">
                    <header class="panel-heading btn-success"><strong>Now Serving </strong></header>
                    <div class="value">
                        <p></p>
                        <h1><?php echo $now_serving->nowtoken;
                            ?></h1>
                        <p></p>
                        <p>***</p>
                    </div>
                </section>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-5">
                <section class="panel text-center">
                    <header class="panel-heading btn-info"><strong>Next Patient </strong></header>
                    <?php foreach ($queue_list as $ongoing) { ?>
                        <div class="value">
                            <p></p>

                            <h1><?php
                                echo $ongoing->Slot_No;
                                ?></h1>

                            <p></p>

                            <p>

                            <h3></h3></p>
                            <p><?php echo $ongoing->patient_name; ?></p>
                        </div>
                        <?php break;
                    } ?>
                </section>
            </div>
        </section>
        <section class="panel panel-primary">
            <div class="col-lg-1"></div>
            <div class="col-lg-11">
                <section class="panel text-center">
                    <header class="panel-heading btn-warning"><strong>Patients on the Queue</strong></header>
                    <?php
                    foreach ($queue_list as $key => $ongoing) {
                        if ($key != 0) {
                            ?>
                            <div class="col-lg-2">
                                <section class="panel">
                                    <div class="value">
                                        <h3><?php echo $ongoing->Slot_No;
                                            ?></h3>

                                        <p><?php echo $ongoing->patient_name; ?></p>
                                    </div>
                                </section>
                            </div>
                        <?php }
                    }
                    ?>
                </section>
            </div>
        </section>
    </div>
</div>
