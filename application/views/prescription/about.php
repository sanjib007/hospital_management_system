<link href="<?php echo base_url(); ?>css/invoice-print.css" rel="stylesheet" media="print">
<div ng-app="myApp">
    <div ng-controller="myCtrl">
        <div id="printHide" class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body progress-panel">
                        <div class="task-progress">

                            <form class="form-inline" role="form">
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                    <input type="text" class="form-control" datetimepicker
                                           datetimepicker-options="{ viewMode: 'days',format: 'YYYY-MM-DD'}"
                                           placeholder="..."
                                           name="time"
                                           ng-model="selectDate">
                                    <button ng-click="GetInformation(selectDate)" type="submit" class="btn btn-success">
                                        Search
                                    </button>

                                </div>

                            </form>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="task-option">
                            <button ng-click="getNextPatient()" class="btn btn-success text-right">
                                next
                            </button>
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in PatientList|filter: showing">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td><?php echo '<a href="' . base_url() . 'prescription/prescribe/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1"
                                        class="btn btn-round btn-success">PAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==0"
                                        class="btn btn-round btn-warning">UNPAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==2"
                                        class="btn btn-round btn-info">Partial Payment
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        On Queue
                    </header>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in filterSecId(PatientList)| orderBy:'ticket_number'|filter:{ ticket_number:'!' + nowServing }">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td><?php echo '<a href="' . base_url() . 'prescription/prescribe/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1"
                                        class="btn btn-round btn-success">PAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==0"
                                        class="btn btn-round btn-warning">UNPAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==2"
                                        class="btn btn-round btn-info">Partial Payment
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Completed
                    </header>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Client Name</th>
                            <th>Client Id</th>
                            <th>Time</th>
                            <th>Token No</th>
                            <th>Pescribe</th>
                            <th>Payment Status</th>
                            <th>Prescription</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="aPatient in PatientList| orderBy:'ticket_number'|filter:{ status:3 }">
                            <td>{{$index + 1}}</td>
                            <td>{{aPatient.client_name}}</td>
                            <td>{{aPatient.client_gen_id}}</td>
                            <td>{{aPatient.mtime}}</td>
                            <td>{{aPatient.ticket_number}}</td>
                            <td><?php echo '<a href="' . base_url() . 'prescription/prescribe/{{aPatient.client_gen_id}}/{{aPatient.apid}}/{{aPatient.adoctor_id}}' . '" target="_blank">show report/pescribe</a>'; ?></td>
                            <td ng-click="showPayment(aPatient)">
                                <button type="button" ng-show="aPatient.payment_status==1"
                                        class="btn btn-round btn-success">PAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==0"
                                        class="btn btn-round btn-warning">UNPAID
                                </button>
                                <button type="button" ng-show="aPatient.payment_status==2"
                                        class="btn btn-round btn-info">Partial Payment
                                </button>
                            </td>
                            <td>
                                <button ng-click="ShowPrescription(aPatient)" type="button"
                                        class="btn btn-round btn-success">Prescription
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" class="example">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
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
                                    <h4>Doctor Schedule</h4>
                                    <ul class="unstyled">
                                        <li ng-repeat="aschedule in prescripinfo.schedule">
                                            Day:{{aschedule.week_day|dayNameWord}}
                                            Time:{{aschedule.time_from|change12hours}}-{{aschedule.time_to|change12hours}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5 col-sm-5"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Name:
                                            {{prescripinfo.patient.client_name}}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Age:
                                            {{ prescripinfo.patient.dateOfBirth | ageFilter }}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px">Sex:
                                            {{prescripinfo.patient.Gender}}
                                        </div>
                                        <div class="col-lg-2 col-sm-2"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">
                                            ID
                                            No: {{prescripinfo.patient.client_gen_id}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-8 col-sm-8"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">
                                            Address:
                                            {{prescripinfo.patient.present_address}}
                                        </div>
                                        <div class="col-lg-3 col-sm-3"
                                             style="border-bottom:1px solid black; padding:5px; margin-left:10px; margin-top:2px">
                                            Date:16
                                            th june , 2015
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-3"
                                     style="margin-left:10px; margin-top:10px; padding: 20px 0;  border-right: 2px solid black; padding-right: 10px;">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider"
                                                   style="text-align: left; padding-left: 10px;"
                                                   href="#"><b>Complains</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.complains}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider"
                                                   style="text-align: left; padding-left: 10px;" href="#"><b>Symtoms</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.symtoms}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="nav nav-pills nav-stacked labels-info">
                                                <a class="btn btn-compose inbox-divider"
                                                   style="text-align: left; padding-left: 10px;"
                                                   href="#"><b>Diagonosis</b></a>
                                                <li>
                                                    <p class="wall">{{prescripinfo.prescription.diagonosis}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-12">
                                            <ul class="list-group">
                                                <a class="btn btn-compose" style="text-align: left; padding-left: 10px;"
                                                   href="#"><b>Basic</b></a>
                                                <li class="list-group-item">
                                                    Temperature: {{prescripinfo.prescription.temperature}}
                                                </li>
                                                <li class="list-group-item">
                                                    Blood Presure: {{prescripinfo.prescription.blood_pressure}}
                                                </li>
                                                <li class="list-group-item">
                                                    Height: {{prescripinfo.prescription.height}}
                                                </li>
                                                <li class="list-group-item">
                                                    Weight: {{prescripinfo.prescription.weight}}
                                                </li>
                                                <li class="list-group-item">
                                                    Blood Group: {{prescripinfo.patient.bloodGroup}}
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
                                                    <td><p>{{ainfo.medicine_name}}</p>

                                                        <p>(Paracetamol)</p></td>
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
    </div>
</div>


<link rel="stylesheet"
      href="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"/>
<!-- modal -->


<script src="<?php echo base_url(); ?>bower_components/angular/angular.min.js"></script>
<script src="<?php echo base_url(); ?>bower_components/moment/moment.js"></script>
<script
    src="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script
    src="<?php echo base_url(); ?>bower_components/angular-bootstrap-datetimepicker-directive/angular-bootstrap-datetimepicker-directive.js"></script>
<script src="<?php echo base_url(); ?>bower_components/angular-sanitize/angular-sanitize.js"></script>
<script src="<?php echo base_url(); ?>js/jQuery.print.js"></script>
<script>

    $(function () {
        $("#clickPrint").click(function () {

            $("#printArea").print({
                //Use Global styles
                globalStyles: true,
                //Add link with attrbute media=print
                mediaPrint: "<?php echo base_url(); ?>css/invoice-print.css",
                //Custom stylesheet
                stylesheet: null,
                //Print in a hidden iframe
                iframe: false,
                //Don't print this
                noPrintSelector: ".avoid-this",
                //Add this at top
                prepend: "",
                //Add this on bottom
                append: ""
            });
        });
    });


    var myApp = angular.module('myApp', ['datetimepicker', 'ngSanitize']);
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

    myApp.filter('ageFilter', function () {
        function calculateAge(dateString) { // birthday is a date

            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            var da = today.getDate() - birthDate.getDate();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (m < 0) {
                m += 12;
            }
            if (da < 0) {
                da += 30;
            }
            return age + " yrs ";
        }

        return function (birthdate) {
            return calculateAge(birthdate);
        };
    });
    myApp.controller('myCtrl', function ($scope, $http, $timeout) {
        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1;
        var year = currentDate.getFullYear();
        $scope.nowServing = 0;
        $scope.PatientList = [];
        $scope.prescripinfo = {};
        $scope.showing = function (item) {
            var mm = $scope.nowServing;
            if(parseInt(item.ticket_number)==mm){
                return item;
            }
        };
        function getGetData(nowtime) {
            var info = {
                todayDate: nowtime,
                doctor_id: <?php echo $this->flexi_auth->get_user_id(); ?>
            };
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>appoinment/GetTodayList',
                method: "POST",
                data: info,
                dataType: "json"
            }).success(function (data) {
                $scope.PatientList = data.info;
                $scope.nowServing = (data.serve).toString();
                console.log($scope.nowServing);
            }).error(function (data) {

            });
        }

        $scope.selectDate = year + "-" + month + "-" + day;
        getGetData($scope.selectDate);
        $scope.filterSecId = function (items) {
            var result = [];
            angular.forEach(items, function (value, key) {
                if (value.status < 2) {
                    result.push(value);
                }
            });
            return result;
        }
        $scope.GetInformation = function (nowDate) {
            getGetData(nowDate);
        };

        $scope.ShowPrescription = function (aprescription) {
            console.log(aprescription);
            $scope.prescripinfo = {};
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>prescription/getPrescriptionInfo',
                method: "POST",
                data: aprescription,
                dataType: "json"
            }).success(function (data) {
                $scope.prescripinfo = data;
                console.log($scope.prescripinfo);
            }).error(function (data) {

            });
            // console.log($scope.prescripinfo);
            $('#myModal').modal('show');
        };

        $scope.getNextPatient = function () {
            var info = {
                dates: $scope.selectDate
            };
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>appoinment/getNextPatient',
                method: "POST",
                data: info,
                dataType: "json"
            }).success(function (data) {
                console.log(data);
                alert("your next patient coming");
                $scope.nowServing = data;
            }).error(function (data) {

            });
        };


    });
</script>

