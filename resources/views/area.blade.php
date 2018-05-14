@extends('layouts.app')
@section('title', 'Loquare | Area')
@section('content')
<main>
  
<div class="banner all-areas">
  <div class="container abslte">
    <div class="row">
       <h1>Learn More about Areas</h1>
       <h4>and choose the best place to live</h4>
    </div>
  </div>  
</div> 

<div class="main-body">
  <div class="container">
    <div class="row">
      
      <?php foreach($districts as $dist) { ?>
      <div class="col-md-6 district_block" >
        <a href="{{ url('/state/'.$dist['id'])  }}">
            <div class="img-gallry">
              <img src="{{ asset('/storage/district/'.$dist['image']) }}"/>
              
              <div class="overlay"><h3><?php echo $dist['dist_name']; ?></h3></div>          
            </div>
        </a>
      </div>
  <?php } ?>
     </div>
    
</div>

</div> 

 
<script type="text/javascript">
$(window).scroll(function() {
if ($(this).scrollTop() > 60){  
    $('.navbar-fixed-top').addClass("logo-fix-adon");
 }
  else{
    $('.navbar-fixed-top').removeClass("logo-fix-adon");				 
  } 
});
</script>
 
</main>
@endsection