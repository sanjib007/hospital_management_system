<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <i class="icon icon-search"></i> Daily Accounts Detail
            </header>
            <div class="panel-body">
                <?php
                $attributes = array(
                    'class' => 'form-inline',
                    'role' => 'form'
                );
                echo form_open('report/trend_alalysis/', $attributes);
                ?>
                <div class="form-group">
                    <label for="Date">From Date</label>
                    <input type="text" name="startDate" id="startDate" value="<?php echo  $startDate; ?>" class="form-control" placeholder="Select Date"/>
                </div>
                <div class="form-group">
                    <label for="Date">To Date</label>
                    <input type="text" name="endDate" id="endDate" value="<?php echo  $endDate; ?>" class="form-control" placeholder="Select Date"/>
                </div>
                <button class="btn btn-success" type="submit"><i class="glyphicon glyphicon-search"></i> Generate Report</button>
                <?php echo form_close(); ?>
            </div>
        </section>
    </div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <i class="icon icon-list"></i> Daily Accounts Detail Report
            </header>
            <div class="panel-body">
                <table class="table table-striped table-advance table-condensed table-bordered" id="leave_log" >
                    <thead>
                    <tr>
                        <th><i class="icon icon-edit"></i> Appointment Date</th>
                        <th><i class="icon icon-edit"></i> Appoinment Taken</th>
                        <th><i class="icon icon-edit"></i> Patient Visited</th>
                        <th><i class="icon icon-edit"></i> Amount Earned</th>
                        <th><i class="icon icon-edit"></i> Amount Received </th>
                        <th><i class="icon icon-edit"></i> Discount Given </th>
                    </tr>
                    </thead>



                    <tbody>
                    <?php foreach ($report_data as $rec_acc_detail) { ?>
                        <tr>
                            <td><?php echo $rec_acc_detail['AppointmentDate']; ?></td>
                            <td><?php echo $rec_acc_detail['AppoinmentTaken']; ?></td>
                            <td><?php echo $rec_acc_detail['PatientVisited']; ?></td>
                            <td><?php echo $rec_acc_detail['AmountEarned']; ?></td>
                            <td><?php echo $rec_acc_detail['AmountReceived']; ?></td>
                            <td><?php echo $rec_acc_detail['DiscountGiven']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </section>

    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/moment/min/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript">
    $(document).ready(function () {
        $('#startDate,#endDate').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD',
            viewMode: 'days'
        });

    });
</script>
