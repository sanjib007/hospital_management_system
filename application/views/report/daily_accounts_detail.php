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
                echo form_open('report/daily_accounts_detail/', $attributes);
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
                        <th><i class="icon icon-edit"></i> Patient Name</th>
                        <th><i class="icon icon-edit"></i> Payment Date</th>
                        <th><i class="icon icon-edit"></i> Total Visit</th>
                        <th><i class="icon icon-edit"></i> Discount Amount</th>
                        <th><i class="icon icon-edit"></i> Dues </th>
                        <th><i class="icon icon-edit"></i> Received </th>
                        <th><i class="icon icon-edit"></i> Status </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($report_data as $rec_acc_detail) { ?>
                        <tr>
                            <td><?php echo $rec_acc_detail->Name; ?></td>
                            <td><?php echo $rec_acc_detail->appointment_date; ?></td>
                            <td><?php echo $rec_acc_detail->visit_price; ?></td>
                            <td><?php echo $rec_acc_detail->discount; ?></td>
                            <td><?php echo ($rec_acc_detail->visit_price -$rec_acc_detail->total) ; ?></td>
                            <td><?php echo $rec_acc_detail->total; ?></td>
                            <td><?php echo ($rec_acc_detail->STATUS==0)?"not present":"present" ?></td>
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
