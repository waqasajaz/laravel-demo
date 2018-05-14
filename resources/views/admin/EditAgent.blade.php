@extends('admin.master')

@section('content')

<!-- Content Wrapper. Contains page content -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Agent
    </h1>
</section>
<?php
    // echo "<pre>"; print_r($data); exit;
?>
<!-- Main content -->
<section class="content">
    <div class="row">

        <!-- right column -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo (isset($data) && !empty($data)) ? ' Edit ' : 'Add' ?> Agent</h3>
                </div><!-- /.box-header -->
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>{{trans('labels.whoops')}}</strong> {{trans('labels.someproblems')}}<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form id="addAgent" class="form-horizontal" method="post" action="{{ url('/admin/agent/create') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="role_id" value="2">
                    <input type="hidden" name="id" value="<?php echo (isset($data) && !empty($data)) ? $data->id : '0' ?>">
                    <div class="box-body">

                        <div class="form-group">
                            <?php
                            if (old('name'))
                                $name = old('name');
                            elseif (isset($data))
                                $name = $data->name;
                            else
                                $name = '';
                            ?>
                            <label for="name" class="col-sm-2 control-label">Name<span class="star_red">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="name" name="name" value="{{$name}}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?php
                            if (old('email'))
                                $email = old('email');
                            elseif (isset($data))
                                $email = $data->email;
                            else
                                $email = '';
                            ?>
                            <label for="email" class="col-sm-2 control-label">Email<span class="star_red">*</span></label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="email" name="email" value="{{$email}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="password" name="password" minlength="6" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" value="">
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn bg-purple save-btn">Save</button>
                            <a class="btn btn-default" href="{{ url('admin/agent') }}">Cancel</a>
                        </div>
                    </div><!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
</section><!-- /.content -->
@stop
@section('script')
<script type="text/javascript">

$(document).ready(function () {
<?php if(isset($data->id) && $data->id > 0) { ?>
    var addAgentRules = {
        name: {
            required: true,
            letterswithbasicpunc: true
        },
        email: {
            required: true,
            email: true,
            minlength: 5,
            maxlength: 50
        }
    };
<?php } else { ?>
    var addAgentRules = {
        name: {
            required: true,
            letterswithbasicpunc: true
        },
        email: {
            required: true,
            email: true,
            minlength: 5,
            maxlength: 50
        },
        password: {
            required: true,
            minlength: 6,
            maxlength: 20,
        },
        confirm_password: {
            required: true,
            minlength: 6,
            maxlength: 20,
            equalTo : '#password'
        }
    };
<?php } ?>
     
    $("#addAgent").validate({
        ignore: "",
        rules: addAgentRules,
        messages: {
            name: {
                required: "<?php echo trans('labels.namerequired')?>",
                letterswithbasicpunc: "<?php echo trans('labels.letterswithbasicpunc') ?> "
            },
            email: {
                required: "<?php echo trans('labels.emailrequired') ?>",
                minlength: "<?php echo trans('labels.emailmin', ['length' => 5]) ?>",
                maxlength: "<?php echo trans('labels.emailmax', ['length' => 50]) ?>",
                email: "<?php echo trans('labels.invalidemailformat') ?>"
            },
            password: {
                required : '<?php echo trans('labels.passwordrequired')?>',
                minlength: "<?php echo trans('labels.passwordmin', ['length' => 6]) ?>",
                maxlength: "<?php echo trans('labels.passwordmax', ['length' => 20]) ?>"
            },
            confirm_password: {
                required : '<?php echo trans('labels.confirmpasswordrequired')?>',
                min: "<?php echo trans('labels.passwordmin', ['length' => 6]) ?>",
                maxlength: "<?php echo trans('labels.passwordmax', ['length' => 20]) ?>",
                equalTo : '<?php echo trans('labels.retypepassword')?>'
            }
        }
    });
});
</script>
@stop