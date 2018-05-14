@forelse($offers as $offer)
<tr>
	@if(!isset($type))
	<td>{{$offer->property->property_deal}}</td>
	@endif
	<td>
		@if(isset($from) && $from == 'completed')
		<small class="label bg-red">{{($offer->new_completed > 0)?'New':''}}</small>&nbsp;
		@elseif(isset($from) && $from == 'dashboard')
		<small class="label bg-red">{{($offer->new > 0)?'New':''}}</small>&nbsp;
		@endif
		{{(isset($offer->user->name))?$offer->user->name:''}} {{(isset($offer->user->lastname))?$offer->user->lastname:''}}
	</td>
	<td>{{$offer->asset_id}}</td>
	<td>{{$offer->created_at->format('d-m-Y')}}</td>
	<td>{{$offer->property->direccion}}</td>
	<td>
		<a href="{{url('/property/'.$offer->property->id.'/gallery')}}" title="Property Images"><i class="fa fa-image"></i></a>
	</td>
	<td>{{ $offer->hood  }}</td>
	<td>{{ $offer->dist_name  }}</td>
	<td>{{ $offer->cops  }}</td>
	<td>{{ $offer->sizem2  }}</td>
	<td>
		@if($offer->step_1_completed == 0)
		-
		@else
		{{$offer->payment_method}}
		@if($offer->payment_method == 'Mortgage')
		, {{$offer->payment_method_mortgage}}
		, &euro;{{$offer->payment_method_mortgage_dp_amount}}
		@if($offer->payment_method_mortgage_certificate != '')
		, <a href="{{asset('offer-storage/'.$offer->payment_method_mortgage_certificate)}}" target="_new">Certificate</a>
		@endif
		@endif
		@if($offer->payment_method == 'Direct from Bank')
		, <a href="{{asset('offer-storage/'.$offer->proof_of_income)}}" target="_new">Proof of Income</a>
		@endif
		@endif
	</td>
	<td>&euro;{{($offer->property->property_deal == 'SALE')?$offer->property->price:$offer->property->price.'/mo'}}</td>
	<td>
		@if($offer->step_2_completed == 0)
		-
		@else
		@if($offer->step_2_negotiate_flag == 0)
		&euro;{{($offer->property->property_deal == 'SALE')?$offer->owner_offer_price:$offer->owner_offer_price.'/mo'}}
		@else
		&euro;{{($offer->property->property_deal == 'SALE')?$offer->customer_offer_price:$offer->customer_offer_price.'/mo'}}
		@endif
		@endif
	</td>
	@if($offer->property->property_deal == "SALE")
	<td>
		<?php
		$str_mortgage = "-";
		$remaining_mortgage = "-";
		if ($offer->step_1_completed == 1 && $offer->step_2_completed == 1) {
			if ($offer->step_2_negotiate_flag == 0) {
				$suggested_price = $offer->owner_offer_price;
			} else {
				$suggested_price = $offer->customer_offer_price;
			}
			if ($offer->payment_method == 'All cash') {
				$str_mortgage = '-';
				$remaining_mortgage = "&euro;" . $suggested_price;
			} else {
				$mortgage_percentage = $offer->payment_method_mortgage_dp_amount;
				$mortgage_amount = round(($suggested_price * $mortgage_percentage) / 100, 0);
				$remaining_mortgage = "&euro;" . ($suggested_price - $mortgage_amount);
				$str_mortgage = implode(", ", [$mortgage_percentage . "%", "&euro;" . $mortgage_amount]);
			}

		}
		?>
		{{$str_mortgage}}
	</td>
	<td>
		{{$remaining_mortgage}}
	</td>
	@elseif($offer->property->property_deal == "RENT")
	<td>-</td>
	<td>-</td>
	@endif
	<td>
		@if($offer->step_3_completed == 0)
		-
		@else
		{{$offer->customer_name}}, {{$offer->customer_phone}}, {{$offer->customer_email}}
		@endif
	</td>
	<td>
		@if($offer->step_4_completed == 0)
		-
		@else
		@if($offer->property->property_deal == 'RENT')
		@if($offer->rental_period == 1)
		1 Year
		@elseif($offer->rental_period == 2)
		3 Year
		@elseif($offer->rental_period == 3)
		&gt; 3 Year
		@endif
		@elseif($offer->property->property_deal == 'SALE')
		<?php
		$visit_schedule = [];
		if ($offer->schedule_visit_1 != '') {
			$visit_schedule[] = $offer->schedule_visit_1;
		}

		if ($offer->schedule_visit_2 != '') {
			$visit_schedule[] = $offer->schedule_visit_2;
		}

		$visit_schedule_str = implode("<br>", $visit_schedule);
		?>
		@if($offer->mark_as_visited == 0)
		{!!$visit_schedule_str!!}
		@else
		Visited
		@endif
		@endif
		@endif
	</td>
	<td>
		@if($offer->step_5_completed == 0)
		-
		@else
		Viewed
		@endif
	</td>
	<td>
		@if($offer->step_6_completed == 0)
		-
		@else
		<table class="table">
			<tbody>
			@if($offer->first_name_1 != '' || $offer->last_name_1 != '' || $offer->photo_1 != '')
			<tr>
				@if($offer->property->property_deal == 'SALE')
				<td><b>Buyer</b></td>
				@else
				<td><b>Renter</b></td>
				@endif
			</tr>
			@if($offer->first_name_1 != '')
			<tr>
				<td>{{$offer->first_name_1}}</td>
			</tr>
			@endif
			@if($offer->last_name_1 != '')
			<tr>
				<td>{{$offer->last_name_1}}</td>
			</tr>
			@endif
			@if($offer->photo_1 != '' && file_exists(public_path('offer-storage/'.$offer->photo_1)))
			<tr>
				<td>
					@if($offer->photo_1 != '' && file_exists(public_path('offer-storage/'.$offer->photo_1)))
					<a target="_new" href="{{ url('offer-storage/'.$offer->photo_1) }}"><img src="{{ url('offer-storage/'.$offer->photo_1) }}"
					                                                                         height="50" width="50"/></a>
					@endif
				</td>
			</tr>
			@endif
			@endif
			@if($offer->first_name_2 != '' || $offer->last_name_2 != '' || $offer->photo_2 != '')
			<tr>
				@if($offer->property->property_deal == 'SALE')
				<td><b>Co-Buyer</b></td>
				@else
				<td><b>Co-Renter</b></td>
				@endif
			</tr>
			@if($offer->first_name_2 != '')
			<tr>
				<td>{{$offer->first_name_2}}</td>
			</tr>
			@endif
			@if($offer->last_name_2 != '')
			<tr>
				<td>{{$offer->last_name_2}}</td>
			</tr>
			@endif
			@if($offer->photo_2 != '' && file_exists(public_path('offer-storage/'.$offer->photo_2)))
			<tr>
				<td>
					@if($offer->photo_2 != '' && file_exists(public_path('offer-storage/'.$offer->photo_2)))
					<a target="_new" href="{{ url('offer-storage/'.$offer->photo_2) }}"><img src="{{ url('offer-storage/'.$offer->photo_2) }}"
					                                                                         height="50" width="50"/></a>
					@endif
				</td>
			</tr>
			@endif
			@endif
			</tbody>
		</table>
		@endif
	</td>
	<td>
		@if($offer->step_7_completed == 0)
		-
		@else
		<?php
		$signature_schedule = [];
		if ($offer->signature_schedule_1 != '') {
			$signature_schedule[] = $offer->signature_schedule_1;
		}

		if ($offer->signature_schedule_2 != '') {
			$signature_schedule[] = $offer->signature_schedule_2;
		}

		if ($offer->signature_schedule_3 != '') {
			$signature_schedule[] = $offer->signature_schedule_3;
		}

		$signature_schedule_str = implode("<br>", $signature_schedule);
		?>
		{!!$signature_schedule_str!!}
		@endif
	</td>


	<td>{{ $offer->contact_name  }}</td>
	<td>{{ $offer->contact_email  }}</td>
	<td>{{ $offer->contact_phone  }}</td>

	@if(isset($actionColumn) && $actionColumn == 1)
	<td class="action_column">
		<?php if($offer->sold_status != 1) { ?>
			@if($offer->accept_status == 0)
				<a href="{{url('/admin/change-offer-status/'.Crypt::encrypt($offer->id).'/'.Crypt::encrypt(1))}}/{{$type}}" title="Accept"><i
						class="glyphicon glyphicon-check"></i></a>
				<a onClick="return confirm('Are you sure to reject the offer?')"
				   href="{{url('/admin/change-offer-status/'.Crypt::encrypt($offer->id).'/'.Crypt::encrypt(2))}}/{{$type}}" title="Reject"><i
						class="glyphicon glyphicon-remove"></i></a>
				<a onClick="return confirm('Are you sure to delete the offer?')" href="{{url('/admin/offer/delete/'.Crypt::encrypt($offer->id))}}/{{$type}}"
				   title="Remove"><i class="glyphicon glyphicon-trash"></i></a>
			@elseif($offer->accept_status == 1)
				<span class="label label-success">Accepted</span>
				<?php if(Auth::guard('admin')->user()->role_id == 1) { ?>
				<a href="{{url('/property-sold/'.$offer->id)}}" class="offer_sold"><button class="btn btn-xs btn-success">SOLD</button></a>
				<?php } ?>
			@elseif($offer->accept_status == 2)
				<span class="label label-danger">Rejected</span>
			@endif
		<?php } ?>
	</td>
	@endif
</tr>
@empty
@endforelse