<table id="offers" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Type</th>
            <th>{{trans('labels.namelbl')}}</th>
            <th>{{trans('labels.refidlbl')}}</th>
            <th>{{trans('labels.datelbl')}}</th>
            <th>{{trans('labels.assetlbl')}}</th>
            <th>{{trans('labels.step1lbl')}}</th>
            <th>PriceListed</th>
            <th>{{trans('labels.step2lbl')}}</th>
            <th>Mortgage</th>
            <th>Cash</th>
            <th>{{trans('labels.step3lbl')}}</th>
            <th>{{trans('labels.step4lbl')}}</th>
            <th>{{trans('labels.step5lbl')}}</th>
            <th>{{trans('labels.step6lbl')}}</th>
            <th>{{trans('labels.step7lbl')}}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($offers as $offer)
            <tr>
                <td>{{$offer->property->property_deal}}</td>
                <td>{{$offer->user->name}} {{$offer->user->last_name}}</td>
                <td>{{$offer->asset_id}}</td>
                <td>{{$offer->created_at->format('d-m-Y')}}</td>
                <td>{{$offer->property->direccion}}</td>
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
                        <!--<table class="table">
                            <tbody>
                                <tr>
                                    <td>{{trans('labels.paymentmethodlbl')}}</td>
                                    <td>{{$offer->payment_method}}</td>
                                </tr>
                                @if($offer->payment_method == 'Mortgage')
                                <tr>
                                    <td>{{trans('labels.mortgagelbl')}}</td>
                                    <td>{{$offer->payment_method_mortgage}}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('labels.amountlbl')}}</td>
                                    <td>{{$offer->payment_method_mortgage_dp_amount}}</td>
                                </tr>
                                @if($offer->payment_method_mortgage_certificate != '')
                                <tr>
                                    <td>{{trans('labels.certificatelbl')}}</td>
                                    <td><a href="{{asset('offer-storage/'.$offer->payment_method_mortgage_certificate)}}" target="_new">Certificate</a></td>
                                </tr>
                                @endif
                                @endif
                            </tbody>
                        </table>-->
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
                    if($offer->step_1_completed == 1 && $offer->step_2_completed == 1){
                        if($offer->step_2_negotiate_flag == 0) {
                            $suggested_price = $offer->owner_offer_price;
                        } else {
                            $suggested_price = $offer->customer_offer_price;
                        }
                        if($offer->payment_method == 'All cash') {
                            $str_mortgage = '-';
                            $remaining_mortgage = "&euro;".$suggested_price;
                        } else {
                            $mortgage_percentage = $offer->payment_method_mortgage_dp_amount;
                            $mortgage_amount = round(($suggested_price * $mortgage_percentage) / 100, 0);
                            $remaining_mortgage = "&euro;".($suggested_price - $mortgage_amount);
                            $str_mortgage = implode(", ", [$mortgage_percentage."%", "&euro;".$mortgage_amount]);
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
                        <!--<table class="table">
                            <tbody>
                                <tr>
                                    <td>{{trans('labels.customernamelbl')}}</td>
                                    <td>{{$offer->customer_name}}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('labels.customerphonelbl')}}</td>
                                    <td>{{$offer->customer_phone}}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('labels.customeremaillbl')}}</td>
                                    <td>{{$offer->customer_email}}</td>
                                </tr>
                            </tbody>
                        </table>-->
                    @endif
                </td>
                <td>
                    @if($offer->step_4_completed == 0)
                        -
                    @else
                        @if($offer->mark_as_visited == 0)
                        {{($offer->schedule_visit_1 != '')?$offer->schedule_visit_1:''}}, {{($offer->schedule_visit_1 != '')?$offer->schedule_visit_1:''}}
                        @else
                            Visited
                        @endif
                        <!--<table class="table">
                            <tbody>
                                @if($offer->mark_as_visited == 0)
                                    <tr>
                                        <td>{{trans('labels.schedulevisite1lbl')}}</td>
                                        <td>{{$offer->schedule_visit_1}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('labels.schedulevisite2lbl')}}</td>
                                        <td>{{$offer->schedule_visit_2}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{trans('labels.visitedlbl')}}</td>
                                        <td>{{$offer->mark_as_visited}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>-->
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
                        
                    @if($offer->first_name_1 != '' || $offer->last_name_1 != '' || $offer->photo_1 != '')
                        Buyer
                        <br/>
                        @if($offer->first_name_1 != '')
                            {{$offer->first_name_1}}
                        @endif
                        &nbsp;
                        @if($offer->last_name_1 != '')
                            {{$offer->last_name_1}}
                        @endif
                        <br/>
                        
                    @endif
                    @if($offer->first_name_2 != '' || $offer->last_name_2 != '' || $offer->photo_2 != '')
                        Co-Buyer
                        <br/>
                        @if($offer->first_name_2 != '')
                            {{$offer->first_name_2}}
                        @endif
                        &nbsp;
                        @if($offer->last_name_2 != '')
                            {{$offer->last_name_2}}
                        @endif
                        
                    @endif
                            
                    @endif
                </td>
                <td>
                    @if($offer->step_7_completed == 0)
                        -
                    @else
                        {{($offer->signature_schedule_1 != '')?$offer->signature_schedule_1:''}}, {{($offer->signature_schedule_2 != '')?$offer->signature_schedule_2:''}}, {{($offer->signature_schedule_3 != '')?$offer->signature_schedule_3:''}}
                        <!--<table class="table">
                            <tbody>
                                <tr>
                                    <td>{{trans('labels.schedule1lbl')}}</td>
                                    <td>{{$offer->signature_schedule_1}}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('labels.schedule2lbl')}}</td>
                                    <td>{{$offer->signature_schedule_2}}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('labels.schedule3lbl')}}</td>
                                    <td>{{$offer->signature_schedule_3}}</td>
                                </tr>
                            </tbody>
                        </table>-->
                    @endif
                </td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>