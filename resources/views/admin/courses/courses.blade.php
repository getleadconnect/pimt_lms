@extends('admin.layouts.master')
@section('title','Dashboard')
@section('contents')

<style>
.justify
{
	text-align:justify !important;
}

/* Description (col 17) & Details (col 18) — fixed 400px, wrap content */
#datatable td.col-wide-400,
#datatable th.col-wide-400,
#datatable td:nth-child(17),
#datatable td:nth-child(18),
#datatable th:nth-child(17),
#datatable th:nth-child(18) {
	width: 400px !important;
	min-width: 400px;
	max-width: 400px;
	white-space: normal !important;
	word-wrap: break-word;
	overflow-wrap: break-word;
	vertical-align: top;
}
</style>
<!-- for message -------------->
		<input type="hidden" id="view_message" value="{{ Session::get('message') }}">
	<!-- for message end-------------->	


<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
              <div class="breadcrumb-title pe-3">Course</div>
 
             <!-- <div class="ms-auto">
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Settings</button>
                  <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
                    <a class="dropdown-item" href="javascript:;">Another action</a>
                    <a class="dropdown-item" href="javascript:;">Something else here</a>
                    <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
                  </div>
                </div>
              </div>  -->
            </div>
            <!--end breadcrumb-->

              <div class="card">
                <div class="card-header p-y-3">
				<div class="row">
				<div class="col-lg-9 col-xl-9 col-xxl-9 col-9">
                  <h6 class="mb-0 pt5">Course List</h6>
				  </div>
				  <div class="col-lg-3 col-xl-3 col-xxl-3 col-3 text-right">
				    <button class="btn btn-info btn-xs btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" style="padding-left:3px;" aria-expanded="true" aria-controls="flush-collapseOne">
                       <i class="lni lni-funnel"></i>
                  </button>&nbsp;
				  <a href="{{url('add-course')}}" class="btn btn-primary btn-xs btn-sm"><i class="fa fa-plus"></i>&nbsp;Add Course</a>&nbsp;
				  <button type="button" class="btn btn-success btn-xs btn-sm" data-bs-toggle="modal" data-bs-target="#importCoursesModal" title="Bulk import courses from Excel">
				      <i class="fa fa-file-excel"></i>&nbsp;Import Excel
				  </button>
                  <!--<button type="button" class="btn btn-primary btn-xs btn-sm" data-bs-toggle="modal" data-bs-target="#BasicModal1"><i class="fa fa-plus"></i>&nbsp;Add Course</button>-->
				  </div>
				  				  
				  </div>
                </div>
                <div class="card-body">
				
				<div class="accordion-item accordion-item-bm" >
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" >
                          <div class="accordion-body">
						   <div class="row" style="padding:3px 10px 0px 10px;" >
							<div class="col-3 col-lg-3">
								<label>Center</label>
								<select class="form-control mb-3" id="flt_center" placeholder="Center name" >
								<option value="">select</option>
								@foreach($center as $r)
								<option value="{{$r->id}}">{{$r->center_name}}</option>
								@endforeach
								</select>
							</div>
							
							<div class="col-3 col-lg-3">
								<label>Category</label>
								<select class="form-control mb-3" id="flt_course_category" placeholder="category" >
								<option value="">select</option>
								@foreach($ccat as $r)
								<option value="{{$r->id}}">{{$r->category}}</option>
								@endforeach
								</select>
							</div>
							<div class="col-3 col-lg-3">
								<label>Course Type</label>
								<select class="form-control mb-3" id="flt_course_type" placeholder="course type" >
								<option value="">select</option>
								@foreach($ctype as $r)
								<option value="{{$r->id}}">{{$r->course_type}}</option>
								@endforeach
								</select>
							</div>
						   </div>
						</div>
					  </div>
					</div>
				
				
                   <div class="row mt-3">
                     
                     <div class="col-12 col-lg-12 d-flex">
                      <div class="card  shadow-none w-100">
                        <!--<div class="card-body">-->
                          <div class="table-responsive">
                             <table id="datatable" class="table align-middle" style="width:100% !important;" >
                               <thead class="table-light">
                                 <tr>
				
									<th class="no-content">Action</th>
									<th>SlNo</th>
									<th>Id</th>
									<th>Center</th>
									<th>Premium</th>
									<th>Course_Name</th>
									<th>Category</th>
									<th>Course_Type</th>
									<th>Period</th>
									<th>Wide_icon</th>
									<th>Square_icon</th>
									<th>Rate</th>
									<th>IOS_Rate</th>
									<th>App_store_id</th>
									<th>Subscription_Type</th>
									<th>Video_file</th>
									<th>Description</th>
									<th>Details</th>
									<th>Status</th>
									<th>Added_By</th>
								</tr>
                               </thead>
                               <tbody>
                                  
			
                               </tbody>
                             </table>
							</div>

                       <!-- </div>-->
                      </div> 
                    </div>
                   </div><!--end row-->
                </div>
              </div>



@push('scripts')
<script>
//$('.textarea').summernote({dialogsInBody:true});

var mes=$('#view_message').val().split('#');

if(mes[0]=="success")
{	
	toastr.success(mes[1]);
}
else if(mes[0]=="danger")
{
	toastr.error(mes[1]);
}

//---------------------------------------------------------------------------

var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
		stateSave:true,
		paging     : true,
        pageLength :50,
		scrollX: true,
		
		'pagingType':"simple_numbers",
        'lengthChange': true,
		
		ajax:
		{
			url:"{{url('view-courses')}}",
			data: function (data) 
		    {
               data.search = $('input[type="search"]').val();
			   data.searchCenter = $('#flt_center').val();
			   data.searchCat = $('#flt_course_category').val();
			   data.searchCtype = $('#flt_course_type').val();
		    },
        },
		
		columnDefs:[
				  {"width":"40px","targets":0},
				  {"width":"400px","targets":[16,17], className:"col-wide-400"},
				],
	
        columns: [
			{"data": "action" ,name: 'Action',orderable: false, searchable: false },
            {"data": "slno" },
			{"data": "id" },
			{"data": "comp" },
			{"data": "pre" },
			{"data": "cname" },
			{"data": "cat" },
			{"data": "ctype" },
			{"data": "sdate" },
			{"data": "cwicon" },
			{"data": "csicon" },
			{"data": "rate" },
			{"data": "irate" },
			{"data": "appid" },
			{"data": "subtype" },
			{"data": "vfile" },
			{"data": "desc" ,name:'desc', orderable: true, searchable:true, className:"justify"},
			{"data": "cdetails" },
			{"data": "status" },
			{"data": "addedby" },
			
        ],

});

$("#flt_center").change(function()
{
	$('#datatable').DataTable().ajax.reload(null, false);
});

$("#flt_course_category").change(function()
{
	$('#datatable').DataTable().ajax.reload(null, false);
});

$("#flt_course_type").change(function()
{
	$('#datatable').DataTable().ajax.reload(null, false);
});


$("#datatable tbody").on('click','.btnDel',function()
{
	Swal.fire({
	  title: "Are you sure?",
	  text: "You want to delete this item?",
	  icon: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#3085d6",
	  cancelButtonColor: "#d33",
	  confirmButtonText: "Yes, delete it!"
	}).then((result) => {
	  if (result.isConfirmed) {
		
		var id=$(this).attr('id');
		  jQuery.ajax({
			type: "get",
			url: "{{url('delete-course')}}"+"/"+id,
			dataType: 'json',
			//data: {vid: vid},
			success: function(res)
			{
			   if(res.status==true)
			   {
				   toastr.success(res.msg);
				   $('#datatable').DataTable().ajax.reload(null, false);
			   }
			   else
			   {
				 toastr.error(res.msg); 
			   }
			}
			
		  });

	  }
	});

});


$("#datatable tbody").on('click','.btnAct',function()
{
	Swal.fire({
	  title: "Activate?",
	  text: "You want to activate this item?",
	  icon: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#3085d6",
	  cancelButtonColor: "#d33",
	  confirmButtonText: "Yes, activate it!"
	}).then((result) => {
	  if (result.isConfirmed) {
		var id=$(this).attr('id');
		  jQuery.ajax({
			type: "get",
			url: "{{url('act-deact-course/1')}}"+"/"+id,
			dataType: 'json',
			//data: {vid: vid},
			success: function(res)
			{
			   if(res.status==true)
			   {
				   toastr.success(res.msg);
				   $('#datatable').DataTable().ajax.reload(null, false);
			   }
			   else
			   {
				 toastr.error(res.msg); 
			   }
			}
		  });
	  }
	});

});


$("#datatable tbody").on('click','.btnDeact',function()
{
	Swal.fire({
	  title: "Deactivate?",
	  text: "You want to deactivate this item?",
	  icon: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#3085d6",
	  cancelButtonColor: "#d33",
	  confirmButtonText: "Yes, deactivate it!"
	}).then((result) => {
	  if (result.isConfirmed) {
		var id=$(this).attr('id');
		  jQuery.ajax({
			type: "get",
			url: "url('act-deact-course/2')}}"+"/"+id,
			dataType: 'json',
			//data: {vid: vid},
			success: function(res)
			{
			   if(res.status==true)
			   {
				   toastr.success(res.msg);
				   $('#datatable').DataTable().ajax.reload(null, false);
			   }
			   else
			   {
				 toastr.error(res.msg); 
			   }
			}
		  });
	  }
	});

});

</script>
@endpush

{{-- ===== Import Courses Modal ===== --}}
<div class="modal fade" id="importCoursesModal" tabindex="-1" aria-labelledby="importCoursesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('courses.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="importCoursesModalLabel">
            <i class="fa fa-file-excel text-success"></i>&nbsp; Import Courses from Excel
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="alert alert-info py-2 mb-3" style="font-size: 0.85rem;">
            <i class="fa fa-info-circle"></i>
            Image / file fields are not imported. Fill them in via the
            <strong>Edit Course</strong> screen after import.
          </div>

          <div class="mb-3">
            <a href="{{ route('courses.import.template') }}" class="btn btn-outline-primary btn-sm">
              <i class="fa fa-download"></i>&nbsp; Download Template
            </a>
            <small class="text-muted d-block mt-1">
              Download, fill the rows, then upload below. <code>course_name</code> is required.
            </small>
          </div>

          <div class="mb-2">
            <label for="import_file" class="form-label">Excel File <span class="text-danger">*</span></label>
            <input type="file" name="import_file" id="import_file"
                   class="form-control" accept=".xlsx,.xls,.csv" required>
            <small class="text-muted">Allowed: <code>.xlsx</code>, <code>.xls</code>, <code>.csv</code> · Max 5 MB</small>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">
            <i class="fa fa-upload"></i>&nbsp; Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
