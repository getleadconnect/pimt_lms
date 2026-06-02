<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Center;
use App\Models\CourseCategory;
use App\Models\QbankSubject;
use App\Models\QbankQuestion;
use App\Imports\QbankQuestionImport;


use Validator;
use DataTables;
use Session;
use Auth;
use Log;

class QbankQuestionController extends Controller
{
  public function __construct()
  {
     $this->middleware('admin');
  }
  
  public function index()
  {
	$qbsub=QbankSubject::where('status',1)->get();
   return view('admin.question_bank.questions',compact('qbsub'));
  }	
 
 public function add_qbank_question()
  {
	$qbsub=QbankSubject::where('status',1)->get();
   return view('admin.question_bank.add_qbank_question',compact('qbsub'));
  }	
 
  public function store(Request $request)
  {

		if($request->question_type==2)
			$validate = Validator::make(request()->all(),[
				'subject_id'=>'required',
				'quest_option'=>'required',
			]);
		else
	
		  $validate = Validator::make(request()->all(),[
             'subject_id'=>'required',
			 'quest_option'=>'required',
			 'answer1'=>'required',
			 'answer2'=>'required',
			 'answer3'=>'required',
			 'answer4'=>'required',
			 'correct_answer'=>'required'
        ]);


	    if ($validate->fails())
        {
            Session::flash('message', 'danger#Some details are missing, try again.');
			return back()->withErrors($validate)->withInput();
        }
	
		try
		{
			$fname1="";
			if($request->file('image_question'))
			{ 
				$fname1=Storage::disk('spaces')->putFile("image_questions",$request->file('image_question'), 'public');
				$fname1=str_replace("image_questions/","",$fname1);
			}
			else
			{
				$fname1=$request->question;
			}
			
			$qtype=1;
			if($request->question_type!='')
			{
				$qtype=$request->question_type;
			}

			$result=QbankQuestion::create([
			 'qbank_subject_id'=>$request->subject_id,
			 'question_type'=>$qtype,
			 'question'=>$fname1,
			 'answer1'=>$request->answer1,
			 'answer2'=>$request->answer2,
			 'answer3'=>$request->answer3,
			 'answer4'=>$request->answer4,
			 'correct_answer'=>$request->correct_answer,
			]);
			
			if($result)
			{
				Session::flash('message', 'success#Question successfully added.');
			}
			else
			{
				Session::flash('message', 'danger#Some details are missing, Please check.');
			}
		}
		catch(\Exception $e)
		{
			\Log::info($e->getMessage());
			Session::flash('message', 'danger#Something wrong, Try again.');
		}
		
		return redirect('add-qbank-question');
		
  }


public function view_data(Request $request)
	{

		if ($request->ajax()) {
            //$data = $this->getQuestions($request);

		$search=$request->search;
		$subject_id=$request->searchSubjectId;

		$qtype_id=$request->questionTypeId;
				
		$cid=Auth::guard('admin')->user()->center_id;
		
		$dts=QbankQuestion::select('qbank_questions.*','qbank_subjects.subject_name')
		->leftJoin('qbank_subjects','qbank_questions.qbank_subject_id','=','qbank_subjects.id')
		->where(function($where) use($search)
			    {
					$where->where("qbank_questions.question", 'like', '%' .$search . '%')
					->orWhere("qbank_questions.answer1", 'like', '%' .$search . '%')
					->orWhere("qbank_questions.answer2", 'like', '%' .$search . '%')
					->orWhere("qbank_questions.answer3", 'like', '%' .$search . '%')
					->orWhere("qbank_questions.answer4", 'like', '%' .$search . '%')
					->orWhere("qbank_questions.correct_answer", 'like', '%' .$search . '%')
					->orWhere("qbank_subjects.subject_name", 'like', '%' .$search . '%');
				});
		
		if($subject_id!="")
		{
			$dts->where('qbank_questions.qbank_subject_id',$subject_id);
		}	
		if($qtype_id!="")
		{
			$dts->where('qbank_questions.question_type',$qtype_id);
		}			
					  
		$dats=$dts->orderBy('qbank_questions.id','ASC')->get();

			return DataTables::of($dats)
                    ->addIndexColumn()
					->addColumn('quest', function($row)
					{
						if($row->question_type==1)	
							{
								$qt='<a href="#" class="view-image" data-bs-toggle="modal" data-bs-target="#BasicModal3" data-image="'.config('constants.image_question').$row->question.'" ><img src="'.config('constants.image_question').$row->question.'" style="width:100px;height:40px;"></a>';
								return $qt;
							}
						return $row->question;
                    })
					->addColumn('type', function($row)
					{
						$qtype="Objective";
						if($row->question_type==1)
						{
							$qtype='<span style="color:purple;">Image</span>';
						}
						elseif($row->question_type==2)
						{
							$qtype='<span style="color:#3434fa;">Descriptive</span>';
						}
						return $qtype;
                    })
					->addColumn('subj', function($row)
					{
						return $row->subject_name;
                    })
					->addColumn('ans1', function($row)
					{
						return $row->answer1;
                    })
					->addColumn('ans2', function($row)
					{
						return $row->answer2;
                    })
					->addColumn('ans3', function($row)
					{
						return $row->answer3;
                    })
					->addColumn('ans4', function($row)
					{
						return $row->answer4;
                    })

					->addColumn('cans', function($row)
					{
						return $row->correct_answer;
                    })

					->addColumn('action', function($row)
					{

					$dr_btn='<div class="dropdown action-dd">
                          <button type="button" class="btn btn-outline-secondary btn-action-circle dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                              <i class="bx bx-dots-vertical" style="margin-left:0px;"></i>
                          </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item edit" href="'.route('edit-qbank-question',$row->id).'" >Edit</a></li>
							  <li><a class="dropdown-item btnDel" href="javascript:void(0)" id="'.$row->id.'" >Delete</a></li>
                            </ul>
                        </div>';
					return $dr_btn;
					})
				
                    ->rawColumns(['action','status','quest','type'])
                    ->make(true);
        }
	}
	

  public function edit($id)
  {
    $qs=QbankQuestion::where('id',$id)->first();
	$qbsub=QbankSubject::where('status',1)->get();
	return view('admin.question_bank.edit_qbank_question',compact('qs','qbsub'));
  }	
		
	public function update_qbank_question(Request $request)
	{

	   $validate = Validator::make(request()->all(),[
             'subject_id'=>'required',
			 'answer1'=>'required',
			 'answer2'=>'required',
			 'answer3'=>'required',
			 'answer4'=>'required',
			 'currect_answer'=>'required',
        ]);

		 
	    if ($validate->fails())
        {
            Session::flash('message', 'danger#Some details are missing, try again.');
			return back()->withErrors($validate)->withInput();
        }
	
	try
		{
						
			$id=$request->quest_id;
			$fname1=$request->quest_image;
			
			if($request->file('image_question'))
			{ 
				Storage::disk('spaces')->delete("image_questions"."/".$fname1);
				$fname1=Storage::disk('spaces')->putFile("image_questions",$request->file('image_question'), 'public');
				$fname1=str_replace("image_questions/","",$fname1);
			}
			
			if($request->quest_type==0)
			{
				$fname1=$request->question;
			}
			
			$new_dat=[
			 'qbank_subject_id'=>$request->subject_id,
			 'question'=>$fname1,
			 'answer1'=>$request->answer1,
			 'answer2'=>$request->answer2,
			 'answer3'=>$request->answer3,
			 'answer4'=>$request->answer4,
			 'correct_answer'=>$request->currect_answer
			];
			
			$result=QbankQuestion::where('id',$id)->update($new_dat);
						 
			if($result)
			{
				Session::flash('message', 'success#Question successfully added.');
			}
			else
			{
				Session::flash('message', 'danger#Some details are missing, Please check.');
			}
		}
		catch(\Exception $e)
		{
			\Log::info($e->getMessage());
			Session::flash('message', 'danger#Something wrong, Try again.');
		}
		
		return redirect('questions');

  }

	
   public function destroy($id)
	{
		$dat=QbankQuestion::findorfail($id);
		
			if(!empty($dat))
			{
				$dat->delete();
				return response()->json(['msg' =>'Question successfuly removed.!' , 'status' => true]);
			}
			else
			{
				return response()->json(['msg' =>'Something wrong, try again!' , 'status' => false]);
			}				
	}
	

//----------------------------------------------------------------------------------------------------------------


public function import_qbank_questions()
{
	$qbsub=QbankSubject::where('status',1)->get();
	return view('admin.question_bank.import_qbank_questions',compact('qbsub'));
}	

public function import(Request $request) 
   {
		$validate = Validator::make(request()->all(),[
           'subject_id'=>'required',
		   'question_file' => 'required|mimes:xlsx,xls',
        ]);
		 
	    if ($validate->fails())
        {
            Session::flash('message', 'danger#Some details are missing, try again.');
			return back();
		}
		
		try
		{
		   $sub_id=$request->subject_id;

		   $success=Excel::import(new QbankQuestionImport($sub_id),request()->file('question_file'));
		   if($success)
		   {
		       Session::flash('message', 'success#Question successfully added.');
		   }
		   else
		   {
			   Session::flash('message', 'danger#Something wrong, Try again.');
		   }
		}
		catch(\Exception $e)
		{
			\Log::info($e->getMessage());
			Session::flash('message', 'danger#Something wrong, Try again.');
		}
	   
	   return back();
    }


}
