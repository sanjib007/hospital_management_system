<style>
    .datepicker{z-index:1151 !important;}
    label.error{
        color: red;
        font-weight: bold;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {        
        $('#Date').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#mDate').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#iDate').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#stratDate').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd'
        });
        
        $(".clientDetail").click(function () {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>clients_account/get_company_info",
                data: "ID=" + id,
                success: function (data)
                {
                    var empStatus1 = $('#descriptiontable1 tbody');
                    empStatus1.html("");
                    empStatus1.append("<tr><td bgcolor='#9A9E9E' align='center' style='color:#ffffff'>TITLE</td><td bgcolor='#9A9E9E' align='center' style='color:#ffffff'>DESCRIPTION</td></tr><tr><td>Address</td><td>" + data.Address + "</td></tr><tr><td>Phone</td><td>" + data.Phone + "</td></tr><tr><td>Company Type</td><td>" + data.CompanyType + "</td></tr><tr><td>Contact Person</td><td>" + data.ContactPerson + "</td></tr><tr><td>Contact Person Phone</td><td>" + data.ContactPersonPhone + "</td></tr><tr><td>IP</td><td>" + data.IPList + "</td></tr>")

                }, dataType: 'json'
            });
        });
        
        $(".insertButton").click(function () {
            //opening edit modal
            $("#insertModal").modal({
                show: true
            });  
        });

        $('#insertForm').validate({  // initialize plugin
            rules : {
                iClientName : "required",
                iBankName : "required",
                iStatus : "required",
                iAmount : "required",
                iDate : "required",
                iTransactionCharge : "required",
                iConversionCharge : "required",
                iReference : "required"
            },
            messages : {
                iClientName : "please type company or client name",
                iBankName : "please select a bank name",
                iStatus : "please select a status",
                iDate : "please select date",
                iAmount : "type amount",
                iTransactionCharge : "type transaction charge",
                iConversionCharge : "type conversion charge",
                iReference : "type Reference code"
            },
            submitHandler: function () {
                // form validates so do the ajax
                var clientName = $("#iClientName").val();
                var bankName = $("#iBankName").val();
                var status = $("#iStatus").val();
                var date = $("#iDate").val();
                var amount = $("#iAmount").val();
                var transactionCharge = $("#iTransactionCharge").val();
                var conversionCharge = $("#iConversionCharge").val();
                var reference = $("#iReference").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>clients_account/set_post_data",
                    data: "ClientName=" + clientName + "&Date=" + date + "&BankName=" + bankName + "&Status=" + status + "&Amount=" + amount + "&TransactionCharge=" + transactionCharge + "&ConversionCharge=" + conversionCharge + "&Reference=" + reference,
                    success: function (data)
                    {
                        if (data.message == "success") {
                            alert("successfully inserted");
                            $("#insertModal").hide();
                            location.reload();
                        }
                    }, dataType: 'json'
                });
                return false; // ajax used, block the normal submit
            }
        });

        $(".editButton").click(function () {
            //opening edit modal
            $("#editModal").modal({
                show: true
            });            
            //getting value from selected row
            var id = $(this).data('editid');
            $('#editClientId').val(id);
            var rowData = $(this).parents('tr');
            var clientName = $(rowData).find("td").eq(0).text();
            var bankName = $(rowData).find("td").eq(1).text();
            var reference = $(rowData).find("td").eq(2).text();
            var tranStatus = $(rowData).find("td").eq(3).text();
            var date = $(rowData).find("td").eq(4).text();
            var amount = $(rowData).find("td").eq(5).text();
            var tranCharge = $(rowData).find("td").eq(6).text();
            var conCharge = $(rowData).find("td").eq(7).text();
            
            //setting input value selected
            $('#Reference').val(reference);
            $('#Amount').val(amount);
            $('#TransactionCharge').val(tranCharge);
            $('#ConversionCharge').val(conCharge); 
            $('#mDate').val(date); 
            
            //setting client options value selected
            var $selectClient = $('#ClientName');
            var $options = $('option', $selectClient);
            var clOptionvalue = null;
            $options.each(function() {
                if ($(this).text() == clientName){
                    clOptionvalue = $(this).val(); 
                }
            });
            $('#ClientName option[value='+clOptionvalue+']').attr('selected','selected');
            
            //setting bank options value selected
            var $selectBank = $('#BankName');
            var $options = $('option', $selectBank);
            var bankOptionvalue = null;
            $options.each(function() {
                if ($(this).text() == bankName){
                    bankOptionvalue = $(this).val(); 
                }
            });
            $('#BankName option[value='+bankOptionvalue+']').attr('selected','selected');
            
            //setting status options value selected
            var $selectStatus = $('#Status');
            var $options = $('option', $selectStatus);
            var statusOptionvalue = null;
            $options.each(function() {
                if ($(this).text() == tranStatus){
                    statusOptionvalue = $(this).val(); 
                }
            });
            $('#Status option[value='+statusOptionvalue+']').attr('selected','selected');
        });

        $('#editForm').validate({  // initialize plugin
            rules : {
                eClientName : "required",
                eBankName : "required",
                eStatus : "required",
                eAmount : "required",
                eTransactionCharge : "required",
                eConversionCharge : "required",
                eReference : "required"
            },
            messages : {
                eClientName : "please type company or client name",
                eBankName : "please select a bank name",
                eStatus : "please select a status",
                eAmount : "type amount",
                eTransactionCharge : "type transaction charge",
                eConversionCharge : "type conversion charge",
                eReference : "type Reference code"
            },
            submitHandler: function () {
                // form validates so do the ajax
                var id = $('#editClientId').val();
                var clientName = $("#ClientName").val();
                var bankName = $("#BankName").val();
                var status = $("#Status").val();
                var date = $("#mDate").val();
                var amount = $("#Amount").val();
                var transactionCharge = $("#TransactionCharge").val();
                var conversionCharge = $("#ConversionCharge").val();
                var reference = $("#Reference").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>clients_account/update_transactions",
                    data: "ClientName=" + clientName + "&Date=" + date + "&ID=" + id + "&BankName=" + bankName + "&Status=" + status + "&Amount=" + amount + "&TransactionCharge=" + transactionCharge + "&ConversionCharge=" + conversionCharge + "&Reference=" + reference,
                    success: function (data)
                    {
                        if (data.message == "success") {
                            alert("successfully updated");
                            $("#editModal").hide();
                            location.reload();
                        }
                    }, dataType: 'json'
                });
                return false; // ajax used, block the normal submit
            }
        });
        
        $(".deleteButton").click(function () {
            var id = $(this).data('id');
            $('#deleteClientId').val(id);
            //opening delete modal
            $("#deleteModal").modal({
                show: true
            });
            //getting value from selected row
            var rowData = $(this).parents('tr');
            var clientName = $(rowData).find("td").eq(0).text();
            var bankName = $(rowData).find("td").eq(1).text();
            var reference = $(rowData).find("td").eq(2).text();
            var tranStatus = $(rowData).find("td").eq(3).text();
            var date = $(rowData).find("td").eq(4).text();
            var amount = $(rowData).find("td").eq(5).text();
            var tranCharge = $(rowData).find("td").eq(6).text();
            var conCharge = $(rowData).find("td").eq(7).text();
            //set data to table 
            var tranInfo = $('#descriptiontable1 tbody');
            tranInfo.html("");
            tranInfo.append("<tr><td bgcolor='#9A9E9E' align='center' style='color:#ffffff'>TITLE</td><td bgcolor='#9A9E9E' align='center' style='color:#ffffff'>DESCRIPTION</td></tr><tr><td>client Name</td><td>" + clientName + "</td></tr><tr><td>Bank Name</td><td>" + bankName + "</td></tr><tr><td>Reference</td><td>" + reference + "</td></tr><tr><td>Status</td><td>" + tranStatus + "</td></tr><tr><td>Date</td><td>" + date + "</td></tr><tr><td>Amount</td><td>" + amount + "</td></tr><tr><td>Transaction Charge</td><td>" + tranCharge + "</td></tr><tr><td>Conversion Charge</td><td>" + conCharge + "</td></tr>")

        });

        $("#deleteForm").submit(function () {
            var id = $('#deleteClientId').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>clients_account/delete",
                data: "ID=" + id,
                success: function (data)
                {
                    if (data.message == "success") {
                        alert("successfully deleted");
                        $("#deleteModal").hide();
                        location.reload();
                    }
                }, dataType: 'json'
            });
            return false;
        });
    });
</script>
<div class='row'>
    <div class='col-md-3'></div>
    <div class='col-md-6'>
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-success alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="icon-remove"></i>
                </button>
                <h4>
                    <i class="icon-ok-sign"></i>
                    <?php echo $this->session->flashdata('msg'); ?>
                </h4>            
            </div>
        <?php } ?>
    </div>
    <div class='col-md-3'></div>
</div>
<!-- Insert Modal Start -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Insert Transaction Info.</h4>
            </div>
            <div class="modal-body">                        
                <form role="form" id="insertForm" class="insertForm">
                    <div class="form-group">
                        <label for="ClientName" > Client Name</label>
                        <select class="form-control" name="iClientName" id="iClientName">
                            <option value="">Select Client</option> 
                            <?php foreach ($client_info as $cInfo) { ?>                                    
                                <option value="<?php echo $cInfo->ID; ?>"><?php echo $cInfo->CompanyName; ?></option>
                            <?php } ?>
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="BankName" > Bank Name</label>                                                          
                        <select class="form-control" name="iBankName" id="iBankName">
                            <option value="">Select a Bank</option>
                            <?php foreach ($bank_info as $bInfo) { ?>                                    
                                <option value="<?php echo $bInfo->ID; ?>"><?php echo $bInfo->BankName; ?></option>
                            <?php } ?>
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="Status" > Status</label>                                                          
                        <select class="form-control" name="iStatus" id="iStatus">
                            <option value="">Select Status</option>                                  
                            <?php foreach ($transaction_status_info as $statusInfo) { ?>                                    
                                <option value="<?php echo $statusInfo->ID; ?>"><?php echo $statusInfo->StatusName; ?></option>
                            <?php } ?>                                    
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="Reference" >Reference</label>                                                          
                        <input type="text" name="iReference" class="form-control Reference" id="iReference">
                    </div>                                                        
                    <div class="form-group">
                        <label for="Date" >Date</label>                                                          
                        <input type="text" name="iDate" class="form-control" id="iDate">
                    </div>                                                        
                    <div class="form-group">
                        <label for="Amount" >Amount</label>                                                          
                        <input type="text" name="iAmount" class="form-control" id="iAmount">
                    </div>                                                        
                    <div class="form-group">
                        <label for="TransactionCharge" >Transaction Charge</label>                                                          
                        <input type="text" name="iTransactionCharge" class="form-control" id="iTransactionCharge">
                    </div>                                                        
                    <div class="form-group">
                        <label for="ConversionCharge" >Conversion Charge</label>                                                          
                        <input type="text" name="iConversionCharge" class="form-control" id="iConversionCharge">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                </form>
            </div>                    
        </div>
    </div>
</div>
<!-- Insert Modal End -->
<!-- Edit Modal Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Update Transaction Info.</h4>
            </div>
            <div class="modal-body">                        
                <form role="form" id="editForm">
                    <div class="form-group">
                        <label for="ClientName" > Client Name</label>                                                          
                        <select class="form-control" name="eClientName" id="ClientName">
                            <option value="">Select Client</option> 
                            <?php foreach ($client_info as $cInfo) { ?>                                    
                                <option value="<?php echo $cInfo->ID; ?>"><?php echo $cInfo->CompanyName; ?></option>
                            <?php } ?>
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="BankName" > Bank Name</label>                                                          
                        <select class="form-control" name="eBankName" id="BankName">
                            <option value="">Select a Bank</option>
                            <?php foreach ($bank_info as $bInfo) { ?>                                    
                                <option value="<?php echo $bInfo->ID; ?>"><?php echo $bInfo->BankName; ?></option>
                            <?php } ?>
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="Status" > Status</label>                                                          
                        <select class="form-control" name="eStatus" id="Status">
                            <option value="">Select Status</option>                                  
                            <?php foreach ($transaction_status_info as $statusInfo) { ?>                                    
                                <option value="<?php echo $statusInfo->ID; ?>"><?php echo $statusInfo->StatusName; ?></option>
                            <?php } ?>                                    
                        </select>  
                    </div>                        
                    <div class="form-group">
                        <label for="Reference" >Reference</label>                                                          
                        <input type="text" name="eReference" class="form-control" id="Reference">
                    </div>                                                        
                    <div class="form-group">
                        <label for="Date" >Date</label>                                                          
                        <input type="text" name="eDate" class="form-control" id="mDate">
                    </div>                                                        
                    <div class="form-group">
                        <label for="Amount" >Amount</label>                                                          
                        <input type="text" name="eAmount" class="form-control" id="Amount">
                    </div>                                                        
                    <div class="form-group">
                        <label for="TransactionCharge" >Transaction Charge</label>                                                          
                        <input type="text" name="eTransactionCharge" class="form-control" id="TransactionCharge">
                    </div>                                                        
                    <div class="form-group">
                        <label for="ConversionCharge" >Conversion Charge</label>                                                          
                        <input type="text" name="eConversionCharge" class="form-control" id="ConversionCharge">
                    </div> 
                    <input type="hidden" name="editClientId" id="editClientId"/>
                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                </form>
            </div>                    
        </div>
    </div>
</div>
<!-- Edit Modal End -->
<!-- Delete Modal Start -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> you are permanently deleting this Transactions!</h4>
            </div>
            <div class="modal-body">                        
                <form class="form-inline" role="form" id="deleteForm">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <table class="table table-bordered table-responsive" id="descriptiontable1">
                            <thead>
                                <tr></tr>
                                <tr></tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="deleteClientId" id="deleteClientId"/>
                    <button type="submit" class="btn btn-danger btn-large">Delete</button>
                </form>
            </div>                    
        </div>
    </div>
</div>
<!-- Delete Modal End -->
<!-- Company Detail Info Modal Start -->
<div class="modal fade bs-example-modal-lg" id="detailViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:9999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Company Detail Info</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="form-inline" role="form" id="deleteForm">
                        <div class="row">                            
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <table class="table table-bordered table-responsive" id="descriptiontable1">
                                    <thead>
                                        <tr></tr>
                                        <tr></tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Company Detail Info Modal end -->
<!-- Accounts Detail Info Modal start -->
<div class="row">
    <div class="row">   
        <div class="col-lg-12" style="padding-left: 30px;">
            <section class="panel panel-body">
                <div class="panel-primary" > 
                    <header class="panel-heading">
                        <h4> 
                            <div id="rcis"></div>
                            <div id="rTIME"></div>
                            <i class="icon icon-search"></i> Filter By Date
                        </h4>                
                    </header> 
                    <div class="panel-body">
                        <?php
                        $attributes = array(
                            'class' => 'form-inline',
                            'role' => 'form'
                        );
                        echo form_open('clients_account/view', $attributes);
                        ?>                        
                        <div class="form-group">
                            <div class="col-md-3">
                                <input type="text" name="stratDate" id="stratDate" value="<?php echo $fDate; ?>" class="form-control"  placeholder="Select a date"/>
                            </div>                            
                            <div class="col-md-3">
                                <input type="text" name="endDate" id="endDate" value="<?php echo $tDate; ?>" class="form-control" placeholder="Select a date"/>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i> Search</button>
                                <a class="btn btn-large btn-primary insertButton" data-toggle="modal"><i class="icon icon-plus-sign"></i> Add Info.</a>
                            </div>                          
                            <div class="col-md-4">
                                </div>                          
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </section>
        </div>    
    </div>
    <div class="row">
        <div class="col-md-12" style="padding-left: 30px;">
            <section class="panel panel-body">
                <div class="panel-primary" >
                    <header class="panel-heading">
                        <h4> 
                            <i class="glyphicon glyphicon-user"></i>Clients Transaction Detail               
                        </h4>
                    </header>
                    <div class="panel-body table-responsive">
                        <table class="table table-bordered" id="daily_log">
                            <thead>
                                <th><i class="icon icon-rocket"></i> Client Name</th>
                                <th><i class="icon icon-rocket"></i> Bank Name</th>
                                <th><i class="icon icon-time"></i> Reference</th>
                                <th><i class="icon icon-rocket"></i> Status</th>
                                <th><i class="icon icon-rocket"></i> Date</th>                                
                                <th><i class="fa fa-money"></i> Amount</th>	
                                <th><i class="icon icon-rocket"></i> Transaction Charge</th>
                                <th><i class="icon icon-time"></i> Conversion Charge</th>
                                <th><i class="icon icon-rocket"></i> Action</th>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th> Client Name</th>
                                    <th> Bank Name</th>
                                    <th> Reference</th>
                                    <th> Status</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php foreach ($accInfo as $accInfos) {?>                                                                
                                    <tr>
                                        <td><a class="clientDetail" title="Click to view detail info" data-toggle="modal" href="#detailViewModal" data-id="<?php echo $accInfos->comID; ?>"><?php echo $accInfos->CompanyName; ?></a></td>
                                        <td><?php echo $accInfos->BankName; ?></td>
                                        <td><?php echo $accInfos->Reference; ?> </td>
                                        <td><?php echo $accInfos->StatusName ?></td>
                                        <td><?php echo $accInfos->Date ?></td>
                                        <td><?php echo $accInfos->Amount ?></td>
                                        <td><?php echo $accInfos->TransactionCharge ?></td>
                                        <td><?php echo $accInfos->ConversionCharge ?></td>
                                        <td>
                                            <a class="btn btn-xs btn-primary editButton" data-toggle="modal" data-editid="<?php echo $accInfos->ID; ?>"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-xs btn-danger deleteButton" data-toggle="modal" data-id="<?php echo $accInfos->ID; ?>"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>                                   
                                <?php } ?>								
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>            
        </div>
    </div>
</div>
<!-- Accounts Detail Info Modal end -->
<script type="text/javascript">
    $(document).ready(function ()
    {
        //For Data Table
        var table = $('#daily_log').DataTable({
            dom: 'T<"clear">lfrtip',
            tableTools: {
                "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
            }
        });
        $("#daily_log tfoot th").each(function (i) {
            var select = $('<select><option value=""></option></select>')
                    .appendTo($(this).empty())
                    .on('change', function () {
                        table.column(i)
                                .search($(this).val())
                                .draw();
                    });
            table.column(i).data().unique().sort().each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    });
</script>

