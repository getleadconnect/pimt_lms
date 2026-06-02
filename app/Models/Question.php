<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
		
	protected $table='questions';
	
	protected $fillable = ['id','qbank_question_id','qbank_subject_id','question_paper_id','question_type',
							'question','answer1','answer2','answer3','answer4','correct_answer'];
	 
    protected $hidden = [
		'created_at',
		'updated_at',
    ];
	
	protected $primaryKey='id';

	// One descriptive-grading record per (question, student). The student
	// filter is applied at query time via `->where('student_id', $id)`.
	public function descriptiveMarks()
	{
		return $this->hasMany(\App\Models\DescriptiveQuestionMark::class, 'question_id', 'id');
	}
}
