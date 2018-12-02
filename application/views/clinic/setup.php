<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
            </header>
            <div class="panel-body">
                <?php
                $attributes = array('class' => 'form-horizontal', 'id' => 'create_profile', 'role' => 'form');
                echo form_open_multipart(current_url(), $attributes);
                ?>
                <section class="panel">
                    <div class="bio-graph-heading">
                        Setup Clinic / Hospital Information
                    </div>
                    <div class="panel-body bio-graph-info">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label  for="fname" class="col-lg-2 control-label">Name</label>
                                    <div class="col-lg-6">
                                        <input type="hidden" name="id" value="<?php echo $information->id; ?>"/>
                                        <input type="text" class="form-control" name="name" value="<?php echo $information->name; ?>" id="name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-lg-2 control-label" for="lname">Address</label>
                                    <div class="col-lg-6">
                                        <textarea name="address" id="address" value="<?php echo $information->address;?>" class="form-control" rows="3"><?php echo $information->address; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-lg-2 control-label" for="birthday">phone</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="phone" value="<?php  echo $information->phone; ?>" id="phone" placeholder=" ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-lg-2 control-label" for="birthday">mobile</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="mobile" value="<?php echo $information->mobile; ?>" id="mobile" placeholder=" ">
                                    </div>
                                </div>
                                <div class="form-group">


                                        <label class="col-lg-2 control-label">Upload Picture</label>

                                        <div class="col-lg-6">
                                            <input type="file" class="file-pos" id="exampleInputFile" name="userfile"
                                                   id="image_avater">
                                        </div>

                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <input type="submit" name="submit" value="Save" class="btn btn-info">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <img src="<?php echo base_url(); ?>img/<?php echo $information->image; ?>" alt="" />
                            </div>
                        </div>

                    </div>
                </section>

                </form>
            </div>
        </section>

    </div>


</div>
<style>
    label.error{
        color: red;
        font-weight: bold;
    }
</style>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $("#create_profile").validate({
            rules: {
                name: "required",
                address: "required",
                phone: "required",
                mobile: "required"

            },
            messages: {
                name: "Please enter name",
                address: "Please enter address",
                phone: "Please enter phone number",
                mobile: "Please enter mobile number"

            }
        });
    });
</script>


