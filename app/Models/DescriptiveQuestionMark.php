<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptiveQuestionMark extends Model
{
    use HasFactory;
		
	protected $table='descriptive_question_marks';
	
	protected $fillable = ['id','student_id','question_paper_id','question_id',
							'question_answer','mark'];
	 
    protected $hidden = [
		'created_at',
		'updated_at',
    ];
	
	protected $primaryKey='id';	
		
}
