@extends('admin.master')
@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-image"></i> Image Gallery - <?php echo $property->direccion; ?>
        </h1>
    </section>
    <section  class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-yellow">
                        <div class="widget-user-image">
                            <img class="img-circle" src="<?php echo $s3->url("Properties/".$property->id."/thumbs/".optional($property->image)->filename); ?>" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username"><?php echo $property->direccion; ?>
                        <h5 class="widget-user-desc"><?php echo $property->hood->hood.", ".$property->cops; ?></h5>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">Contact Name <span class="pull-right badge bg-blue"><?php echo optional($property->property_contact)->contact_name; ?></span></a></li>
                            <li><a href="#">Contact Email <span class="pull-right badge bg-aqua"><?php echo optional($property->property_contact)->contact_phone; ?></span></a></li>
                            <li><a href="#">Contact Phone <span class="pull-right badge bg-green"><?php echo optional($property->property_contact)->contact_email; ?></span></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>

            <div class="col-md-8">
                <div class="row">
                    <?php $hasImges = optional($property->images); ?>
                    <?php if($hasImges) foreach($property->images as $image) { ?>
                    <div class="col-md-4" id="property_image_<?php echo $image->id; ?>">
                        <div class="property_image" data-image="<?php echo $s3->url("Properties/".$property->id."/".optional($property->image)->filename); ?>" data-image-id="<?php echo $image->id; ?>">
                            <img src="<?php echo $s3->url("Properties/".$property->id."/thumbs/".$image->filename); ?>" class="img-thumbnail" >
                                <ul class="image_options">
                                    <li class="edit_image"><i class="fa fa-edit success"></i></li>
                                    <li class="delete_image"><i class="fa fa-trash danger"></i></li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <div id="edit_property_image_form_block" class="hidden">
        <form class="form" name="edit_property_image_form" method="post" id="edit_property_image_form" action="{{url('/property/edit_image')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="image_id" name="image_id" value="">
            <input type="hidden" name="property" value="<?php echo $property->id; ?>">
            <img src="" id="editable_image" class="img-thumbnail" style="margin: 10px auto;"/>
            <label class="custom-file">
                <input type="file" id="property_image" class="custom-file-input" name="property_image">
                <label class="error error-property_image" for="property_image"></label>
                <span class="custom-file-control"></span>
            </label>
        </form>
    </div>
@stop
@section('script')

    <script type="text/javascript">
        var edit_image = {
            "src" : "",
            "id" : "",
            "filename" : ""
        };

        $(document).ready(function(){
            $(".property_image .edit_image").click(function(){
                $editable_image = $(this).parent().parent(".property_image");
                edit_image = {
                    "src" : $editable_image.data("image"),
                    "id" : $editable_image.data("image-id"),
                };
                image_edit();
            });

            $(".delete_image").click(function(event){
                $editable_image = $(this).parent().parent(".property_image");
                edit_image = {
                    "src" : $editable_image.data("image"),
                    "id" : $editable_image.data("image-id"),
                };
                delete_image();
            });

        });

        function image_edit()
        {
            $.confirm({
                title:"Edit Image - <?php echo $property->direccion; ?>",
                content:function(){
                    $('#editable_image').attr("src", edit_image.src);
                    $('#image_id').val(edit_image.id);
                    return $("#edit_property_image_form_block").html();
                },
                columnClass: 'col-md-8 col-md-offset-2',
                buttons:{
                    "upload":{
                        btnText:"Upload",
                        btnClass:"btn btn-primary",
                        action:function(){
                            var inputfile = this.$content.find('#property_image');
                            if(inputfile.val() == "")
                            {
                                $.alert({
                                    "title":"ERROR!",
                                    "content":"You did not select any image",
                                    type: 'red',
                                    "buttons":{
                                        ok:function(){ image_edit(); }
                                    }
                                });
                            }
                            else {
                                var jc = this;
                                this.$content.find('form').submit();
                            }
                        }
                    },
                    "cancel":{
                        btnText:"Cancel",
                        btnClass:"btn btn-danger"
                    }
                }
            });
        }

        function delete_image()
        {
            $.confirm({
                title:"Are you sure you want to delete this image?",
                content:function(){
                    return '<img src="'+edit_image.src+'"  class="img-thumbnail" style="margin: 10px auto;"/>';
                },
                type:"red",
                columnClass:'col-md-8 col-md-offset-2',
                buttons:{
                    "delete":{
                        btnText:"Delete",
                        btnClass:"btn btn-danger",
                        action:function () {
                            $.ajax({
                                url:basepath+"/property/deleteimage",
                                type:"POST",
                                data:{
                                    _token:$("input[name='_token']").val(),
                                    image:edit_image.id
                                },
                                success:function(res){
                                    res = jQuery.parseJSON(res);
                                    if(!res)
                                    {
                                        $.alert({
                                            title:"ERROR!",
                                            type: 'red',
                                            content:"There was system problem While deleting image!"
                                        });
                                    }else {
                                        location.reload();
                                    }
                                }
                            });
                        }
                    },
                    'cancel':{
                        btnText:"Cancel",
                        action:function(){}
                    }
                }
            });
        }
    </script>
@stop