@extends('admin.master')

@section('content')
	<!-- content   -->

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Assets
		</h1>
	</section>
	<?php if($varifiedadmin) { ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body" style="overflow-x: scroll;">
						<table id="activity_log" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>#</th>
								<th>Page URL</th>
								<th>Log Type</th>
								<th>Log Message</th>
								<th>User</th>
								<th>Browser</th>
								<th>IP Address</th>
								<th>Start</th>
								<th>End</th>
								<th>Duration</th>
							</tr>
							</thead>
							<tbody>

							<?php if($logs != false) { foreach($logs as $log) { ?>
							<tr>
								<td><?php echo $log['id']; ?></td>
								<td><?php echo $log['page_url']; ?></td>
								<td><?php echo $log['log_type']; ?></td>
								<td><?php echo $log['log_message']; ?></td>
								<td><?php echo $log['userid']; ?></td>
								<td><?php echo $log['browser']; ?></td>
								<td><?php echo $log['ip_address']; ?></td>
								<td><?php echo $log['arrive_time']; ?></td>
								<td><?php echo $log['leave_time']; ?></td>
								<td><?php echo $log['interval']; ?></td>
							</tr>
							<?php } } ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php } else{ ?>

	<div id="authentication-block">
		<?php if($varifiedadmin === false){ ?> Invalid Authentication password! <?php } ?>
		<form class="form-inline" action="{{url('/logs/authentication')}}" method="post">
			{{csrf_field()}}
			<h4>Authentication</h4>
			<p>You must login to view content of this page.</p>
			<div class="form-group">
				<label class="control-label" for="password">
					Password :
				</label>
				<input type="password" name="authentication" id="authentication" placeholder="Password" class="form-control"/>
			</div>
			<div class="form-group">
				<button class="btn btn-sm btn-success" type="submit"><i class="fa fa-arrow-right"></i> Continue </button>
			</div>
		</form>
	</div>
	<?php } ?>

@stop
@section('script')
	<?php if($varifiedadmin) { ?>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
	<script type="text/javascript">
		$('#activity_log').DataTable({
			"aaSorting": [],
			dom: 'Bfrtip',
			buttons: [
				{
					text: 'Export Logs',
					action: function ( e, dt, node, config ) {
						e.preventDefault();
						$(location).attr("href", basepath+"/logs/export/xls");
					}
				},
				{
					text:"Exprt as CSV",
					action:function( e, dt, node, config ){
						e.preventDefault();
						$(location).attr("href", basepath+"/logs/export/csv");
					}
				}
			]
		});
	</script>
	<?php } ?>
@stop