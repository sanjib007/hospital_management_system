
        <div class="row">

            <div class="col-lg-12">
                <!--work progress start-->
                <section class="panel">
                    <div class="panel-body progress-panel">
                        <div class="task-progress">
                            <h1>Appoinment Schedule</h1>
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
                            echo form_open('dashboard/drawSearchResult', $attributes);
                            ?>
                            <select class="styled" name="doctorId" onchange="this.form.submit()">
                                <option value="">Select a Doctor</option>
                                <?php
                                foreach ($doctorList as $aDoctor) {
                                    $mm = "";
                                    if($aDoctor->uacc_id == $dccId ){
                                        $mm = " selected ";
                                    }
                                    echo '<option' .$mm.'  value="' . $aDoctor->uacc_id . '">' . $aDoctor->upro_first_name." ".$aDoctor->upro_last_name . '</option>';
                                }
                                ?>
                            </select>
                            <?php echo form_close(); ?>    
                        </div>
                    </div>
                    <table class="table">
                        
                        <?php 
                        if(isset($output)){
                        echo $output;} ?>
                    </table>
                </section>
                <!--work progress end-->
            </div>
        </div>

