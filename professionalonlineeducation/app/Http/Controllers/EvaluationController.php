<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EvaluationService as Service;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Auth;

class EvaluationController extends Controller
{

    public function list(){
        $service = new Service();
        $lists = $service->list();
        $evaluations = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.evaluations.list',compact('evaluations'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.evaluations.list',compact('evaluations'));
        }else{
            return abort('500');
        }
    }
    public function addEvaluation(){
        $pageData = array('type'=>'add','name'=>'Add Evaluation');
        $evaluation = new Evaluation;
        return  view('admin.evaluations.add',compact('evaluation','pageData'));
    }
    public function editEvaluation($id){
        $pageData = array('type'=>'edit','name'=>'Edit Evaluation');
        $service = new Service();
        $cat = $service->getEvaluation($id);
        if ($cat['type'] == 'success'){
            $evaluation = $cat['data'];
        }else{
            return abort('505');
        }

        return  view('admin.evaluations.add',compact('evaluation','pageData'));
    }
    public function save(Request $request){
        $service = new Service();
        $save = $service->save($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_evaluation'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_evaluation'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }


}
