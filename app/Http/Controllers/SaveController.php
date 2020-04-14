<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\personal_info;
use App\Model\rate_annual_performance;
use App\Model\rate_monthly_performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    public function saveRMAP($id, Request $request){
        if($request->isMethod('post') && $request->has("id")) {
            $count = count($request->input("id"));
            $ids = $request->input("id");
            $monthly_rate = $request->input('monthly_rate');

            for($i = 0; $i < $count; $i++) {
                $rate_annual_performance = rate_annual_performance::find($ids[$i]);
                if($rate_annual_performance->id) {
                    if($monthly_rate[$i]<2.5){
                        $rate_annual_performance->monthly_performance_level = 'Improvement Opportunity';
                    }
                    if($monthly_rate[$i]<3 && $monthly_rate[$i]>=2.5){
                        $rate_annual_performance->monthly_performance_level = 'Meets Expectation';
                    }
                    if($monthly_rate[$i]<3.5 && $monthly_rate[$i]>=3){
                        $rate_annual_performance->monthly_performance_level = 'Exceeds Expectation';
                    }
                    if($monthly_rate[$i]<4.2 && $monthly_rate[$i]>=3.5){
                        $rate_annual_performance->monthly_performance_level = 'Exceeds many Expectation';
                    }
                    if($monthly_rate[$i]>=4.2){
                        $rate_annual_performance->monthly_performance_level = 'Outstanding';
                    }
                    // Set another data here
                    $rate_annual_performance->save();
                }
            }
        }


        return redirect()->route('RMAP',Auth::user()->id);
    }

    public function saveRMMP($id, Request $request){
        if($request->isMethod('post') && $request->has("id")) {
            $count = count($request->input("id"));
            $ids = $request->input("id");
            $objective_and_milestone = $request->input('objective_and_milestone');
            $result = $request->input('result');
            $achieve = $request->input('achieve');
            $monthly_rate = $request->input('monthly_rate');

            for($i = 0; $i < $count; $i++) {
                $rate_monthly_performance = rate_monthly_performance::find($ids[$i]);
                if($rate_monthly_performance->id) {
                    $rate_monthly_performance->objective_and_milestone = $objective_and_milestone[$i];
                    $rate_monthly_performance->result = $result[$i];
                    $rate_monthly_performance->monthly_rate = $monthly_rate[$i];
                    if(isset($achieve[$i])){
                        $rate_monthly_performance->achieve = 1;
                    }else{
                        $rate_monthly_performance->achieve = 0;
                    }

                    if($monthly_rate[$i]<2.5){
                        $rate_monthly_performance->monthly_performance_level = 'Improvement Opportunity';
                    }
                    if($monthly_rate[$i]<3 && $monthly_rate[$i]>=2.5){
                        $rate_monthly_performance->monthly_performance_level = 'Meets Expectation';
                    }
                    if($monthly_rate[$i]<3.5 && $monthly_rate[$i]>=3){
                        $rate_monthly_performance->monthly_performance_level = 'Exceeds Expectation';
                    }
                    if($monthly_rate[$i]<4.2 && $monthly_rate[$i]>=3.5){
                        $rate_monthly_performance->monthly_performance_level = 'Exceeds many Expectation';
                    }
                    if($monthly_rate[$i]>=4.2){
                        $rate_monthly_performance->monthly_performance_level = 'Outstanding';
                    }
                    // Set another data here
                    $rate_monthly_performance->save();

                    //save annual
                    if($i == ($count-1)){
                         $this->saveAnnual($rate_monthly_performance->month_year, $id);
                    }
                }
            }
        }


        return redirect()->route('RMMP',Auth::user()->id);
    }

    public function saveAnnual($date, $id)
    {
        $date = date('Y-m', strtotime($date));
        $rate_annual_performance = rate_annual_performance::where('date', 'like', $date . '%')->where('user_id',$id)->first();
        $rate_monthly_performance = rate_monthly_performance::where('month_year', 'like', $date . '%')->where('user_id', $id)->get();
        $avg = 0;
        $count = rate_monthly_performance::where('month_year', 'like', $date . '%')->where('user_id', $id)->count();
        foreach ($rate_monthly_performance as $monthly) {
            $objective_category = $monthly->objective_category;
            $rate_annual_performance->$objective_category = $monthly->achieve;
            $avg += $monthly->monthly_rate;
        }
        $avg = $avg / $count;
        if ($avg < 2.5) {
            $rate_annual_performance->monthly_performance_level = 'Improvement Opportunity';
        }
        if ($avg < 3 && $avg >= 2.5) {
            $rate_annual_performance->monthly_performance_level = 'Meets Expectation';
        }
        if ($avg < 3.5 && $avg >= 3) {
            $rate_annual_performance->monthly_performance_level = 'Exceeds Expectation';
        }
        if ($avg < 4.2 && $avg >= 3.5) {
            $rate_annual_performance->monthly_performance_level = 'Exceeds many Expectation';
        }
        if ($avg >= 4.2) {
            $rate_annual_performance->monthly_performance_level = 'Outstanding';
        }
        $rate_annual_performance->monthly_rate = $avg;
        $rate_annual_performance->save();

    }

    public  function createRMMP($id, Request $request){
        $departmentList = personal_info::whereNotNull('department_id')->get();
        $departmentIds = array();
        foreach ($departmentList as $user) {
            if( !in_array($user->department_id, $departmentIds) ) {
                $departmentIds[] = $user->department_id;
            }
        }

        $department = '';

        $departmentList = personal_info::whereIn('user_id', $departmentIds)->get();
        if($request->isMethod('post')){
            $objective_category = ['Must_Do_1', 'Must_Do_2','Must_Do_3','Must_Do_4','Should_Do_1', 'Should_Do_2','Could_Do_1'];
            $objective_and_milestone = $request->input('objective_and_milestone');
            $result = $request->input('result');
            $achieve = $request->input('achieve');
            $monthly_rate = $request->input('monthly_rate');
            $year = $request->year;
            for($i = 0; $i < 7; $i++) {
                $rate_monthly_performance = new rate_monthly_performance();
                $rate_monthly_performance->user_id = $id;
                $rate_monthly_performance->month_year = $year;
                $rate_monthly_performance->status = 1;
                $rate_monthly_performance->objective_category = $objective_category[$i];
                $rate_monthly_performance->objective_and_milestone = $objective_and_milestone[$i];
                $rate_monthly_performance->result = $result[$i];
                $rate_monthly_performance->monthly_rate = $monthly_rate[$i];
                if(isset($achieve[$i])){
                    $rate_monthly_performance->achieve = 1;
                }else{
                    $rate_monthly_performance->achieve = 0;
                }

                if($monthly_rate[$i]<2.5){
                    $rate_monthly_performance->monthly_performance_level = 'Improvement Opportunity';
                }
                if($monthly_rate[$i]<3 && $monthly_rate[$i]>=2.5){
                    $rate_monthly_performance->monthly_performance_level = 'Average';
                }
                if($monthly_rate[$i]<3.5 && $monthly_rate[$i]>=3){
                    $rate_monthly_performance->monthly_performance_level = 'Meets Expectation';
                }
                if($monthly_rate[$i]<4.2 && $monthly_rate[$i]>=3.5){
                    $rate_monthly_performance->monthly_performance_level = 'Exceeds Expectation';
                }
                if($monthly_rate[$i]>=4.2){
                    $rate_monthly_performance->monthly_performance_level = 'Outstanding';
                }
                // Set another data here
                $rate_monthly_performance->save();

                //save annual
                if($i == 6){
                     $this->saveAnnual($rate_monthly_performance->month_year, $id);
                }
            }
        }
        $year = date('Y-m');
        if(HomeController::isHR()) {
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
        return view('performance_management.rating_performance.rating_my_performance.RMMP',[ 'personal_info'=>$personal_info,'rate_monthly_performance'=>$rate_monthly_performance, 'year'=>$year,'department'=>$department,'department_list'=>$departmentList]);
    }
}
