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
<div class="">
    <div class="row justify-content-center">
        <div style="width: 100%;">
            <div class="card">
                <div class="card-header">
                    Performance Management / Rating Performance / Rating My Performance / Rating My Annual Performance
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h4 class="text-center" >PHU QUOC PETROLEUM OPERATING COMPANY<br>
                        <b>{{  __("PERFORMANCE MANAGEMENT SYSTEM") }}</b></h4><br>
                    <h3 class="text-center" ><b>RATING MY ANNUAL PERFORMANCE</b></h3><br>
                    <form action="{{ route('searchRMAP',Auth::user()->id) }}" method="post">
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
                                       <option value="{{ $pi->user_id }}" @if($department == $pi->user_id) selected="selected" @endif >{{$pi->department}}</option>
                                   @endforeach
                               </select>
                           </div>
                           @endif
                           <div class="col-md-2">
                               <p>Select Year:</p>
                           </div>
                           <div class="col-md-2">
                               <input type="text" name="year" class="datepicker text-center form-control col-md-10 " value="{{ $year }}">
                           </div>
                           <div class="col-md-2">
                               <input type="submit" class="btn btn-success" value="Search">
                           </div>
                        </div>
                    </form>
                    <form action="{{ route('submitRateAnnual',Auth::user()->id) }}" method="post">
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
                        <thead class="bg-success">
                            <tr>
                                <th rowspan="2">
                                    Month / Year
                                </th>
                                <th colspan="7">
                                    Achieved (√)
                                </th>
                                <th rowspan="2">
                                    Monthly Rates
                                </th>
                                <th rowspan="2">
                                    Monthly Performance Appraisal Level
                                </th>
                                <th rowspan="2">
                                    Status
                                </th>
                                <th rowspan="2">
                                    Note
                                </th>
                            </tr>
                            <tr>
                                <th>Must-Do 1</th>
                                <th>Must-Do 2</th>
                                <th>Must-Do 3</th>
                                <th>Must-Do 4</th>
                                <th>Should-Do 1</th>
                                <th>Should-Do 2</th>
                                <th>Could-Do 1</th>


                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;?>
                            @php $status = ''; @endphp
                            @foreach($rate_annual_performance as $rate_annual_performance )
                                @php $status = $rate_annual_performance->name; @endphp
                                <?php switch ($rate_annual_performance->name) {
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
                            <tr>
                                <input type="hidden" name="id[]" value="{{$rate_annual_performance->id}}">
                                <td>{{ date('M-y', strtotime($rate_annual_performance->date)) }}</td>
                                <td><input type="checkbox" disabled="disabled" name="must_do_1[{{$i}}]" @if($rate_annual_performance->must_do_1==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="must_do_2[{{$i}}]" @if($rate_annual_performance->must_do_2==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="must_do_3[{{$i}}]" @if($rate_annual_performance->must_do_3==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="must_do_4[{{$i}}]" @if($rate_annual_performance->must_do_4==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="should_do_1[{{$i}}]" @if($rate_annual_performance->should_do_1==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="should_do_2[{{$i}}]" @if($rate_annual_performance->should_do_2==1) checked="checked"@endif></td>
                                <td><input type="checkbox" disabled="disabled" name="could_do_1[{{$i}}]" @if($rate_annual_performance->could_do_1==1) checked="checked"@endif></td>
                                <td>{{$rate_annual_performance->monthly_rate}}
                                    <input type="hidden" value="{{$rate_annual_performance->monthly_rate}}" name="monthly_rate[]">
                                </td>
                                <td>{{$rate_annual_performance->monthly_performance_level}}</td>
                                <td class="{{ $classColor }}">{{ App\Http\Controllers\HomeController::getStatus($rate_annual_performance->status) }}</td>
                                <td>{{$rate_annual_performance->note}}</td>
                            </tr>
                            <?php $i++;?>
                            @endforeach
                            <tr>
                                <td class="text-left" colspan="2">
                                    Annual Average Rate:
                                </td>
                                <td class="text-left" colspan="10">
                                    {{$avg}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="2">
                                    Convert to Annual Performance Appraisal Level:
                                </td>
                                <td class="text-left" colspan="10">{{$monthly_performance_level}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
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

                        @hasanyrole('department_managers|director|general_director')
                            <button data-action="{{ route('reviewRMAP',Auth::user()->id) }}" class="btn btn-success btn-reject">{{ __("Reviewed") }}</button>
                            {{--<button data-action="{{ route('unlockRMAP',Auth::user()->id) }}" class="btn btn-success btn-reject">{{ __("Unlock") }}</button>--}}
                            <button data-action="{{ route('saveRMAP',Auth::user()->id) }}" class="btn btn-success btn-reject" >{{ __("Save") }}</button>
                        @else
                            @if(strcmp($status, 'Pending') == 0)
                                <label for="submit"><b>SUBMIT TO DEPARTMENT FOR APPROVAL: </b>&emsp;</label>
                                <input type="submit" name="submit" value="Submit" class="btn btn-success">
                                <button data-action="{{ route('saveRMAP',Auth::user()->id) }}" class="btn btn-success btn-reject" >{{ __("Save") }}</button>
                            @elseif(strcmp($status,'Submited') == 0)
                                <button class="col-md-3 btn btn-success no-click" name="submited">Submitted</button>
                            @elseif(strcmp($status,'Approved') == 0)
                                <button class="col-md-3 btn btn-success no-click" name="approved">Approved</button>
                            @endif
                        @endif

                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
