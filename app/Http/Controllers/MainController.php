<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ReportTest;
use App\Models\ReportTestAnswer;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $data = [];
        return view('form_personal',$data);
    }

    public function savePersonal(Request $request) {
        $this->validate($request,[
            'nik'=>'required',
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required'
        ]);

        $already = ReportTest::query()->where('nik',$request->nik)->first();
        if($already) return redirect()->back()->with(['message'=>'Anda sudah pernah melakukan test ini','type'=>'warning']);

        $reportTest = new ReportTest();
        $reportTest->name = $request->name;
        $reportTest->nik = $request->nik;
        $reportTest->email = $request->email;
        $reportTest->phone = $request->phone;
        $reportTest->position = $request->position;
        $reportTest->time_start = date('Y-m-d H:i:s');
        $reportTest->save();

        session(['report_test_id'=>$reportTest->id]);

        return redirect('eye-test');
    }

    public function eyeTest()
    {
        if(!session()->has('report_test_id')) {
            return redirect('/');
        }
        return view('eye_test');
    }

    public function eyeTestCompleted()
    {
        $reportTest = ReportTest::findOrFail(session('report_test_id'));
        $reportTest->eye_test_completed = 1;
        $reportTest->save();

        return response()->json(['status'=>true]);
    }

    public function formTest()
    {
        $reportTest = ReportTest::findOrFail(session('report_test_id'));
        if($reportTest->eye_test_completed) {
            $data = [];
            $data['questions'] = Question::all();
            $data['time_start'] = $reportTest->time_start;
            $data['report_test'] = $reportTest;
            return view('form_test',$data);
        } else {
            return redirect("eye-test");
        }
    }

    public function finishTest(Request $request)
    {
        $this->validate($request,['id'=>'required|exists:report_tests']);

        if($request->answer) {
            $data = ReportTest::findOrFail($request->id);
            $data->time_end = date('Y-m-d H:i:s');
            $data->save();

            foreach($request->answer as $i=>$answer) {
                if($answer && @$request->questions_id[$i]) {
                    $dataAnswer = new ReportTestAnswer();
                    $dataAnswer->report_test_id = $data->id;
                    @$dataAnswer->question_id = $request->questions_id[$i];
                    @$dataAnswer->question = $request->questions_question[$i];
                    $dataAnswer->answer = $answer;
                    $dataAnswer->save();
                }
            }
            return redirect('/')->with(['message'=>'<strong>Terima kasih</strong> telah mengikuti test online ini. Kami akan segera menginformasikan kepada Anda hasil test ini.','type'=>'success']);
        } else {
            return redirect('/')->with(['message'=>'Anda didiskualifikasi!','type'=>'warning']);
        }
    }

    public function abort()
    {
        $data = ReportTest::query()->where("id",session('report_test_id'))->firstOrFail();
        $update = ReportTest::find($data->id);
        $update->remark = "Fail Cheated";
        $update->save();

        return response()->json(['status'=>true]);
    }
}
