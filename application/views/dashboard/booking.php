<script src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<style>
    label.error {
        color: red;
        font-weight: bold;
    }
</style>

<div ng-app="myApp">
    <div ng-controller="myCtrl">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Booking
                    </header>
                    <div class="panel-body">
                        <div class="alert alert-success" role="alert">
                            <div class="row">
                                <div class="col-lg-6">Doctor
                                    Name: <?php echo $doctors->upro_first_name . " " . $doctors->upro_last_name; ?><br>
                                    Booking Date: <?php $dtime = new DateTime($output['date']);
                                    echo $dtime->format("dS M Y"); ?></div>
                                <div class="col-lg-6">Visit: <?php echo $doctors->default_visit_price; ?> TK<br>Visit
                                    TIme:<?php echo $time_in_12_hour_format = date("g:i a", strtotime($output['start_time'])); ?>
                                </div>
                            </div>

                        </div>


                        <p ng-if="Previous != null" class="well">Last visited date:  {{Previous.appointment_date}} numbers of day before last visit :{{Previous.DiffDate}} </p>
                        <h1 ng-show="completeInfo!=null">{{completeInfo.msg}}</h1>
                        <form  ng-show="completeInfo==null && todayVisit!=true" class="form-horizontal" name="bookingForm">

                            <div class="form-group">
                                <label for="bclientid" class="col-lg-3 control-label">Client Phone Number/ID</label>

                                <div class="col-lg-4">
                                    <input type="text" class="form-control" ng-model="Patient.mobile" name="mobile"
                                           id="mobile" placeholder="Enter Client Phone Number/ ID ">


                                    <input type="hidden" class="form-control" ng-model="Patient.bookingDate"
                                           ng-init="Patient.bookingDate = '<?php echo $output['date']; ?>'" readonly=""
                                           name="bbookingDate"  value="<?php echo $output['date']; ?>">
                                    <input type="hidden" name="patientDoctorID" ng-model="Patient.patientDoctorID"
                                           ng-init="Patient.patientDoctorID = '<?php echo $output['docid']; ?>'"
                                           value="<?php echo $output['docid']; ?>">
                                    <input type="hidden" ng-model="Patient.visitPrice"
                                           ng-init="Patient.visitPrice = '<?php echo $doctors->default_visit_price; ?>'" class="form-control" name="bvisitprice" id="bclientid"
                                           value="<?php echo $doctors->default_visit_price; ?>">
                                    <input type="hidden" ng-model="Patient.priviousvisitPrice"
                                           ng-init="Patient.priviousvisitPrice = '<?php echo $doctors->previous_visit_price; ?>'" class="form-control" name="bvisitprice" id="bclientid"
                                           value="<?php echo $doctors->previous_visit_price; ?>">
                                    <input type="hidden" ng-model="Patient.previous_visit_day"
                                           ng-init="Patient.previous_visit_day = '<?php echo $doctors->previous_visit_day; ?>'" class="form-control" name="bvisitprice" id="bclientid"
                                           value="<?php echo $doctors->previous_visit_day; ?>">
                                    <input type="hidden" ng-model="Patient.VisitDuration"
                                           ng-init="Patient.VisitDuration = '<?php echo $output['duration']; ?>'" class="form-control" name="bvisitduration"
                                           value="<?php echo $output['duration']; ?>">
                                    <input type="hidden" ng-model="Patient.token"
                                           ng-init="Patient.token = '<?php echo $output['tokon']; ?>'" class="form-control" name="token"
                                           value="<?php echo $output['tokon']; ?>">
                                    <input type="hidden" ng-model="Patient.time"
                                           ng-init="Patient.time = '<?php echo $output['start_time']; ?>'" class="form-control" readonly="" name="bbookingtime"
                                           id="bbookingtime" value="<?php echo $output['start_time']; ?>">
                                </div>
                                <div class="col-lg-3">
                                    <button class="btn btn-primary" ng-disabled="!Patient.mobile" type="button"
                                            ng-click="CheckUsingMobile(Patient)">Search
                                    </button>
                                </div>
                            </div>
                            <div ng-show="showDetails">
                                <div class="form-group">
                                    <label for="clientName" class="col-lg-3 control-label">Client ID</label>

                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" ng-model="Patient.client_gen_id"
                                               readonly=""
                                               name="clientID" id="clientName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="clientName" class="col-lg-3 control-label">Client Name</label>

                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" ng-model="Patient.client_name"
                                               name="clientName"
                                               id="clientName" placeholder="Enter Client Name ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="clientParentsName">Father/Husband
                                        Name</label>

                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" ng-model="Patient.client_parents_name"
                                               name="clientParentsName"
                                               placeholder="Enter Client Father/Husband Name ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="clientParentsName">Payment</label>

                                    <div class="col-lg-8">
                                        <input type="text"  class="form-control" ng-init="Patient.payment = '<?php echo $doctors->default_visit_price; ?>'" name="clientPayment" ng-model="Patient.payment"
                                               placeholder="Enter Payment ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="clientParentsgender">Gender</label>

                                    <div class="col-lg-9">
                                        <label class="checkbox-inline">
                                            <input type="radio" ng-model="Patient.sex"
                                                   name="clientParentsgender"
                                                   value="male"> Male
                                            &nbsp;&nbsp;<input type="radio" ng-model="Patient.sex"
                                                               name="clientParentsgender"
                                                               value="female"> Female
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-10">
                                        <button ng-click="AddPatient(Patient)" ng-disabled="!Patient.payment || !Patient.client_name || !Patient.client_parents_name || !Patient.sex" type="button"
                                                class="btn btn-success">Save
                                        </button>

                                    </div>
                                </div>
                            </div>


                            <?php
                            echo form_close();
                            ?>

                    </div>
                        <div ng-show="todayVisit" class="lead">
                            <h1>You have a booking in today please check this</h1>
                        </div>
                </section>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">View Client List</h4>
                    </div>
                    <div class="modal-body">
                        <section class="panel panel-primary">

                            <table ng-if="PatientInfos.length > 0" class="table table-striped table-advance table-hover"
                                   id="clientDetails">
                                <thead>
                                <tr>
                                    <th>
                                        <li class="icon-info" style="margin-right: 5px;"></li>
                                        Client Id
                                    </th>
                                    <th>
                                        <li class="icon-info" style="margin-right: 5px;"></li>
                                        Client Name
                                    </th>
                                    <th>
                                        <li class="icon-info" style="margin-right: 5px;"></li>
                                        Parents Name
                                    </th>
                                    <th>
                                        <li class="icon-info" style="margin-right: 5px;"></li>
                                        Phone
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-click="AddInformation(aPatient)" ng-repeat="aPatient in PatientInfos">
                                    <td>{{ aPatient.client_gen_id}}</td>
                                    <td>{{ aPatient.client_name}}</td>
                                    <td>{{ aPatient.client_parents_name}}</td>
                                    <td>{{ aPatient.mobile}}</td>
                                </tr>
                                </tbody>

                            </table>
                            <p ng-if="PatientInfos.length==0" class="lead">No data found</p>
                        </section>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
    </div>
</div>
<script src="<?php echo base_url(); ?>bower_components/angular-bootstrap3-datepicker/example/js/angular.js"></script>
<script>
    var myApp = angular.module('myApp', []);
    myApp.controller('myCtrl', function ($scope, $http) {
        $scope.completeInfo = null;
        $scope.showDetails = false;
        $scope.Previous = null;
        $scope.todayVisit = false;
        $scope.CheckUsingMobile = function (Patient) {
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>patient/search_client_details_by_id",
                data: Patient,
                dataType: "json"
            }).success(function (data) {
                $('#myModal').modal({show: true});
                $scope.showDetails = true;
                if (data == 1) {
                    $scope.PatientInfos = [];

                } else {
                    $scope.PatientInfos = data;
                }

            }).error(function (data) {
            });
        };
        $scope.AddInformation = function(patient){

            $('#myModal').modal('hide');


            $scope.Patient.client_gen_id = patient.client_gen_id;
            $scope.Patient.client_name = patient.client_name;
            $scope.Patient.client_parents_name = patient.client_parents_name;
            $scope.Patient.sex = patient.Gender;
            patient.todayDate = $scope.Patient.bookingDate;
            patient.doctorId = $scope.Patient.patientDoctorID;
            $http({
                traditional: true,
                method: "POST",
                url: "<?php echo base_url(); ?>patient/LastVisitedDate",
                data: patient,
                dataType: "json"
            }).success(function (data) {
                if(data!=1){
                    $scope.Previous = data;
                    var mm = parseInt(data.DiffDate);
                    if(mm==0){
                        $scope.todayVisit = true;
                        console.log( $scope.todayVisit);
                    }else{
                        $scope.todayVisit = false;
                        if(parseInt(data.DiffDate)<=parseInt($scope.Patient.previous_visit_day)){
                            $scope.Patient.payment = $scope.Patient.priviousvisitPrice;
                        }
                    }


                }else{
                    $scope.Patient.payment = $scope.Patient.visitPrice;
                }

            }).error(function (data) {
            });
        };

        $scope.AddPatient = function(Patient){
            var apatient = Patient;
            if($scope.Previous==null){
                apatient.FirstTime = 1;
            }else{
                if(parseInt($scope.Previous.DiffDate)<=30){
                    apatient.FirstTime = 0;
                }

            }
            var patientPrice = parseFloat(Patient.visitPrice);
            var patientGiven = parseFloat(Patient.payment);
            if(patientGiven>patientPrice){
                alert("something wrong , you provide more amount then visit price");
            }else{
                $http({
                    traditional: true,
                    method: "POST",
                    url: "<?php echo base_url(); ?>patient/create_booking",
                    data: apatient,
                    dataType: "json"
                }).success(function (data) {
                    $scope.completeInfo = data;
                }).error(function (data) {
                });
            }

        };


    });
</script>

