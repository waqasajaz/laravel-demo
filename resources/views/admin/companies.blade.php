@extends('admin.master')

@section('content')
<!-- content   -->

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<i class="fa fa-industry"></i> Companies
	</h1>
</section>
<section  class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Import new company</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal" method="POST" action="{{url('admin/import/company')}}" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-lg-4">Company Name</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="company_name" name="company_name"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-lg-4">Company Email</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="company_email" name="company_email"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-lg-4">Company Phone</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="company_phone" name="company_phone"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-lg-4">Company Website</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="company_website" name="company_website"/>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-2">Company Address</label>
								<div class="col-md-10">
									<input type="text" class="form-control" name="company_address" id="company_address" />
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-lg-2">Company Logo</label>
								<div class="col-md-8">
									<input type="file" id="company_logo" name="company_logo"/>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-2">Property XML</label>
								<div class="col-md-10">
									<input type="file" name="property_excel" id="property_excel">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<label class="col-md-2"></label>
							<div class="col-md-10">
								<button class="bnt btn-success btn-sm">
									<i class="fa fa-upload"></i> Submit
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
		<div class="col-md-12">
			<div class="box">
				<div class="box-body" style="overflow-x: scroll;">
					<div class="table-responsive">
						<table id="company_list" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>#</th>
								<th>Company Name</th>
								<th>Company email</th>
								<th>Company contact</th>
								<th>Company Website</th>
								<th>Company address</th>
								<th>Total Property</th>
								<th>Agent</th>
								<th>Created</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody>
								<?php if($companies != false) { foreach($companies as $company) { ?>
								<tr>
									<td><?php echo $company->id; ?></td>
									<td><?php echo $company->company_name; ?></td>
									<td><?php echo $company->company_email; ?></td>
									<td><?php echo $company->company_phone; ?></td>
									<td><?php echo $company->company_website; ?></td>
									<td><?php echo $company->company_address; ?></td>
									<td><?php echo $company->total; ?></td>
									<td>
										<span class="label label-success company-label-<?php echo $company->id; ?>"><?php echo $company->agent; ?></span>
									</td>
									<td><?php echo $company->created_at; ?></td>
									<td>
										<a href="{{url('admin/company/properties/'.$company->id)}}" title="List of Property">
											<i class="fa fa-list"></i>
										</a>
										<?php if($logedin->role_id == 1) { ?>
											<select class="form-control" onchange="return AssignAgent(this.value,'<?php echo $company->id; ?>')" id="agents-<?php echo $company->id; ?>">
												<option value="">Assign an agent</option>
												<?php if($agents) { foreach($agents as $agent) { ?>
												<option value="<?php echo $agent->id ?>" <?php echo ($company->agent_id == $agent->id)?"selected":""; ?>>
													<?php echo $agent->name; ?>
												</option>
												<?php } } ?>
											</select>
										<?php } ?>
									</td>
								</tr>
								<?php } } ?>
							</tbody>
						</table>
						<div class="row">
							<div class="col-md-12">
								{{ $companies->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@stop
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#company_list").dataTable({
				"order": [[ 0, "desc" ]]
			});
		});

		function AssignAgent(agent_id, company){
			$.ajax({
				url: "{{ url('/agent/assign/company') }}",
				type: 'post',
				async: false,
				data: {
					"_token": '{{ csrf_token() }}',
					"agent": agent_id,
					"company": company
				},
				success: function(response) {
					response = jQuery.parseJSON(response);
					if(response)
					{
						$(".company-label-"+company).text($("#agents-"+company+" option:selected").text());
					}
				}
			});
		}

	</script>
@stop