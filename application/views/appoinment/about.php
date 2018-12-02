<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href="<?php echo base_url(); ?>css/invoice-print.css" rel="stylesheet" media="print">
<script>
    $(document).ready(function () {
        $("#datepicker").datepicker().datepicker("setDate", new Date());

    });
</script>
<div ng-app="myApp">
    <div ng-controller="myCtrl">
        <div class="modal fade avoid-this" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Change Patient Status</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">Patient Status</label>

                                <div class="col-sm-8">
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="singlePatient.status" ng-change="StatusChange()"
                                               name="inlineRadioOptions" id="inlineRadio1" value="0">Booked
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="singlePatient.status" ng-change="StatusChange()"
                                               name="inlineRadioOptions" id="inlineRadio2" value="1">Present
                                    </label>

                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-example-modal-lg" id="myModal1" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" class="example">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body" >
                        <div id="printArea">
                            <div class="row invoice-list">
                                <div class="text-center corporate-id">
                                    <img src="<?php echo base_url(); ?>img/Health_Record_System1.png" alt="">
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <h4>Doctor's Information</h4>
                                    <div ng-bind-html="prescripinfo.doctor_info.doctor_prescribe_info"></div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <h4>Chamber's Address</h4>

                                    <p>
                                        <strong>{{prescripinfo.hospital_info.name}}</strong><br>
                                        {{prescripinfo.hospital_info.address}}<br>
                                        M:{{prescripinfo.hospital_info.mobile}}<br>
                                        P: {{prescripinfo.hospital_info.phone}}<br>
                                    </p>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <h4>Prescription Information</h4>
                                    <ul class="unstyled">
                                        <li ng-repeat="aschedule in prescripinfo.schedule">Day:{{aschedule.week_day|dayNameWord}} Time:{{aschedule.time_from|change12hours}}-{{aschedule.time_to|change12hours}}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5 col-sm-5"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Name: {{prescripinfo.patient.client_name}}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Age: {{ prescripinfo.patient.dateOfBirth | ageFilter }}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Sex: {{prescripinfo.patient.Gender}}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">ID
                                            No: {{prescripinfo.patient.client_gen_id}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-8 col-sm-8"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">Address:
                                            {{prescripinfo.patient.present_address}}
                                        </div>
                                        <div class="col-lg-3 col-sm-3"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">Date:16
                                            th june , 2015
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-3"
                                     style="margin-left:10px; margin-top:10px; padding: 20px 0; border-right: 2px solid black; padding-right: 10px;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider" style="text-align: left; padding-left: 10px;" href="#"><b>Complains</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.complains}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider" style="text-align: left; padding-left: 10px;" href="#"><b>Symtoms</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.symtoms}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider" style="text-align: left; padding-left: 10px;" href="#"><b>Diagonosis</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.diagonosis}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider" style="text-align: left; padding-left: 10px;" href="#"><b>Baisc Info</b></a>
                                                <li>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <th>Info</th>
                                                        <th>calc</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Temperature</td><td>{{prescripinfo.prescription.temperature}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Blood Presure</td><td>{{prescripinfo.prescription.blood_pressure}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Height</td><td>{{prescripinfo.prescription.height}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Weight</td><td>{{prescripinfo.prescription.weight}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Blood Group</td><td>{{prescripinfo.patient.bloodGroup}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-sm-8" style="margin-left:10px; margin-top:2px">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <h1 class="wall"><img src="<?php echo base_url(); ?>img/rx2.png"/></h1>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td>Type</td>
                                                    <td>Name</td>
                                                    <td>Dose</td>
                                                    <td>Days</td>
                                                    <td>After/Before</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr ng-repeat="ainfo in prescripinfo.medicine">
                                                    <td>Tablet</td>
                                                    <td><p>{{ainfo.medicine_name}}</p><p>(Paracetamol)</p></td>
                                                    <td>{{ainfo.dose}}</td>
                                                    <td>{{ainfo.number_days}}</td>
                                                    <td>{{ainfo.procedure}}</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" class="example">
                            <div class="col-lg-12">
                                <div class="text-center invoice-btn">

                                    <a class="btn btn-info btn-lg" id="clickPrint"><i
                                            class="fa fa-print"></i> Print </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade bs-example-modal-lg avoid-this" id="paymentInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Payment Information</h4>
                    </div>
                    <div class="modal-body">
                        <table ng-show="paymentInfo.paymentList.length>0" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th class="hidden-phone">Date</th>
                                <th class="">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="apayment in paymentInfo.paymentList">
                                <td>{{$index+1}}</td>
                                <td>{{apayment.title}}</td>
                                <td class="hidden-phone">{{apayment.payment_date}}</td>
                                <td class="">{{apayment.amount}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div ng-show="paymentInfo.paymentList.length==0" class="alert alert-danger" role="alert">Already there is no paid</div>
                        <div class="row">
                            <div class="col-lg-4 invoice-block pull-right">
                                <ul class="unstyled amounts">
                                    <li><strong>Total amount :</strong> {{paymentInfo.Total}}</li>
                                    <li><strong>Total Paid</strong> {{TotalPaidAmount()}}</li>
                                    <li><strong>Remaining</strong> {{paymentInfo.Total - TotalPaidAmount() }}<button ng-click="GetRemainingPayment(paymentInfo,(paymentInfo.Total - TotalPaidAmount()))" ng-show="(paymentInfo.Total - TotalPaidAmount())>0" type="button" class="btn btn-danger">payment</button></li>
                                    <li><strong>Status</strong> {{(paymentInfo.Total - TotalPaidAmount())>0?"unpaid":"paid"}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row avoid-this">
            <div class="col-lg-12">
                <!--work progress start-->
                <section class="panel">
                    <div class="panel-body progress-panel">
                        <div class="task-progress">
                            <h1>Appoinment Schedule</h1>
                        </div>
                        <div class="task-option">
                            <?php
                            $attributes = array('class' => 'form-inline', 'id' => 'edit_profile', 'role' => 'form');
                            echo form_open('', $attributes);
                            ?>
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputEmail2">Enter Date</label>
                                <input type="text" ng-model="ltDate" class="form-control"
                                       value="<?php if ($this->input->post('todayDate')) echo $this->input->post('todayDate'); ?>"
                                       name="todayDate" id="datepicker" placeholder="Enter Date">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputPassword2">Select Doctor</label>
                                <select ng-model="ltDoctorId" ng-change="ShowInformation(ltDate, ltDoctorId)"
                                        class="styled" name="doctorId">
                                    <option value="">Select a Doctor</option>
                                    <?php
                                    foreach ($doctorList as $aDoctor) {
                                        echo '<option  value="' . $aDoctor->uacc_id . '">' . $aDoctor->upro_first_name . " " . $aDoctor->upro_last_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>


                            <?php echo form_close(); ?>
                        </div>
                    </div>

                </section>
                <!--work progress end-->
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Upcoming Patient
                    </header>
                    <table ng-show="PatientList.length > 0" class="table table-hover personal-task">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Status</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in PatientList| orderBy:'ticket_number' | filter: showing">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td>
                                <button ng-click="ChangeStatus(aPatient)" ng-class="aPatient.myclass">{{aPatient.msg}}
                                </button>
                            </td>
                            <td><?php echo '<a href="' . base_url() . 'appoinment/getInfo/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1" class="btn btn-round btn-success">PAID</button>
                                <button type="button" ng-show="aPatient.payment_status==0" class="btn btn-round btn-warning">UNPAID</button>
                                <button type="button" ng-show="aPatient.payment_status==2" class="btn btn-round btn-info">Partial Payment</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div ng-show="PatientList.length==0" class="alert alert-danger" role="alert">There is no doctor selected</div>
                </section>
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        On Queue
                    </header>
                    <table ng-show="PatientList.length > 0" class="table table-hover personal-task">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Status</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in filterSecId(PatientList)| orderBy:'ticket_number' | valueChange|filter:{ ticket_number:'!' + nowServing }">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td>
                                <button ng-click="ChangeStatus(aPatient)" ng-class="aPatient.myclass">{{aPatient.msg}}
                                </button>
                            </td>
                            <td><?php echo '<a href="' . base_url() . 'appoinment/getInfo/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1" class="btn btn-round btn-success">PAID</button>
                                <button type="button" ng-show="aPatient.payment_status==0" class="btn btn-round btn-warning">UNPAID</button>
                                <button type="button" ng-show="aPatient.payment_status==2" class="btn btn-round btn-info">Partial Payment</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div ng-show="PatientList.length==0" class="alert alert-danger" role="alert">There is no doctor selected</div>
                </section>
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Completed
                    </header>
                    <table ng-show="PatientList.length > 0" class="table table-hover personal-task">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Status</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                            <th>Prescription</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in PatientList| orderBy:'ticket_number' | valueChange |filter:{ status:3 }">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td>
                                <button ng-click="ChangeStatus(aPatient)" ng-class="aPatient.myclass">{{aPatient.msg}}
                                </button>
                            </td>
                            <td><?php echo '<a href="' . base_url() . 'appoinment/getInfo/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1" class="btn btn-round btn-success">PAID</button>
                                <button type="button" ng-show="aPatient.payment_status==0" class="btn btn-round btn-warning">UNPAID</button>
                                <button type="button" ng-show="aPatient.payment_status==2" class="btn btn-round btn-info">Partial Payment</button>
                            </td>
                            <td>
                                <button ng-click="ShowPrescription(aPatient)" type="button"
                                        class="btn btn-round btn-success">Prescription
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div ng-show="PatientList.length==0" class="alert alert-danger" role="alert">There is no doctor selected</div>
                </section>
            </div>

        </div>

    </div>
</div>


<script src="<?php echo base_url(); ?>bower_components/angular/angular.min.js"></script>
<script src="<?php echo base_url(); ?>bower_components/angular-sanitize/angular-sanitize.js"></script>
<script src="<?php echo base_url(); ?>js/jQuery.print.js"></script>
<script>
    $(function () {
        $("#clickPrint").click(function () {

            $("#printArea").print({
                //Use Global styles
                globalStyles : true,
                //Add link with attrbute media=print
                mediaPrint : "<?php echo base_url(); ?>css/invoice-print.css",
                //Custom stylesheet
                stylesheet : null,
                //Print in a hidden iframe
                iframe : false,
                //Don't print this
                noPrintSelector : ".avoid-this",
                //Add this at top
                prepend : "",
                //Add this on bottom
                append : ""
            });
        });
    });
    var myApp = angular.module('myApp', ['ngSanitize']);
    myApp.filter("change12hours", function () {
        return function (mytime) {
            var timeString = mytime;
            var H = +timeString.substr(0, 2);
            var h = (H % 12) || 12;
            var ampm = H < 12 ? "AM" : "PM";
            timeString = h + timeString.substr(2, 3) + ampm;
            return timeString;
        }
    });
    myApp.filter("dayNameWord", function () {
        return function (dayintValue) {
            var mm = parseInt(dayintValue) - 1;
            var daysName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            return daysName[(parseInt(dayintValue) - 1)];
        }
    });
    myApp.filter('ageFilter', function() {
        function calculateAge(dateString) { // birthday is a date

            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            var da = today.getDate() - birthDate.getDate();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if(m<0){
                m +=12;
            }
            if(da<0){
                da +=30;
            }
            return age+" yrs ";
        }

        return function(birthdate) {
            return calculateAge(birthdate);
        };
    });

    myApp.controller('myCtrl', function ($scope, $http) {
        $scope.PatientList = [];
        $scope.paymentInfo = {};
        $scope.singlePatient = null;
        $scope.prescripinfo = {};
        $scope.nowServing = "0";
        $scope.showing = function (item) {
            var mm = $scope.nowServing;
            if(parseInt(item.ticket_number)==mm){
                return item;
            }
        };
        $scope.filterSecId = function (items) {
            var result = [];
            angular.forEach(items, function (value, key) {
                if (value.status < 2) {
                    result.push(value);
                }
            });
            return result;
        }
        $scope.ShowInformation = function (todayDate, DoctorID) {
            var postInfo = {
                'todayDate': todayDate,
                'doctor_id': DoctorID
            };
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>appoinment/GetTodayList",
                data: postInfo,
                dataType: "json"
            }).success(function (data) {
              //  console.log(data);
                $scope.PatientList = data.info;
                $scope.nowServing = (data.serve).toString();
                console.log($scope.nowServing);
            }).error(function (data) {
            });
        };
        $scope.ChangeStatus = function (Patient) {

            $scope.singlePatient = Patient;
            $('#myModal').modal({show: true});
            console.log(Patient);
        };
        $scope.StatusChange = function () {
            var statusinfo = $scope.singlePatient;
            var anfo = {
                'app_id': $scope.singlePatient.apid,
                'status': $scope.singlePatient.status,
                'today_date': $scope.ltDate

            };
            statusinfo =   angular.extend(statusinfo,anfo);
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>appoinment/chageStatus",
                data: statusinfo,
                dataType: "json"
            }).success(function (data) {
                if (data.info == true) {
                    $scope.singlePatient = null;
                    $('#myModal').modal('hide');
                }
            }).error(function (data) {
            });

        };

        $scope.showPayment = function(aPaytient){
            console.log(aPaytient);
            $('#paymentInfo').modal({show: true});
            $scope.paymentInfo.Total = aPaytient.visit_price;
            $scope.paymentInfo.appid = aPaytient.apid;
            $scope.paymentInfo.doctorId =aPaytient.adoctor_id;
            $scope.paymentInfo.client_gen_id =aPaytient.client_gen_id;
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>appoinment/getPaymentList",
                data: aPaytient,
                dataType: "json"
            }).success(function (data) {
                if(data!=1){
                    $scope.paymentInfo.paymentList = data;
                }else{
                    $scope.paymentInfo.paymentList = [];
                }

            }).error(function (data) {
            });
        };
        $scope.TotalPaidAmount = function(){
            var count = 0;
            angular.forEach($scope.paymentInfo.paymentList, function(value) {
                count = count + parseFloat(value.amount);
            });
            return count;
        };
        $scope.GetRemainingPayment = function(ainfo,amount){
          ainfo.remain = amount;

            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>appoinment/getamount",
                data: ainfo,
                dataType: "json"
            }).success(function (data) {
                $scope.paymentInfo.paymentList.push({title:"basic payment",payment_date:"abc",amount:ainfo.remain});
                for(var i = 0;i<$scope.PatientList.length;i++){
                    if($scope.PatientList[i].apid==ainfo.appid){
                        $scope.PatientList[i].payment_status = 1;
                        break;
                    }
                }
            }).error(function (data) {
            });
        };
        $scope.ShowPrescription = function (aprescription) {

            $scope.prescripinfo = {};
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>prescription/getPrescriptionInfo',
                method: "POST",
                data: aprescription,
                dataType: "json"
            }).success(function (data) {
                $scope.prescripinfo = data;
            }).error(function (data) {

            });
            // console.log($scope.prescripinfo);
            $('#myModal1').modal('show');
        };
    });
    myApp.filter('valueChange', function () {
        return function (items) {
            var filtered = [];
            for (var i = 0; i < items.length; i++) {
                var aInfo = items[i];
                var item = items[i].status;
                var info = '';
                var classInfo = '';
                if (item == 0) {
                    classInfo = 'btn btn-default';
                    info = "booked";
                }
                else if (item == 1) {
                    classInfo = 'btn btn-primary';
                    info = "present";
                }
                else if (item == 2) {
                    classInfo = 'btn btn-warning';
                    info = "on doctor";
                } else {
                    classInfo = 'btn btn-danger';
                    info = "on output";
                }
                aInfo.msg = info;
                aInfo.myclass = classInfo;
                filtered.push(aInfo);
            }
            return filtered;
        };
    });
</script>

