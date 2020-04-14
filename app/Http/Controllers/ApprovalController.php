<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\course;
use App\Model\msc_performance;
use App\Model\personal_info;
use App\Model\rate_annual_performance;
use App\Model\rate_monthly_performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public const STATUS_PENDING = 1;
    public const STATUS_SUBMITED = 2;
    public const STATUS_APPROVED = 3;
    public const STATUS_REJECTED = 4;
    public const STATUS_COMPLETED = 5;
    public const STATUS_REVIEW = 6;

    public function submitMscMothy($id) {
        $msc_performance = msc_performance::where('user_id',$id)->where('type', 1)->where('status', $this::STATUS_PENDING)->get();
        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_SUBMITED;
            $msc->save();
        }

        return redirect()->route('BMMMO', ['id' => $id]);
    }

    public function submitMscAnnual($id) {
        $msc_performance = msc_performance::where('user_id',$id)->where('type', 0)->where('status', $this::STATUS_PENDING)->get();
        foreach ($msc_performance as $msc) {

            $msc->status = $this::STATUS_SUBMITED;
            $msc->save();
        }
        return redirect()->route('BMAMO', ['id' => $id]);
    }

    public function submitRateAnnual($id){
        $course = course::all();
        $rate_annual_performance = rate_annual_performance::where('user_id',$id)->where('status', $this::STATUS_PENDING)->get();
        foreach ($rate_annual_performance as $rate) {
            $rate->status = $this::STATUS_SUBMITED;
            $rate->save();
        }
        $rate_annual_performance = rate_annual_performance::where('user_id',$id)->get();
        $personal_info = personal_info::where('user_id',$id)->first();
        return redirect()->route('RMAP', ['id' => $id]);
    }

    public function submitRateMonthy($id, Request $request) {
        $rate_monthly_performance = rate_monthly_performance::where('user_id',$id)->where('status', $this::STATUS_PENDING)->get();
        foreach ($rate_monthly_performance as $rate) {
            $rate->status = $this::STATUS_SUBMITED;
            $rate->save();
        }
        return redirect()->route('RMMP', ['id' => $id]);
    }

    public function approveMyEmployeeMscAnnual($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $msc_performance = msc_performance::whereIn('user_id', $userIds)->where('type', 0)->where('status', $this::STATUS_SUBMITED)->get();
        $comment = $request->input('comment');
        if ( !$comment ) {
            $comment = '';
        }

        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_APPROVED;
            $msc->note = $comment;
            $msc->save();
        }

        return redirect()->route('AMEAMO', ['id' => $id]);
    }

    public function approveMyEmployeeMscMonthly($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $msc_performance = msc_performance::whereIn('user_id', $userIds)->where('type', 1)->where('status', $this::STATUS_SUBMITED)->get();
        $comment = $request->input('comment');
        if ( !$comment ) {
            $comment = '';
        }

        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_APPROVED;
            $msc->note = $comment;
            $msc->save();
        }

        return redirect()->route('AMEMMO', ['id' => $id]);
    }

    public function rejectMyEmployeeMscAnnual($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $msc_performance = msc_performance::whereIn('user_id', $userIds)->where('type', 0)->where('status', $this::STATUS_SUBMITED)->get();
        $comment = $request->input('comment');
        if ( !$comment ) {
            $comment = '';
        }

        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_REJECTED;
            $msc->note = $comment;
            $msc->save();
        }

        return redirect()->route('AMEAMO', ['id' => $id]);
    }

    public function rejectMyEmployeeMscMonthly($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $msc_performance = msc_performance::whereIn('user_id', $userIds)->where('type', 1)->where('status', $this::STATUS_SUBMITED)->get();
        $comment = $request->input('comment');
        if ( !$comment ) {
            $comment = '';
        }

        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_REJECTED;
            $msc->note = $comment;
            $msc->save();
        }

        return redirect()->route('AMEMMO', ['id' => $id]);
    }

    public function approveMyEmployeeRateAnnual($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        $comment = $request->input('comment');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }

        $rate_annual_performance = rate_annual_performance::whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();

        foreach ($rate_annual_performance as $rate) {
            $rate->note = $comment;
            $rate->status = $this::STATUS_APPROVED;
            $rate->save();
        }

        return redirect()->route('AMEAP', ['id' => $id]);
    }

    public function approveMyEmployeeRateMonthly($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        $comment = $request->input('comment');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_monthly_performance = rate_monthly_performance::whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();

        foreach ($rate_monthly_performance as $rate) {
            $rate->note = $comment;
            $rate->status = $this::STATUS_APPROVED;
            $rate->save();
        }

        return redirect()->route('AMEMP', ['id' => $id]);
    }

    public function rejectMyEmployeeRateAnnual($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        $comment = $request->input('comment');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_annual_performance = rate_annual_performance::whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();

        foreach ($rate_annual_performance as $rate) {
            $rate->note = $comment;
            $rate->status = $this::STATUS_REJECTED;
            $rate->save();
        }

        return redirect()->route('AMEAP', ['id' => $id]);
    }

    public function rejectMyEmployeeRateMonthly($id, Request $request) {
        $users= personal_info::where('department_id', $id)->get();
        $userIds = array();
        $comment = $request->input('comment');
        foreach ($users as $user) {
            $userIds[] = $user->user_id;
        }
        $rate_monthly_performance = rate_monthly_performance::whereIn('user_id', $userIds)
            ->where('status', $this::STATUS_SUBMITED)
            ->get();

        foreach ($rate_monthly_performance as $rate) {
            $rate->note = $comment;
            $rate->status = $this::STATUS_REJECTED;
            $rate->save();
        }

        return redirect()->route('AMEMP', ['id' => $id]);
    }

    public function resetAllStatus() {
        $rate_monthly_performance = rate_monthly_performance::all();

        foreach ($rate_monthly_performance as $rate) {
            $rate->status = $this::STATUS_PENDING;
            $rate->save();
        }

        $rate_annual_performance = rate_annual_performance::all();

        foreach ($rate_annual_performance as $rate) {
            $rate->status = $this::STATUS_PENDING;
            $rate->save();
        }

        $msc_performance = msc_performance::all();

        foreach ($msc_performance as $msc) {
            $msc->status = $this::STATUS_PENDING;
            $msc->save();
        }

        return redirect('/');
    }
    public function reviewRMAP($id, Request $request) {
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

    public function submitFirstRMMP($id, Request $request) {
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
        $rate_monthly_performance = rate_monthly_performance::where('user_id',$id)->where('status', $this::STATUS_PENDING)->get();
        foreach ($rate_monthly_performance as $rate) {
            $rate->status = $this::STATUS_SUBMITED;
            $rate->save();
        }
        return redirect()->route('RMMP', ['id' => $id]);
    }
}
