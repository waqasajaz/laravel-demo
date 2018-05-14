@extends('admin.master')

@section('content')
    <!-- content   -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> Settings
        </h1>
    </section>
    <section  class="content">
        <?php  ?>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4>Reset Your password For Admin</h4>
                    </div>
                    <form id="reset_password_form1" action="{{url('admin/resetpassword')}}" method="post">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="password">Current Password</label>
                                <input type="password" name="password" id="password" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="re_password">Confirm new password</label>
                                <input type="password" name="re_password" id="re_password" class="form-control" />
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" value="submit" class="btn btn-success btn-sm pull-right"/>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h4>Reset Your password For Logs access</h4>
                    </div>
                    <form id="reset_password_form1" action="{{url('admin/resetlogpassword')}}" method="post">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="password">Current Password</label>
                                <input type="password" name="password" id="password" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="re_password">Confirm new password</label>
                                <input type="password" name="re_password" id="re_password" class="form-control" />
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" value="submit" class="btn btn-warning btn-sm pull-right"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')

    <script type="text/javascript">
        $(document).ready(function(){
            $("#reset_password_form1").validate({
                rules:{
                    password:"required",
                    new_password:{
                        required:true,
                        minlength:6,
                        maxlength:16
                    },
                    re_password:{
                        required:true,
                        equalTo:"#new_password"
                    }
                },
                messages:{
                    password:"Current password is require",
                    new_password:{
                        required:"You can not set blank password",
                        minlength:"password should be atleast 6 charecter long",
                        maxlength:"Password should not longer than 16 charecter long"
                    },
                    re_password:{
                        required:"Please reinsert new password that you have entered above",
                        equalTo:"Confirm password must match with new password"
                    }
                }
            });
        });
    </script>
@stop