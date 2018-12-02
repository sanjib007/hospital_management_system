<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Basic Information Getting Form
                <?php
                if($this->session->flashdata('msg')){
                    echo '<h1>'.$this->session->flashdata('msg').'</h1>';
                }
                ?>
            </header>
            <div class="panel-body">
                <div class="stepy-tab">
                    <ul id="default-titles" class="stepy-titles clearfix">
                        <li id="default-title-0" class="current-step">
                            <div>Step 1</div>
                        </li>
                        <li id="default-title-1" class="">
                            <div>Step 2</div>
                        </li>
                    </ul>
                </div>
                <form  method="post" class="form-horizontal" id="default">
                    <fieldset title="Step 1" class="step" id="default-step-0">
                        <legend></legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Full Name</label>

                                    <div class="col-lg-8">
                                        <input id="client_name" type="text" name="client_name" class="form-control"
                                               value="<?php echo $patientInfo->client_name; ?>">
                                        <input type="hidden" name="client_id" class="form-control"
                                               value="<?php echo $patientInfo->client_gen_id; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Father/Husband</label>

                                    <div class="col-lg-8">
                                        <input id="client_parent" type="text" name="client_parent" class="form-control"
                                               value="<?php echo $patientInfo->client_parents_name; ?>"
                                               placeholder="Father/Husband Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Date of Birth</label>

                                    <div class="col-lg-8">
                                        <input id="client_date_of_birth" type="text" name="client_date_of_birth" class="form-control"
                                               value="<?php echo $patientInfo->dateOfBirth; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Sex</label>

                                    <div class="col-lg-8">
                                        <select name="client_gender" id="client_gender" class="form-control">
                                            <option value="">---Select Option---</option>
                                            <option value="male" selected>Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Present Address</label>

                                    <div class="col-lg-8">
                                        <input id="client_present" type="text" name="client_present" class="form-control"
                                               value="<?php echo $patientInfo->present_address; ?>"
                                               placeholder="Present Address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Permanent Address</label>

                                    <div class="col-lg-8">
                                        <input id="client_permanent" type="text" name="client_permanent" class="form-control"
                                               value="<?php echo $patientInfo->permanent_address; ?>"
                                               placeholder="Permanent Address">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Mobile</label>

                                    <div class="col-lg-8">
                                        <input id="client_mobile" type="text" name="client_mobile" class="form-control"
                                               value="<?php echo $patientInfo->mobile; ?>"
                                               placeholder="Mobile">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Email</label>

                                    <div class="col-lg-8">
                                        <input id="client_email" type="text" name="client_email" class="form-control"
                                               value="<?php echo $patientInfo->email; ?>"
                                               placeholder="Email">
                                    </div>
                                </div>

                            </div>
                        </div>


                    </fieldset>
                    <fieldset title="basic" class="step" id="default-step-1">
                        <legend></legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Temperature</label>

                                    <div class="col-lg-8">
                                        <input id="client_temperature" type="text"
                                               value="<?php if (isset($pescriptionInfo->temperature)) echo $pescriptionInfo->temperature; ?>"
                                               name="client_temperature" class="form-control">
                                        <input type="hidden"
                                               value="<?php if (isset($pescriptionInfo->id)) echo $pescriptionInfo->id; ?>"
                                               name="presecription_id" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Blood Presure</label>

                                    <div class="col-lg-8">
                                        <input id="client_blood" type="text"
                                               value="<?php if (isset($pescriptionInfo->blood_pressure)) echo $pescriptionInfo->blood_pressure; ?>"
                                               name="client_blood" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Height</label>

                                    <div class="col-lg-8">
                                        <input id="client_height" type="text"
                                               value="<?php if (isset($pescriptionInfo->height)) echo $pescriptionInfo->height; ?>"
                                               name="client_height" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Weight</label>

                                    <div class="col-lg-8">
                                        <input id="client_weight" type="text" name="client_weight"
                                               value="<?php if (isset($pescriptionInfo->weight)) echo $pescriptionInfo->weight; ?>"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4" for="blood">Blood Group</label>

                                    <div class="col-lg-8">
                                        <select id="blood" class="form-control" name="blood" id="blood">
                                            <option <?php if ($patientInfo->bloodGroup == "") echo ' selected' ?>
                                                value="">--Select One--
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "A+") echo ' selected' ?>
                                                value="A+">A+
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "O+") echo ' selected' ?>
                                                value="O+">O+
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "B+") echo ' selected' ?>
                                                value="B+">B+
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "AB+") echo ' selected' ?>
                                                value="AB+">AB+
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "A-") echo ' selected' ?>
                                                value="A-">A-
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "O-") echo ' selected' ?>
                                                value="O-">O-
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "B-") echo ' selected' ?>
                                                value="B-">B-
                                            </option>
                                            <option <?php if ($patientInfo->bloodGroup == "AB-") echo ' selected' ?>
                                                value="AB-">AB-
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Complains</label>

                                    <div class="col-lg-10">
                                        <textarea id="client_complain" name="client_complain"
                                                  value="<?php if (isset($pescriptionInfo->complains)) echo $pescriptionInfo->complains; ?>"
                                                  class="form-control"
                                                  rows="3"><?php if (isset($pescriptionInfo->complains)) echo $pescriptionInfo->complains; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Symtoms</label>

                                    <div class="col-lg-10">
                                        <textarea id="client_symtoms" name="client_symtoms"
                                                  value="<?php if (isset($pescriptionInfo->symtoms)) echo $pescriptionInfo->symtoms; ?>"
                                                  class="form-control"
                                                  rows="3"><?php if (isset($pescriptionInfo->symtoms)) echo $pescriptionInfo->symtoms; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Diagnosis</label>

                                    <div class="col-lg-10">
                                        <textarea id="client_Diagnosis" name="client_Diagnosis"
                                                  value="<?php if (isset($pescriptionInfo->diagonosis)) echo $pescriptionInfo->diagonosis; ?>"
                                                  class="form-control"
                                                  rows="3"><?php if (isset($pescriptionInfo->diagonosis)) echo $pescriptionInfo->diagonosis; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                    <input type="submit" class="finish btn btn-danger" value="Finish"/>
                </form>
            </div>
        </section>
    </div>
</div>
<!-- page end-->

<script src="<?php echo base_url(); ?>js/jquery.stepy.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/moment/min/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<script>
    //step wizard
    $(document).ready(function () {
        $('#default').stepy({
            backLabel: 'Previous',
            block: true,
            nextLabel: 'Next',
            titleClick: true,
            titleTarget: '.stepy-tab'
        });

        $('#client_date_of_birth').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD'
        });
    });
</script>
