<form method="POST" action="{{url('update-course')}}" enctype="multipart/form-data">
	@csrf
	
	<input type="hidden" name="course_id" value="{{$crs->id}}">

	<div class="form-group">
		<label>Course</label>
		<input class="form-control mb-3" type="text" placeholder="Course Name" name="course_name_edit" value="{{$crs->course_name}}" required>
	</div>
	
	<div class="form-group">
		<label>Course Category</label>
		<select class="form-control mb-3" placeholder="Select Type" name="course_category_edit" required>
			<option value="">Select Type</option>
			@foreach($cat as $r)
			<option value="{{$r->id}}"  @if($r->id==$crs->course_category_id) selected @endif >{{$r->category}}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group">
			<label>Description</label>
			<textarea class="form-control" rows="5" placeholder="Description" name="description_edit">{{$crs->description}}</textarea>
	</div>
	
	</div>
	<div class="form-group mt-3 mb-5">
		<div class="row">
			<div class="col-lg-12 col-xl-12 col-xxl-12 text-right">
			<button type="submit" class="btn btn-primary">Update changes</button>
		</div>
	</div>
	
	</form>