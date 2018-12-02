<div ng-app="myApp">
    <div ng-controller="myCtrl">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Create New Schedule</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">Doctor Name</label>

                                <div class="col-sm-8">
                                    <select ng-model="aschedule.doctor"
                                            ng-options="adoctor.upro_first_name for adoctor in doctors"
                                            class="form-control">
                                        <option value="">Select doctor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">Valid From Date</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ viewMode: 'years',format: 'YYYY-MM-DD'}"
                                           placeholder="..."
                                           name="time"
                                           ng-model="aschedule.fromtime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">Valid To Date</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ viewMode: 'years',format: 'YYYY-MM-DD'}"
                                           name="time1"
                                           ng-model="aschedule.totime">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" ng-click="InsertSchedule(aschedule)" class="btn btn-primary">
                                        Add Schedule
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit Schedule</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">Valid From Date</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ viewMode: 'years',format: 'YYYY-MM-DD'}"
                                           placeholder="..."
                                           name="time"
                                           ng-model="editschedule.fromtime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">Valid To Date</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ viewMode: 'years',format: 'YYYY-MM-DD'}"
                                           name="time1"
                                           ng-model="editschedule.totime">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" ng-click="UpdateSchedule(editschedule)"
                                            class="btn btn-primary">
                                        Update Schedule
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createTimeBlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Create Time Block</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="day" class="col-lg-4 control-label">Day</label>

                                <div class="col-lg-8">
                                    <select name="day" ng-model="timeBlockAdd.day" id="day" class="form-control">
                                        <option value="">Select a Day</option>
                                        <option value="1">Sunday</option>
                                        <option value="2">Monday</option>
                                        <option value="3">Tuesday</option>
                                        <option value="4">Wednesday</option>
                                        <option value="5">Thursday</option>
                                        <option value="6">Friday</option>
                                        <option value="7">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">From Time</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ format: 'HH:mm'}"
                                           placeholder="..."
                                           name="time"
                                           ng-model="timeBlockAdd.fromtime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">To Time</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ format: 'HH:mm'}"
                                           name="time1"
                                           ng-model="timeBlockAdd.totime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">Time Slot</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="timeBlockAdd.slot">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" ng-click="CreateNewTimeBlock(timeBlockAdd)"
                                            class="btn btn-primary">
                                        Create TimeBlock
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditTimeBlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit Time Block</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="day" class="col-lg-4 control-label">Day</label>

                                <div class="col-lg-8">
                                    <select name="day" ng-model="timeBlockEdit.day" id="day" class="form-control">
                                        <option value="">Select a Day</option>
                                        <option value="1">Sunday</option>
                                        <option value="2">Monday</option>
                                        <option value="3">Tuesday</option>
                                        <option value="4">Wednesday</option>
                                        <option value="5">Thursday</option>
                                        <option value="6">Friday</option>
                                        <option value="7">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">From Time</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ format: 'HH:mm'}"
                                           placeholder="..."
                                           name="time"
                                           ng-model="timeBlockEdit.fromtime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-4 control-label">To Time</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm"
                                           datetimepicker
                                           datetimepicker-options="{ format: 'HH:mm'}"
                                           name="time1"
                                           ng-model="timeBlockEdit.totime">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-4 control-label">Time Slot</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="timeBlockEdit.slot">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" ng-click="UpdateTimeBlock(timeBlockEdit)"
                                            class="btn btn-primary">
                                        Update TimeBlock
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        View All Schedules
                        <div class="text-right"><a ng-click="createSchedule()" href="#" class="btn btn-success">Create
                                Schedule</a></div>
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Select Doctor</label>

                            <div class="col-sm-10">
                                <select ng-model="alldoctor" ng-options="adoctor.upro_first_name for adoctor in doctors"
                                        class="form-control">
                                    <option value="">Select doctor</option>
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="hidden-phone"><i class="icon-question-sign"></i> Doctor</th>

                                <th><i class=" icon-edit"></i>Valid From Date</th>
                                <th><i class=" icon-edit"></i>Valid To Date</th>
                                <th><i class=" icon-edit"></i>Time Blocks</th>
                                <th><i class=" icon-edit"></i>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-class="{active: aSchedule.id == scheduleIds}"
                                ng-repeat="aSchedule in schedules|findSchedule:alldoctor">
                                <td>{{aSchedule.docName}}</td>
                                <td>{{aSchedule.date1}}</td>
                                <td>{{aSchedule.date2}}</td>
                                <td>
                                    <button type="button" ng-click="showTimeBlock(aSchedule)" class="btn btn-success">
                                        TimeBlock
                                    </button>
                                </td>
                                <td>
                                    <button type="button" ng-click="editSchedule(aSchedule)" class="btn btn-warning">
                                        Edit
                                    </button>
                                    <button type="button" ng-click="deleteSchedule(aSchedule)" class="btn btn-danger">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </section>
            </div>

        </div>
        <div style="height: 600px;">
            <div ng-show="scheduleIds!=null" class="row" style="min-height: 400px;">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            View All TimeBlock
                            <div class="text-right"><a ng-click="createTimeblock()" href="#" class="btn btn-success">Create
                                    TimeBlock</a></div>
                        </header>
                        <div class="panel-body">
                            <table class="table" ng-show="blocks.length>0">
                                <thead>
                                <tr>
                                    <th class="hidden-phone"><i class="icon-bookmark"></i> Day</th>
                                    <th><i class=" icon-edit"></i> Time From</th>
                                    <th><i class=" icon-edit"></i> Time To</th>
                                    <th><i class=" icon-edit"></i> Time Slots</th>
                                    <th><i class=" icon-edit"></i> Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="ablocks in blocks">
                                    <td>{{ablocks.week_day|dayNameWord}}</td>
                                    <td>{{ablocks.time_from|change12hours}}</td>
                                    <td>{{ablocks.time_to|change12hours}}</td>
                                    <td>{{ablocks.time_slots}}</td>
                                    <td>
                                        <button type="button" ng-click="editTimeblock(ablocks)" class="btn btn-warning">
                                            Edit
                                        </button>
                                        <button type="button" ng-click="deleteTimeblock(ablocks)"
                                                class="btn btn-danger">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </div>
                    </section>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- create schedule Modal -->
<link rel="stylesheet"
      href="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"/>
<!-- modal -->


<script src="<?php echo base_url(); ?>bower_components/angular/angular.min.js"></script>
<script src="<?php echo base_url(); ?>bower_components/moment/moment.js"></script>
<script
    src="<?php echo base_url(); ?>bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script
    src="<?php echo base_url(); ?>bower_components/angular-bootstrap-datetimepicker-directive/angular-bootstrap-datetimepicker-directive.js"></script>
<script>

    var myApp = angular.module("myApp", ['datetimepicker']);

    myApp.filter("findSchedule", function () {
        return function (data, selectDoctor) {
            var schedules = [];
            if (angular.isArray(data) && angular.isObject(selectDoctor)) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    if (data[i].doctor_id == selectDoctor.uacc_id) {
                        schedules.push(data[i]);
                    }
                }
                return schedules;
            } else {
                return data;
            }
        }
    });

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
    myApp.controller("myCtrl", function ($scope, $http) {
        $scope.scheduleIds = null;
        $scope.editschedule = {};
        $scope.blocks = [];
        $http.get('<?php echo base_url(); ?>schedule/GetAllInfo').
            success(function (data) {
                $scope.doctors = data.doctors;
                $scope.schedules = data.schedule;
                console.log(data.doctors);
            }).
            error(function (data) {

            });

        $scope.createSchedule = function () {
            $('#myModal').modal('show');

        };
        $scope.InsertSchedule = function (abc) {
            var msg = true;
            angular.forEach($scope.schedules, function (todo) {
                if (todo.doctor_id == abc.doctor.uacc_id) {
                    msg = false;
                }
            });
            if (!msg) {
                alert("you already schedule this doctor");
            } else {
                var values = {
                    doctorID: abc.doctor.uacc_id,
                    fromDate: abc.fromtime,
                    toDate: abc.totime
                };
                $http({
                    traditional: true,
                    url: '<?php echo base_url(); ?>schedule/insertSchedule',
                    method: "POST",
                    data: values,
                    dataType: "json"
                }).success(function (data) {
                    $('#myModal').modal('hide');
                    alert("schedule insert successfully");
                    $scope.schedules.push({
                        doctor_id: abc.doctor.uacc_id,
                        docName: abc.doctor.upro_first_name,
                        date1: abc.fromtime,
                        date2: abc.totime,
                        id: data
                    });
                    delete $scope.aschedule;
                }).error(function (data) {

                });
            }
        };

        $scope.deleteSchedule = function (aSchedule) {
            var info = {
                id: aSchedule.id
            };
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/deleteSchedule',
                method: "POST",
                data: info,
                dataType: "json"
            }).success(function (data) {
                alert("schedule delete successfully");
                var index = $scope.schedules.indexOf(aSchedule);
                $scope.schedules.splice(index, 1);

            }).error(function (data) {
            });
        };

        $scope.editSchedule = function (aSchedule) {
            // editschedule
            $scope.editschedule.fromtime = aSchedule.date1;
            $scope.editschedule.totime = aSchedule.date2;
            $scope.editschedule.id = aSchedule.id;
            $('#EditModal').modal('show');
        };

        $scope.UpdateSchedule = function (upschedule) {
            console.log(upschedule);

            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/updateSchedule',
                method: "POST",
                data: upschedule,
                dataType: "json"
            }).success(function (data) {
                alert("schedule update successfully");
                $('#EditModal').modal('hide');
                for (var i = 0; i < $scope.schedules.length; i++) {
                    if ($scope.schedules[i].id == upschedule.id) {
                        $scope.schedules[i].date1 = upschedule.fromtime;
                        $scope.schedules[i].date2 = upschedule.totime;
                    }
                }
            }).error(function (data) {
            });
        };

        $scope.showTimeBlock = function (schedule) {
            $scope.scheduleIds = schedule.id;
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/GetTimeBlock',
                method: "POST",
                data: schedule,
                dataType: "json"
            }).success(function (data) {
                $scope.blocks = data;

            }).error(function (data) {
            });
        };

        $scope.createTimeblock = function () {
            $('#createTimeBlock').modal('show');

        };

        $scope.CreateNewTimeBlock = function (atimeBlocks) {
            atimeBlocks.scheduleid = $scope.scheduleIds;
            console.log(atimeBlocks);
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/InsertTimeBlock',
                method: "POST",
                data: atimeBlocks,
                dataType: "json"
            }).success(function (data) {
                $('#createTimeBlock').modal('hide');
                alert("Time Block insert successfully");
                $scope.blocks.push({
                    schedule_id: atimeBlocks.scheduleid,
                    week_day: atimeBlocks.day,
                    time_from: atimeBlocks.fromtime,
                    time_to: atimeBlocks.totime,
                    time_slots: atimeBlocks.slot,
                    id: data
                });
            }).error(function (data) {

            });

        };

        $scope.deleteTimeblock = function (timeInfo) {
            var info = {
                id: timeInfo.id
            };
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/deleteTimeBlock',
                method: "POST",
                data: info,
                dataType: "json"
            }).success(function (data) {
                alert("Time block delete successfully");
                var index = $scope.blocks.indexOf(timeInfo);
                $scope.blocks.splice(index, 1);

            }).error(function (data) {
            });
        };

        $scope.editTimeblock = function (timeblock) {
            console.log(timeblock);
            var ainfo = {
                day: timeblock.week_day,
                fromtime: timeblock.time_from,
                totime: timeblock.time_to,
                slot: timeblock.time_slots,
                id: timeblock.id

            };
            $scope.timeBlockEdit = ainfo;
            $('#EditTimeBlock').modal('show');
        };

        $scope.UpdateTimeBlock = function (aTimeBlock) {
            $http({
                traditional: true,
                url: '<?php echo base_url(); ?>schedule/updateTimeBlock',
                method: "POST",
                data: aTimeBlock,
                dataType: "json"
            }).success(function (data) {
                alert("Time update successfully");
                $('#EditTimeBlock').modal('hide');
                for (var i = 0; i < $scope.blocks.length; i++) {
                    if ($scope.blocks[i].id == aTimeBlock.id) {
                        $scope.blocks[i].week_day = aTimeBlock.day;
                        $scope.blocks[i].time_from = aTimeBlock.fromtime;
                        $scope.blocks[i].time_to = aTimeBlock.totime;
                        $scope.blocks[i].time_slots = aTimeBlock.slot;
                        break;
                    }
                }


            }).error(function (data) {
            });
        };


    });
</script>

