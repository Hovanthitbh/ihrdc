@extends('layouts.app')

@section('content')
<style type="text/css">
    #dtHorizontalExample th, td {
    white-space: nowrap;
    }
    .Training table tbody td input[type=text]{
        width: 150px;
    }

    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div style="width: 100%;">
            <div class="card">
                <div class="card-header">
                    Performance Management / Building My MSC Objectives / Building My MSC Objectives / Building My Monthly MSC Objectives
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('postIATP')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <h4 class="text-center" >PHU QUOC PETROLEUM OPERATING COMPANY<br>
                        <b>TRAINING MANAGEMENT SYSTEM</b></h4><br>
                    <h3 class="text-center" ><b>BUILDING MY MONTHLY MSC OBJECTIVES</b></h3><br>
                    <p class="text-left" ><b>GENERAL INFO</b></p>
                    <table style="width: 100%;">
                        <tr>
                            <div class="form-group">
                                <td><label for="Satff_Name">Staff name:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="staffName" value="{{$personal_info->first_name}} {{$personal_info->middle_name}} {{$personal_info->last_name}} " disabled="disabled"><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="Background">Background:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="backGround" value="{{$personal_info->background}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="Supervisor">Supervisor:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="supervisor" value="{{$personal_info->supervisor_name}}" disabled="disabled" ><td></td>
                            </div>
                        </tr>
                        <tr>
                            <div class="form-group">
                                <td><label for="Staff_Number">Staff number:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="staffNumber" value="{{$personal_info->staff_number}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="Education">Education/Academic degree:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="education" value="{{$personal_info->education}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="supervisorJobTitle">Supersivor Job title:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="supervisorJobTitle" value="{{$personal_info->supervisor_job_title}}" disabled="disabled" ><td></td>
                            </div>
                        </tr>
                        <tr>
                            <div class="form-group">
                                <td><label for="email">Email:
                                </label></td>
                                <td><input type="email" class="form-control col-md-10" name="email" value="{{$personal_info->email}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="jobTitle">Job title:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="jobTitle" value="{{$personal_info->job_title}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="supervisorEmail">Supervisor Email:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="supervisorEmail" value="{{$personal_info->supervisor_email}}" disabled="disabled" ><td></td>
                            </div>
                        </tr>
                        <tr>
                            <div class="form-group">
                                <td><label for="dateOfHire">Date joining:
                                </label></td>
                                <td><input type="Date" class="form-control col-md-10" name="dateOfHire" value="{{$personal_info->date_of_hire}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="dateInCurrentJobTitle">Date in current position:
                                </label></td>
                                <td><input type="date" class="form-control col-md-10" name="dateInCurrentJobTitle" value="{{$personal_info->date_in_current_job_title}}" disabled="disabled" ><td></td>
                            </div>
                            <div class="form-group">
                                <td><label for="department">Department:
                                </label></td>
                                <td><input type="text" class="form-control col-md-10" name="department" value="{{$personal_info->department}}" disabled="disabled" ><td></td>
                            </div>
                        </tr>
                    </table><br>
                    <p class="text-left" ><b>ANNUAL MSC OBJECTIVES</b></p>
                    @if(count($errors)>0)
                        <div class="atler">
                            @foreach($errors->all() as $er)
                                <b class="text-danger">{{$er}}</b><br>
                            @endforeach
                        </div>
                    @endif
                    @if(session('notice'))
                        <div class="text-danger">
                            <b>{{session('notice')}}</b>
                        </div>
                    @endif
                    <table style="width: 100%;">
                        <tr>
                            <td>Training & Development period from:</td>
                            <div class="form-group">
                                <td><input type="date" name="dateFrom" class="form-control col-md-8" value=""></td>
                                <td><b>To</b></td>
                                <td><input type="date" name="dateTo" class="form-control col-md-8" value=""></td>
                            </div>
                        </tr>
                    </table><br>  
                    <table class="table table-bordered text-center table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>
                                    Objective Category
                                </th>
                                <th>
                                    SMART Objectives and Monthly Milestone <br> (MSC)(Verb/Ojective/Timing/Result)
                                </th>
                                <th>
                                    Target
                                </th>
                                <th>
                                    Criteria for Success
                                </th>
                                <th>
                                    Action Plans to Achieve Objectives
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                              
                            </tr>
                            <tr>
                                <td>2</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                             
                            </tr>
                            <tr>
                                <td>3</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                        
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                              
                            </tr>
                            <tr>
                                <td>5</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                            
                            </tr>
                            <tr>
                                <td>6</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                       
                            </tr>
                            <tr>
                                <td>7</td>
                                <td></td>
                                <td>
                                    <input type="text" name="smart">
                                </td>
                                <td>
                                    <input type="text" name="target">
                                </td>
                                <td>
                                    <input type="text" name="criteria">
                                </td>
                                <td>
                                    <input type="text" name="action-plans">
                                </td>
                            
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group text-center">
                        <label for="submit"><b>SUBMIT TO SUPERVISOR FOR APPROVAL:</b>&emsp;</label>
                        <input type="submit" name="submit" value="Submit" class="btn btn-success col-md-3">
                    </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection