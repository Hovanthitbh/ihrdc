<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Model\course;
use App\Model\personal_info;
use App\Model\training_record;
use App\Model\rate_monthly_performance;
use App\Model\status;
use App\Model\rate_annual_performance;
use App\Model\training_request;
use App\Model\training_employee;
use Illuminate\Support\Facades\Auth;
use App\Model\msc_performance;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\Schema;
use App\Charts\Highcharts;
use PDF;

class HomeController extends Controller
{
    public const STATUS_PENDING = 1;
    public const STATUS_SUBMITED = 2;
    public const STATUS_APPROVED = 3;
    public const STATUS_REJECTED = 4;
    public const STATUS_COMPLETED = 5;
    public const STATUS_REVIEW = 6;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }


    public function getBMPDP($id) {
        $course = course::all();
        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.building_my_msc_objectives.building_my_msc_objectives.BMPDP',['course'=>$course, 'personal_info'=>$personal_info]);
    }
    public function getBMMMO($id, Request $request) {
        $course = course::all();
        $users= personal_info::where('staff_role_id', 3)->get();
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }
        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        $year = date('Y-m');
        $department = '';

        if($this->isHR()) {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")
                ->join('status', 'status.id', '=', 'status')
                ->where('status', '<>', $this::STATUS_PENDING)
                ->where('status', '<>', $this::STATUS_SUBMITED)
                ->where('type', 1);

            if($request->isMethod('POST')) {
                $year = $request->input('dateFrom');
                $department = $request->input('department');
                if($year) {
                    $msc_performance = $msc_performance->where('month_year', 'like', $year."%");
                }
                if($department) {
//                    $msc_performance = $msc_performance->where('user_id', $department);
                }
            }
        } else {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'status.id', '=', 'status')->where('user_id',$id)->where('type', 1);

            if($request->isMethod('POST')) {
                $year = $request->input('dateFrom');
                if($year) {
                    $msc_performance = $msc_performance->where('month_year', 'like', $year."%");
                }
            }
        }

        $msc_performance =$msc_performance->where('year','like', $year."%");
        $msc_performance = $msc_performance->get();

        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.building_my_msc_objectives.building_my_msc_objectives.BMMMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance'=>$msc_performance,'users'=> $users, 'year' => $year, 'department_list' => $departmentList, 'department'=>$department]);
    }

    public function searchMscMonthly ($id, Request $request){
        $course = course::all();
        $users= personal_info::where('staff_role_id', 3)->get();
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }
        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        $year = '';
        $department = '';

        if($this->isHR()) {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('status', '<>', $this::STATUS_PENDING)->where('status', '<>', $this::STATUS_SUBMITED)->where('type', 1);
            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $department = $request->input('department');
                $msc_performance = $msc_performance->where('year', 'like', $year."%")->where('user_id', $department);
            }
        } else {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('user_id',$id)->where('type', 1);

            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $msc_performance = $msc_performance->where('year', 'like', $year."%");
            }
        }


        $msc_performance = $msc_performance->get();

        $personal_info = personal_info::where('user_id',$id)->first();
        if($request->isMethod('POST')) {
            $isPrintPdf = $request->input('isPrintPdf');
            if(strcmp($isPrintPdf, 'true') == 0 ) {
                $data= [
                    'course'=>$course,
                    'personal_info'=>$personal_info,
                    'msc_performance'=>$msc_performance,
                    'users'=>$users,
                    'year' => $year,
                    'department_list' => $departmentList
                ];
                $pdf = PDF::loadView('performance_management.building_my_msc_objectives.building_my_msc_objectives.pdf_BMAMO', $data)->setPaper('a4', 'landscape');
                return $pdf->download('msc_annual.pdf');
            }
        }

        return view('performance_management.building_my_msc_objectives.building_my_msc_objectives.BMMMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance'=>$msc_performance,'users'=>$users, 'year' => $year, 'department_list' => $departmentList, 'department'=>$department]);
    }

    public function getBMAMO($id, Request $request){
        $course = course::all();
        $users= personal_info::where('staff_role_id', 3)->get();
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }
        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        $year = now()->year;
        $department = '';

        if($this->isHR()) {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('status', '<>', $this::STATUS_PENDING)->where('status', '<>', $this::STATUS_SUBMITED)->where('type', 0);
            if($request->isMethod('POST')) {
                $year = $request->input('dateFrom');
                $department = $request->input('department');
                if($year) {
                    $msc_performance = $msc_performance->where('year', 'like', $year."%");
                }
                if($department) {
                    $userIds = $this->getUserIdsByDepartmentId($department);
                    $msc_performance = $msc_performance->whereIn('user_id', $userIds);
                }
            }
        } else {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('user_id',$id)->where('type', 0);

            if($request->isMethod('POST')) {
                $year = $request->input('dateFrom');
                if($year) {
                    $msc_performance = $msc_performance->where('year', 'like', $year."%");
                }
            }
        }


        $msc_performance = $msc_performance->where('year','like',$year."%");
        $msc_performance = $msc_performance->get();

        $personal_info = personal_info::where('user_id',$id)->first();
        if($request->isMethod('POST')) {
            $isPrintPdf = $request->input('isPrintPdf');
            if(strcmp($isPrintPdf, 'true') == 0 ) {
                $data= [
                    'course'=>$course,
                    'personal_info'=>$personal_info,
                    'msc_performance'=>$msc_performance,
                    'users'=>$users,
                    'year' => $year,
                    'department_list' => $departmentList
                ];
                $pdf = PDF::loadView('performance_management.building_my_msc_objectives.building_my_msc_objectives.pdf_BMAMO', $data)->setPaper('a4', 'landscape');
                return $pdf->download('msc_annual.pdf');
            }
        }

        return view('performance_management.building_my_msc_objectives.building_my_msc_objectives.BMAMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance'=>$msc_performance,'users'=>$users, 'year' => $year, 'department_list' => $departmentList, 'department'=>$department]);
    }

    public function searchMscAnnual ($id, Request $request){
        $course = course::all();
        $users= personal_info::where('staff_role_id', 3)->get();
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }
        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        $year = '';
        $department = '';

        if($this->isHR()) {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('status', '<>', $this::STATUS_PENDING)->where('status', '<>', $this::STATUS_SUBMITED)->where('type', 0);
            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $department = $request->input('department');
                if($year) {
                    $msc_performance = $msc_performance->where('year', 'like', $year."%");
                }
                if($department) {
                    $userIds = $this->getUserIdsByDepartmentId($department);
                    $msc_performance = $msc_performance->whereIn('user_id', $userIds);
                }

            }
        } else {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('user_id',$id)->where('type', 0);

            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $msc_performance = $msc_performance->where('year', 'like', $year."%");
            }
        }


        $msc_performance = $msc_performance->get();

        $personal_info = personal_info::where('user_id',$id)->first();
        if($request->isMethod('POST')) {
            $isPrintPdf = $request->input('isPrintPdf');
            if(strcmp($isPrintPdf, 'true') == 0 ) {
                $data= [
                    'course'=>$course,
                    'personal_info'=>$personal_info,
                    'msc_performance'=>$msc_performance,
                    'users'=>$users,
                    'year' => $year,
                    'department_list' => $departmentList
                ];
                $pdf = PDF::loadView('performance_management.building_my_msc_objectives.building_my_msc_objectives.pdf_BMAMO', $data)->setPaper('a4', 'landscape');
                return $pdf->download('msc_annual.pdf');
            }
        }

        return view('performance_management.building_my_msc_objectives.building_my_msc_objectives.BMAMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance'=>$msc_performance,'users'=>$users, 'year' => $year, 'department_list' => $departmentList, 'department'=>$department]);
    }

    public function getAMEAMO($id, Request $request){


        $course = course::all();
        $personal_info = personal_info::where('user_id',$id)->first();
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $msc_performance = msc_performance::join('status', 'status.id', '=', 'status')
            ->whereIn('user_id', $userIds)->where('type', 0)->where('status', $this::STATUS_SUBMITED);

        $year = '';
        $employee = '';
        if($request->isMethod('POST')) {
            $year = $request->input('dateFrom');
            $employee = $request->input('employee');
            if($year) {
                $msc_performance = $msc_performance->where('year', 'like', $year."%");
            }
            if($employee) {
                $msc_performance = $msc_performance->where('user_id', $employee);
            }
        }
        $msc_performance = $msc_performance->get();

        return view('performance_management.building_my_msc_objectives.approve_my_employees_msc_objectives.AMEAMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance' => $msc_performance, 'users'=>$users, 'year' => $year, 'employee' => $employee]);
    }
    public function getAMEMMO($id, Request $request){
        $course = course::all();
        $personal_info = personal_info::where('user_id',$id)->first();
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $msc_performance = msc_performance::join('status', 'status.id', '=', 'status')
            ->whereIn('user_id', $userIds)->where('type', 1)->where('status', $this::STATUS_SUBMITED);

        $year = '';
        $employee = '';
        if($request->isMethod('POST')) {
            $year = $request->input('dateFrom');
            $employee = $request->input('employee');
            if($year) {
                $msc_performance = $msc_performance->where('year', 'like', $year."%");
            }
            if($employee) {
                $msc_performance = $msc_performance->where('user_id', $employee);
            }
        }
        $msc_performance = $msc_performance->get();
        return view('performance_management.building_my_msc_objectives..approve_my_employees_msc_objectives.AMEMMO',['course'=>$course, 'personal_info'=>$personal_info, 'msc_performance' => $msc_performance, 'users'=>$users, 'year' => $year, 'employee' => $employee]);
    }

    public function getPerformaceManagement($id, Request $request) {
        $users = '';
        $department = '';
        $from_date = date('Y-01-01');
        $to_date = date('Y-m-01');
        $users_first = '0';
        if($this->isHR()){
            $departmentList = personal_info::whereNotNull('department_id')->get();
            $departmentIds = array();
            foreach ($departmentList as $user) {
                if( !in_array($user->department_id, $departmentIds) ) {
                    $departmentIds[] = $user->department_id;
                }
            }
            $users = personal_info::whereIn('user_id', $departmentIds)->get();
        }else{
            $users= personal_info::where('department_id', $id)->get();
        }
        if($request->isMethod('POST')){
            $users_first = $request->input('user');
            $to_date = $request->input('to_date')."-1";
            $from_date = $request->input('from_date')."-1";

            $to = $request->input('to_date');
            $from = $request->input('from_date');
        }else{
            $users_first = $users->first();
            if($users_first){
                $users_first = $users_first->user_id;
            }
            $from = date('Y-01');
            $to = date('Y-m');
        }
        $rap = rate_annual_performance::where('user_id', $users_first)->whereBetween('date',[$from_date, $to_date])->get();
        $bar = new Highcharts();
        $bar_all = new Highcharts();
        $pie = new Highcharts();
        $pie_all = new Highcharts();
        $data_bar = collect([]);
        $data_bar_all = collect([]);
        $data_pie = collect([]);
        $data_pie_all = collect([]);
        $rate_annual_performance = rate_annual_performance::where('user_id', $users_first)->whereBetween('date',[$from_date, $to_date])->get();
        $data_pie->push($rate_poor = rate_annual_performance::where('user_id', $users_first)
        ->whereBetween('date',[$from_date, $to_date])->where('monthly_performance_level','like','Improvement Opportunity')->count()/12*100);
        $data_pie->push($rate_avg = rate_annual_performance::where('user_id', $users_first)
        ->whereBetween('date',[$from_date, $to_date])->where('monthly_performance_level','like','Meets Expectation')->count()/12*100);
        $data_pie->push($rate_good = rate_annual_performance::where('user_id', $users_first)
        ->whereBetween('date',[$from_date, $to_date])->where('monthly_performance_level','like','Exceeds Expectation')->count()/12*100);
        $data_pie->push($rate_very_good = rate_annual_performance::where('user_id', $users_first)
        ->whereBetween('date',[$from_date, $to_date])->where('monthly_performance_level','like','Exceeds many Expectation')->count()/12*100);
        $data_pie->push($rate_outstanding = rate_annual_performance::where('user_id', $users_first)
        ->whereBetween('date',[$from_date, $to_date])->where('monthly_performance_level','like','Outstanding')->count()/12*100);
        foreach ($rate_annual_performance as $rate_aunnual){
            $data_bar->push($rate_aunnual->monthly_rate);
        }
        $improvement_opportunity= 0;
        $meets_expectation = 0;
        $exceeds_expectation = 0;
        $exceeds_many_expectation = 0;
        $outstanding= 0;
        foreach ($users as $u){
            $rate_all = rate_annual_performance::where('user_id',$u->user_id)->whereBetween('date',[$from_date, $to_date])->get();
            $avg = $rate_all->avg('monthly_rate');
            if ($avg < 2.5) {
                $improvement_opportunity++;
            }
            if ($avg < 3 && $avg >= 2.5) {
                $meets_expectation++;
            }
            if ($avg < 3.5 && $avg >= 3) {
                $exceeds_expectation++;
            }
            if ($avg < 4.2 && $avg >= 3.5) {
                $exceeds_many_expectation++;
            }
            if ($avg >= 4.2) {
                $outstanding++;
            }
        }
        $data_bar_all->push($improvement_opportunity);
        $data_bar_all->push($meets_expectation);
        $data_bar_all->push($exceeds_expectation);
        $data_bar_all->push($exceeds_many_expectation);
        $data_bar_all->push($outstanding);
        if(count($users)==0){
            $count_user = 1;
        }else{
            $count_user =count($users);
        }

        $data_pie_all->push($improvement_opportunity/$count_user*100);
        $data_pie_all->push($meets_expectation/$count_user*100);
        $data_pie_all->push($exceeds_expectation/$count_user*100);
        $data_pie_all->push($exceeds_many_expectation/$count_user*100);
        $data_pie_all->push($outstanding/$count_user*100);

        $bar->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $pie->labels(['Improvement Opportunity','Meets Expectation','Exceeds Expectation','Exceeds many Expectation','Outstanding']);
        $bar_all->labels(['Improvement Opportunity','Meets Expectation','Exceeds Expectation','Exceeds many Expectation','Outstanding']);
        $pie_all->labels(['Improvement Opportunity','Meets Expectation','Exceeds Expectation','Exceeds many Expectation','Outstanding']);

        $bar->dataset('Rate Annual', 'column', $data_bar);
        $bar->options([
            'yAxis'=> [ //--- Primary yAxis
                'title'=> [
                    'text'=> 'Mothnly rate'
                ],
                'max'=>'5',
            ],
        ]);

        $bar_all->dataset('Rate Annual', 'column', $data_bar_all);
        $bar_all->options([
            'yAxis'=> [ //--- Primary yAxis
                'title'=> [
                    'text'=> 'Total Staff'
                ],
            ],
            'color' => ['red','#FF8C00','Violet','blue','green'],
        ]);

        $pie->dataset('Rate Annual', 'pie', $data_pie)->options([
            'chart'=> [
              'plotBackgroundColor'=> null,
              'plotBorderWidth'=> null,
              'plotShadow'=> false,
            ],
            'color' => ['red','#FF8C00','Violet','blue','green'],
            'tooltip'=> [
              'pointFormat'=> '{series.name}: <br>{point.percentage:.1f} %<br>value: {point.y}'
            ],
            'plotOptions'=> [
              'pie'=> [
                'dataLabels'=> [
                  'enabled'=> true,
                  'format'=> '<b>{point.name}</b>:<br>{point.percentage:.1f} %<br>value: {point.y}',
                ]
              ]
            ]
        ]);
        $pie_all->dataset('Rate Annual', 'pie', $data_pie_all)->options([
            'chart'=> [
              'plotBackgroundColor'=> null,
              'plotBorderWidth'=> null,
              'plotShadow'=> false,
            ],
            'color' => ['red','#FF8C00','Violet','blue','green'],
            'tooltip'=> [
              'pointFormat'=> '{series.name}: <br>{point.percentage:.1f} %<br>value: {point.y}'
            ],
            'plotOptions'=> [
              'pie'=> [
                'dataLabels'=> [
                  'enabled'=> true,
                  'format'=> '<b>{point.name}</b>:<br>{point.percentage:.1f} %<br>value: {point.y}',
                ]
              ]
            ]
        ]);
        return view('performance_management/performance_management', ['bar' => $bar, 'pie'=>$pie,'bar_all'=>$bar_all,'pie_all'=>$pie_all, 'rap'=>$rap,'users'=>$users, 'users_first'=>$users_first,'from_date'=>$from ,'to_date'=>$to]);
    }

    public function getRMAP($id, Request $request){
        $course = course::all();

        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }

        $year = now()->year;
        $department = '';

        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        if($this->isHR()) {
            $rate_annual_performance = rate_annual_performance::select("rate_annual_performance.*", "status.name")
                ->join('status', 'status.id', '=', 'status')
                ->where('status', '<>', $this::STATUS_PENDING)
                ->where('status', '<>', $this::STATUS_SUBMITED);
            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $department = $request->input('department');
                if($year) {
                    $rate_annual_performance = $rate_annual_performance->where('year', 'like', $year."%");
                }
                if($department) {
//                    $rate_annual_performance = $rate_annual_performance->where('user_id', $department);
                }
            }
        } else {
            $rate_annual_performance = rate_annual_performance::select("rate_annual_performance.*", "status.name")->join('status', 'status.id', '=', 'status')
                ->where('user_id',$id);

        }
        $rate_annual_performance = $rate_annual_performance->where('date', 'like', $year."%");
        $rate_annual_performance = $rate_annual_performance->get();
        $avg = 0;
        foreach ($rate_annual_performance as $rate){
            $avg += $rate->monthly_rate;
        }
        if(count($rate_annual_performance)) {
            $avg = $avg/count($rate_annual_performance);
        } else {
            $avg = 0;
        }

        if($avg<2.5){
            $monthly_performance_level = 'Improvement Opportunity';
        }
        if($avg<3 && $avg>=2.5){
            $monthly_performance_level = 'Meets Expectation';
        }
        if($avg<3.5 && $avg>=3){
            $monthly_performance_level = 'Exceeds Expectation';
        }
        if($avg<4.2 && $avg>=3.5){
            $monthly_performance_level = 'Exceeds many Expectation';
        }
        if($avg>=4.2){
            $monthly_performance_level = 'Outstanding';
        }

        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.rating_my_performance.RMAP',['course'=>$course, 'personal_info'=>$personal_info, 'rate_annual_performance'=>$rate_annual_performance,'avg'=>$avg, 'year' => $year, 'department_list' => $departmentList, 'department'=>$department,'monthly_performance_level'=>$monthly_performance_level]);
    }

    public function searchRMAP($id, Request $request){
        $course = course::all();
        $year = $request->input('year');
        $personal_info = personal_info::where('user_id',$id)->first();
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        $department = '';
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }
        if($this->isHR()){
            $department = $request->input('department');
            $rate_annual_performance = rate_annual_performance::where('year','like' ,$year.'%')->where('user_id',$department)->get();
        }
        else{
            $rate_annual_performance = rate_annual_performance::where('year','like' ,$year.'%')->where('user_id',$id)->get();
        }
        $avg = 0;
        foreach ($rate_annual_performance as $rate){
            $avg += $rate->monthly_rate;
        }
        if($avg!= 0){
            $avg = $avg/count($rate_annual_performance);
        }
        return view('performance_management.rating_performance.rating_my_performance.RMAP',['course'=>$course, 'personal_info'=>$personal_info, 'rate_annual_performance'=>$rate_annual_performance,'avg'=>$avg,'year'=>$year, 'department_list'=>$departmentList,'department'=>$department]);
    }


    public function getRMMP($id, Request $request) {
        $course = course::all();

        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }

        $year = date('Y-m');
        $department = '';

        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();

        if($this->isHR()) {
            $rate_monthly_performance = rate_monthly_performance::select("rate_monthly_performance.*", "status.name")
                ->join('status', 'status.id', '=', 'status')
                ->where('status', '<>', $this::STATUS_PENDING)->where('status', '<>', $this::STATUS_SUBMITED);
            if($request->isMethod('POST')) {
                $year = $request->input('month_year');
                $department = $request->input('department');
                if($year) {
                    $rate_monthly_performance = $rate_monthly_performance->where('month_year', 'like', $year."%");
                }
                if($department) {
                    $rate_monthly_performance = $rate_monthly_performance->where('user_id', $department);
                }
            }
        } else {
            $rate_monthly_performance = rate_monthly_performance::select("rate_monthly_performance.*", "status.name")->join('status', 'status.id', '=', 'status')
                ->where('user_id',$id);
        }
        $rate_monthly_performance = $rate_monthly_performance->where('month_year','like', $year."%");
        $rate_monthly_performance = $rate_monthly_performance->get();

        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.rating_my_performance.RMMP',['course'=>$course, 'personal_info'=>$personal_info,'rate_monthly_performance'=>$rate_monthly_performance, 'department_list' => $departmentList, 'year' => $year, 'department'=>$department]);
    }

    public function searchRMMP($id, Request $request){

        $course = course::all();
        $month_year = $request->input('month_year');
        $personal_info = personal_info::where('user_id',$id)->first();
        $department = '';
        $departmentList = '';
        if($this->isHR()) {
            $departmentList = personal_info::whereNotNull('department_id')->get();
            $departmentIds = array();
            foreach ($departmentList as $user) {
                if( !in_array($user->department_id, $departmentIds) ) {
                    $departmentIds[] = $user->department_id;
                }
            }
            $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
            $month_year = $request->input('month_year');
            $department = $request->input('department');
            $rate_monthly_performance = rate_monthly_performance::select("rate_monthly_performance.*", "status.name")->join('status', 'status.id', '=', 'status')
                ->where('user_id',$id);
            $rate_monthly_performance = $rate_monthly_performance->where('month_year','like' ,$month_year.'%')->get();
            if($request->isMethod('POST')) {
                    $year = $request->input('month_year');
                    $department = $request->input('department');
                }
        } else {
            $rate_monthly_performance = rate_monthly_performance::select("rate_monthly_performance.*", "status.name")->join('status', 'status.id', '=', 'status')
                ->where('user_id',$id);
            $rate_monthly_performance = $rate_monthly_performance->where('month_year','like' ,$month_year.'%')->get();
            if($request->isMethod('POST')) {
                    $year = $request->input('month_year');
                }
        }

        return view('performance_management.rating_performance.rating_my_performance.RMMP',['course'=>$course, 'personal_info'=>$personal_info,'rate_monthly_performance'=>$rate_monthly_performance, 'year'=>$year,'department'=>$department,'department_list'=>$departmentList]);
    }

    public function getAMEAP($id) {
        $course = course::all();
        $userIds = array();
        $users= personal_info::where('department_id', $id)->get();
        $employee = '';
        $year = '';
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_annual_performance = rate_annual_performance::join('status', 'status.id', '=', 'status')
            ->whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();
        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.approving_my_employees_performance.AMEAP',['course'=>$course, 'personal_info'=>$personal_info,'rate_annual_performance'=>$rate_annual_performance, 'users'=>$users,'employee'=>$employee,'year'=>$year]);
    }

    public function searchAMEAP($id, Request $request) {
        $course = course::all();
        $userIds = array();
        $users= personal_info::where('department_id', $id)->get();
        $year = $request->input('year');
        $employee =$request->input('employee');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_annual_performance = rate_annual_performance::where('year','like' ,$year.'%')->where('user_id',$employee)->whereIn('user_id', $userIds)->where('status', $this::STATUS_SUBMITED)->get();
        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.approving_my_employees_performance.AMEAP',['course'=>$course, 'personal_info'=>$personal_info,'rate_annual_performance'=>$rate_annual_performance, 'users'=>$users,'employee'=>$employee, 'year'=>$year]);
    }

    public function getAMEMP($id){
        $course = course::all();
        $users= personal_info::where('department_id', $id)->get();
        $employee = '';
        $month_year = '';
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_monthly_performance = rate_monthly_performance::join('status', 'status.id', '=', 'status')
            ->whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();
        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.approving_my_employees_performance.AMEMP',['course'=>$course, 'personal_info'=>$personal_info,'rate_monthly_performance'=>$rate_monthly_performance, 'users'=>$users, 'employee'=>$employee, 'month_year'=>$month_year]);
    }

    public function searchAMEMP($id, Request $request){
        $course = course::all();
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        $month_year = $request->input('month_year');
        $employee = $request->input('employee');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $rate_monthly_performance = rate_monthly_performance::where('month_year','like' ,$month_year.'%')->where('user_id',$employee)->whereIn('user_id', $userIds)->where('status', $this::STATUS_SUBMITED)->get();
        $personal_info = personal_info::where('user_id',$id)->first();
        return view('performance_management.rating_performance.approving_my_employees_performance.AMEMP',['course'=>$course, 'personal_info'=>$personal_info,'rate_monthly_performance'=>$rate_monthly_performance, 'users'=>$users,'employee'=>$employee, 'month_year'=>$month_year]);
    }

    public static function isHR() {
        return $currentUser = Auth::user()->hasRole('general_director');
    }

    public function getCurrentUserStatus() {
        $status = array();
        $currentUser = Auth::user();
        $userRoles = $currentUser->getRoleNames();
        foreach ($userRoles as $role) {
            switch ($role) {
                case 'department_managers':
                    array_push($status, $this::STATUS_SUBMITED);
                    break;
                case 'director':
                    array_push($status, $this::STATUS_SUBMITED);
                    break;
                case 'general_director':
                    array_push($status, $this::STATUS_APPROVED);
                    break;
                default:
                    break;
            }
        }

        return $status;
    }

    public static function getStatus($status){
        $status_name = status::where('id', $status)->first();
        $status_name = $status_name->name;
        return $status_name;
        }

    public function unlockBMAMO($id, Request $request) {
        if($this->isHR()) {
            $msc_performance = msc_performance::select("msc_performance.*", "status.name")->join('status', 'msc_performance.status', '=', 'status.id')->where('status', $this::STATUS_APPROVED)->where('type', 0);
            if($request->isMethod('POST')) {
                $year = $request->input('year');
                $department = $request->input('department');
                if($year) {
                    $msc_performance = $msc_performance->where('year', 'like', $year."%");
                }
                if($department) {
                    $userIds = $this->getUserIdsByDepartmentId($department);
                    $msc_performance = $msc_performance->whereIn('user_id', $userIds);
                }

                $msc_performance = $msc_performance->get();

                foreach ($msc_performance as $msc) {
                    if($msc->status === $this::STATUS_APPROVED) {
                        $msc->status = $this::STATUS_PENDING;
                        $msc->save();
                    }
                }
            }
        }

        return redirect()->route('BMAMO',Auth::user()->id);
    }

    public function getUserIdsByDepartmentId($departmentIds) {
        $result = personal_info::select('user_id')->where('department_id', $departmentIds)->get();
        $userIds = array();
        foreach ($result as $row) {
            if( !in_array($row['user_id'], $userIds) ) {
                $userIds[] = $row['user_id'];
            }
        }

        return $userIds;
    }

}
