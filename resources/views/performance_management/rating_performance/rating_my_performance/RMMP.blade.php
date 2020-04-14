@extends('layouts.app')

@section('content')
<style type="text/css">

</style>
<div class="">
    <div class="row justify-content-center">
        <div style="width: 100%;">
            <div class="card">
                <div class="card-header">
                    Performance Management / Rating Performance / Rating My Performance / Rating My Monthly Performance
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4 class="text-center" >PHU QUOC PETROLEUM OPERATING COMPANY<br>
                        <b>{{  __("PERFORMANCE MANAGEMENT SYSTEM") }}</b></h4><br>
                    <h3 class="text-center" ><b>RATING MY MONTHLY PERFORMANCE</b></h3><br>
                    <form action="{{ route('searchRMMP',Auth::user()->id) }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="row">
                            @hasanyrole('general_director')
                            <div class="col-md-2">
                                <p>Select Department:</p>
                            </div>
                            <div class="col-md-3">
                                <select class="selectpicker form-control" data-live-search="true" name="department">
                                    <option value="">Select Department</option>
                                    @foreach($department_list as $pi)
                                        <option value="{{ $pi->user_id }}" @if($department==$pi->user_id) selected="selected" @endif>{{$pi->department}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <p>Select Month/Year:</p>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="month_year" class="datepicker-months text-center form-control col-md-10 " value="{{$year}}">
                            </div>
                            <div class="col-md-2">
                                    <input class="btn btn-success" type="submit" value="Search">
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('submitRateMonthy',Auth::user()->id) }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    @include('layouts.personal_info')
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
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-striped">
                        <thead>
                            <tr class="bg-success">
                                <th>No.</th>
                                <th>
                                    Objective Category
                                </th>
                                <th>
                                    SMART Objectives and Monthly Milestone <br> (MSC) (Verb/Ojective/Timing/Result)
                                </th>
                                <th>
                                    Result Description
                                </th>
                                <th>
                                    Archive (√)
                                </th>
                                <th rowspan="2">
                                    Monthly Rates
                                </th>
                                <th rowspan="2">
                                    Monthly Performance Appraisal Level
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Note
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                        @if(count($rate_monthly_performance)==0)
                        <?php $status = 'Create';?>
                        <input type="hidden" name="year" value="{{$year}}-1">
                        <tr>
                            <td>1</td>
                                <td>
                                    Must do 1
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{0}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                                <td>
                                    Must do 2
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{1}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                                <td>
                                    Must do 3
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{2}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>4</td>
                                <td>
                                    Must do 4
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{3}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>5</td>
                                <td>
                                    Should do 1
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{4}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>6</td>
                                <td>
                                    Should do 2
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{5}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                            <td>7</td>
                                <td>
                                    Could do 1
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{6}}]">
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="" class="form-control text-center"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        @else

                        <?php $i=0; ?>
                            @php $status = ''; @endphp
                             @foreach($rate_monthly_performance as $rmp)
                                 @php $status = $rmp->name; @endphp
                                 <?php switch ($rmp->name) {
                                     case 'Approved':
                                         $classColor = 'text-success';
                                         break;
                                     case 'Rejected':
                                         $classColor = 'text-danger';
                                         break;
                                     default:
                                         $classColor = '';
                                         break;
                                 } ?>
                            @if(strcmp($rmp->name, 'Pending') == 0)
                            <tr>
                                <td>
                                {{$i++}}
                                    <input type="hidden" name="id[]" value="{{$rmp->id}}" >
                                </td>
                                <td>
                                    {{ $rmp->objective_category }}
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="{{  $rmp->objective_and_milestone }}" >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="{{ $rmp->result }}" >
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{$i-1}}]" @if($rmp->achieve == 1) checked = "checked" @endif>
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="{{$rmp->monthly_rate}}" class="form-control text-center"></td>
                                <td>{{$rmp->monthly_performance_level}}</td>
                                <td class="{{ $classColor }}">
                                    {{ App\Http\Controllers\HomeController::getStatus($rmp->status) }}
                                </td>
                                <td>
                                    {{ $rmp->note}}
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td>
                                {{++$i}}
                                    <input type="hidden" name="id[]" value="{{$rmp->id}}" >
                                </td>
                                <td>
                                    {{ $rmp->objective_category }}
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="objective_and_milestone[]" value="{{  $rmp->objective_and_milestone }}" disabled >
                                </td>
                                <td>
                                    <input type="text" class="form-control text-center" name="result[]" value="{{ $rmp->result }}" disabled>
                                </td>
                                <td>
                                    <input type="checkbox" name="achieve[{{$i-1}}]" disabled @if($rmp->achieve == 1) checked = "checked" @endif>
                                </td>
                                <td><input type="text" name="monthly_rate[]" value="{{$rmp->monthly_rate}}" class="form-control text-center" disabled></td>
                                <td>{{$rmp->monthly_performance_level}}</td>
                                <td class="{{ $classColor }}">
                                    {{ App\Http\Controllers\HomeController::getStatus($rmp->status) }}
                                </td>
                                <td>
                                    {{ $rmp->note}}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                     <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <div class="form-group">
                                    <td><label for="staff_sign">Staff Signature:
                                    </label></td>
                                    <td><input type="text" class="form-control col-md-12" name="staff_sign"
                                    @if(strcmp($status, 'Submited') == 0) value="Submited" @endif disabled="disabled"
                                    ></td>
                                </div>
                                <div class="form-group">
                                    <td><label for="manager_sign">Line Manager's Signature:
                                    </label></td>
                                    <td><input type="text" class="form-control col-md-12" name="manager_sign"
                                    @if(strcmp($status, 'Approved') == 0) value="Approved" @elseif(strcmp($status, 'Rejected') == 0)value="Approved" @endif disabled="disabled"
                                    ></td>
                                </div>
                                <div class="form-group">
                                    <td><label for="hrm">HRM Recorded by HRM:
                                    </label></td>
                                    <td><input type="text" class="form-control col-md-12" name="hrm"
                                    @if(strcmp($status, 'Reviewed') == 0) value="Reviewed" @endif disabled="disabled"
                                    ></td>
                                </div>
                            </tr>
                        </table>
                    </div>

                    <div class="form-group text-center">
                        @if(strcmp($status, 'Pending') == 0)
                            <label for="submit"><b>SUBMIT TO DEPARTMENT FOR APPROVAL: </b>&emsp;</label>
                            <input type="submit" name="submit" value="Submit" class="btn btn-success">
                            <button data-action="{{ route('saveRMMP',Auth::user()->id) }}" class="btn btn-success btn-reject" >Save</button>
                        @elseif(strcmp($status, 'Submited') == 0)
                            <button class="col-md-3 btn btn-success no-click" name="submited">Submited</button>
                        @elseif(strcmp($status, 'Approved') ==0)
                            <button class="col-md-3 btn btn-success no-click" name="approved">Approved</button>
                        @elseif(strcmp($status, 'Create') ==0)
                        <label for="submit"><b>SUBMIT TO DEPARTMENT FOR APPROVAL: </b>&emsp;</label>
                        <button data-action="{{ route('submitFirstRMMP',Auth::user()->id) }}" class="btn btn-success btn-reject" >Submit</button>
                        <button data-action="{{ route('createRMMP',Auth::user()->id) }}" class="btn btn-success btn-reject" >Save</button>
                        @endif
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
