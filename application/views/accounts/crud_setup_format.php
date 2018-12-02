
<script type="text/javascript">
    $(document).ready(function () {
        $('#Date').datepicker({
            format: 'yyyy-mm-dd'
        });
        $(".clientDetail").click(function () {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>con_proc_dashboard_report/media_detail_info",
                data: "ID=" + id,
                success: function (data)
                {
                    var empStatus = $('#imagetable tbody');
                    empStatus.html("");
                    empStatus.append("<tr><td><img src='<?php echo base_url(); ?>img/" + data.PosterImage + "' class='post_images img-responsive' height='250px' width='180px'></td></tr>");
                    var empStatus1 = $('#descriptiontable1 tbody');
                    empStatus1.html("");
                    empStatus1.append("<tr><td>Name</td><td>" + data.FileName + "</td></tr><tr><td>Story</td><td>" + data.StoryType + "</td></tr><tr><td>Type</td><td>" + data.FileType + "</td></tr><tr><td>Size</td><td>" + data.Size + "</td></tr><tr><td>Print</td><td>" + data.MoviePrint + "</td></tr><tr><td>Resulation</td><td>" + data.Pixel + "</td></tr><tr><td>IMDB Rating</td><td>" + data.ImdbRating + "</td></tr><tr><td bgcolor='#52B043' colspan='2' align='center'><a style='color:#ffffff' href='<?php echo base_url(); ?>Movies/" + data.TorrentFile + "'>DOWNLOAD FILE</a></td></tr>")

                }, dataType: 'json'
            });
        });

        $('#Department').on('change', function (e) {
            var selected = $(this).find('option:selected');
            var id = selected.data('id');
            if (id == 0) {
                var lineSelect = $('#Line');
                lineSelect.html('');
                lineSelect.append("<option value=''>অনুগ্রহ করে বিভাগ নির্বাচন  করুন</option> ");
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>con_set_line/get_section_wise_line",
                    data: "SectionID=" + id,
                    success: function (data)
                    {
                        if (data == '') {
                            var lineSelect = $('#Line');
                            lineSelect.html('');
                            lineSelect.append("<option value=''>দুঃখিত এই বিভাগের লাইন পাওয়া যায় নি</option> ");
                        } else {
                            var lineSelect = $('#Line');
                            lineSelect.html('');
                            lineSelect.append("<option value=''>লাইন নির্বাচন করুন</option> ");
                            lineSelect.append("<option value=''>------------------</option> ");
                            $.each(data, function (v, k) {
                                lineSelect.append("<option value='" + k.Name + "' data-foo='" + k.ID + "' >" + k.Name + "</option>");
                            });
                        }

                    }, dataType: 'json'
                });
            }
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
<hr>
<div class="row">
    <div ng-app="transactionApp">
        <div ng-controller="transactionCrud">
            <div class="row">
                <div class="col-md-12" style="padding-left: 30px;">
                    
                    <section class="panel panel-body">
                        <div class="panel-primary">
                            <header class="panel-heading">
                                <h4>
                                    <i class="glyphicon glyphicon-user"></i> Insert Client's Account Info.
                                </h4>
                            </header>
                            <div class="panel-body">
                                <form class="form-horizontal" ng-submit="AddClientAccInfo(cAccInfo)">
                                <div class="form-group">
                                    <label for="ClientName" class="col-md-3">Client Name</label>                            
                                    <label for="BankName" class="col-md-3">Bank Name</label>                            
                                    <label for="Reference" class="col-md-3">Reference</label>                            
                                    <label for="Status" class="col-md-3">Status</label>                            
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <select ng-model="cAccInfo.ClientID" class="form-control" name="ClientName" id="ClientName">
                                            <option value="">Select Client</option> 
                                            <?php foreach ($client_info as $cInfo) { ?>                                    
                                                <option value="<?php echo $cInfo->ID; ?>"> <?php echo $cInfo->CompanyName; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>                            
                                    <div class="col-md-3">
                                        <select ng-model="cAccInfo.BankID" class="form-control" name="BankName" id="ClientName">
                                            <option value="">Select a Bank</option>
                                            <?php foreach ($bank_info as $bInfo) { ?>                                    
                                                <option value="<?php echo $bInfo->ID; ?>"> <?php echo $bInfo->BankName; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>                            
                                    <div class="col-md-3">
                                        <input type="text" ng-model="cAccInfo.Reference" name="Reference"  class="form-control" id="Reference">
                                    </div>                            
                                    <div class="col-md-3">
                                        <select ng-model="cAccInfo.StatusID" class="form-control" name="Status" id="Status">
                                            <option value="">Select Status</option>                                  
                                            <?php foreach ($transaction_status_info as $statusInfo) { ?>                                    
                                                <option value="<?php echo $statusInfo->ID; ?>"> <?php echo $statusInfo->StatusName; ?> </option>
                                            <?php } ?>                                    
                                        </select>
                                    </div>                           
                                </div>
                                <div class="form-group">
                                    <label for="Date" class="col-md-3">Date</label>                            
                                    <label for="Amount" class="col-md-3">Amount</label>                            
                                    <label for="TransactionCharge" class="col-md-3">Transaction Charge</label>                            
                                    <label for="ConversationCharge" class="col-md-2">Conversation Charge</label>                            
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <input type="text" ng-model="cAccInfo.Date" name="Date"  class="form-control" id="Date">
                                    </div>                            
                                    <div class="col-md-3">
                                        <input type="text" ng-model="cAccInfo.Amount" name="Amount"  class="form-control" id="Amount">
                                    </div>                            
                                    <div class="col-md-3">
                                        <input type="text" ng-model="cAccInfo.TransactionCharge" name="TransactionCharge"  class="form-control" id="TransactionCharge">
                                    </div>                            
                                    <div class="col-md-2">
                                        <input type="text" ng-model="cAccInfo.ConversionCharge" name="ConversationCharge"  class="form-control" id="ConversationCharge">
                                    </div>
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                                </form>
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
                                    <i class="glyphicon glyphicon-user"></i> Client's Account Info.               
                                </h4>                
                            </header>
                            <div class="panel-body table-responsive">
                                <table class="table table-bordered" id="uploadMediaTable">
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
                                            <th>Client Name</th>
                                            <th>Bank Name</th>
                                            <th>Reference</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr ng-repeat="accInfos in accInfo">
                                            <td>{{ accInfos.ClientID }}</td>
                                            <td>{{ accInfos.BankID }}</td>
                                            <td>{{ accInfos.Reference }}</td>
                                            <td>{{ accInfos.StatusID }}</td>
                                            <td>{{ accInfos.Date }}</td>
                                            <td>{{ accInfos.Amount }}</td>
                                            <td>{{ accInfos.TransactionCharge }}</td>
                                            <td>{{ accInfos.ConversionCharge }}</td>
                                            <td><a href="#" ng-click="editUser(accInfos.ID)">Edit</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>            
                </div>
            </div>
        </div>
    </div>   
</div>

<script>
    var transactionApp = angular.module('transactionApp', []);
    transactionApp.controller('transactionCrud', function ($scope, $http) {
        $scope.accInfo = [];
        $http.get('<?php echo site_url('clients_account/get_list'); ?>').success(function ($data) {
            $scope.accInfo = $data;
        });        
        
        //$scope.cAccInfo = [];
        $scope.AddClientAccInfo = function (cAccInfo) {
            var cdate = $('#Date').val();            
            
            $scope.cAccInfos = {Date : cdate, 
                                ClientID : cAccInfo.ClientID, 
                                BankID: cAccInfo.BankID, 
                                Reference: cAccInfo.Reference, 
                                StatusID: cAccInfo.StatusID, 
                                Amount: cAccInfo.Amount,
                                TransactionCharge: cAccInfo.TransactionCharge,
                                ConversionCharge: cAccInfo.ConversionCharge
                                };
            console.log($scope.cAccInfos);
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>clients_account/set_post_data",
                data: JSON.stringify($scope.cAccInfos),
                dataType: "json"
            }).success(function (data) {
                $scope.accInfo.push($scope.cAccInfos);
                alert("insert success");
                $scope.cAccInfos = null;
            }).error(function (data) {
            });
        };

        $scope.deleteFeed = function (pId) {
            //Defining $http service for deleting a person 
            // alert(pId);
            $http({
                method: 'DELETE',
                url: '<?php echo base_url() . 'welcome/del_feedback?ID='; ?>' + pId
            }).success(function (data) {
                    $http.get('<?php echo site_url('welcome/get_list'); ?>').success(function ($data){
                    $scope.users = $data;
                });
            });
        };

        $scope.editUser = function (pId) {
            //Defining $http service for deleting a person 
            // alert(pId);
            $http({
                method: 'EDIT',
                url: '<?php echo base_url() . 'welcome/get_user_by_id?ID='; ?>' + pId
            }).success(function (data) {
                $scope.editc = $data;
            });
        };
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#uploadMediaTable').DataTable();
        $("#uploadMediaTable tfoot th").each(function (i) {
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
