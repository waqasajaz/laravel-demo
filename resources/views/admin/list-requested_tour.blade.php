@foreach($tours as $tour)
<tr>
	<td>{{$tour->property->direccion}}</td>
	<td>{{$tour->property->hood->hood}}</td>
	<td>{{$tour->property->district->dist_name}}</td>
	<td class="my-properties__equip" style="border: none;">
        <?php
        $features = array(
            "furnished" => array(
                "name" => "Furnished",
                "icon" => asset('/frontend/assets/icons/icon-25x25-furniture.svg')
            ),
            "heating" => array(
                "name" => "Heating",
                "icon" => asset('/frontend/assets/icons/icon-25x25-heating.svg')
            ),
            "elevetor" => array(
                "name" => "Elevator",
                "icon" => asset('/frontend/assets/icons/icon-25x25-elevator.svg')
            ),
            "outdoor_space" => array(
                "name" => "Outdoor",
                "icon" => asset('/frontend/assets/icons/icon-25x25-outdoor.svg')
            ),
            "dishwasher" => array(
                "name" => "Dishwasher",
                "icon" => asset('/frontend/assets/icons/icon-25x25-dishwasher.svg')
            ),
            "central_ac" => array(
                "name" => "Central a/c",
                "icon" => asset('/frontend/assets/icons/icon-25x25-ac.svg')
            ),
            "pool" => array(
                "name" => "Pool",
                "icon" => asset('/frontend/assets/icons/icon-25x25-pool.svg')
            ),
            "doorman" => array(
                "name" => "Doorman",
                "icon" => asset('/frontend/assets/icons/icon-25x25-doorman.svg')
            ),
            "gym" => array(
                "name" => "Gym",
                "icon" => asset('/frontend/assets/icons/icon-25x25-gym.svg')
            ),
            "pets" => array(
                "name" => "Pets",
                "icon" => asset('/frontend/assets/icons/icon-25x25-pets.svg')
            ),
            "dogs" => array(
                "name" => "Dogs",
                "icon" => asset('/frontend/assets/icons/icon-25x25-dogs.svg')
            ),
            "laundry" => array(
                "name" => "Laundry",
                "icon" => asset('/frontend/assets/icons/icon-25x25-laundry.svg')
            ),
            "cats" => array(
                "name" => "Cat",
                "icon" => asset('/frontend/assets/icons/icon-25x25-cat.svg')
            ),
            "others" => array(
                "name" => "Other",
                "icon" => ""
            )
        );
        ?>
        <?php foreach($features as $feature => $value){ if($tour->property->$feature == 1) { ?>
        <?php if($value['icon'] != "") { ?>
		<div class="my-properties__equip-item">
			<img src="<?php echo $value['icon']; ?>" title="{{$value['name']}}"/>
		</div>
        <?php } } } ?>
	</td>
	<td>{{$tour->price}}</td>
	<td>
		Name: {{optional($tour->property->property_contact)->contact_name}} <br>
		Phone: {{optional($tour->property->property_contact)->contact_phone}}
	</td>


	<td>
		Name: {{$tour->name}} <br>
		Phone: {{$tour->phone_no}} <br>
		Email: {{$tour->email}}

	</td>
	<td>{{$tour->schedule_date}}</td>
	@if($type=='yes')
		<td>
			<form method="post" action="{{url('admin/tour/visited_date_update')}}">

				<input type="text" class="datepickers" name="visited_date" value="{{$tour->visited_date}}"/><input type="hidden" class="datepickers" name="tour_id"  value="{{$tour->id}}"/>
				{{ csrf_field() }}
				<button type="submit"> Update</button>
			</form>
		</td>
		@else
		<td><a href="{{url("admin/tour/visited/".$tour->id)}}" class="btn btn-primary">yes</a></td>
	@endif

</tr>
@endforeach