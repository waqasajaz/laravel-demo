<?php if($sold_properties->count() > 0){ foreach($sold_properties as $flat) { ?>
<div class="nearby__item">
    <div class="card card--compare" title="<?php echo $flat->direccion; ?>">
        <a href="{{ url('/')  }}/property/detail/<?php echo $flat->id; ?>" class="card__link"></a>
        <div class="card__top lazyload" data-sizes="auto"
             data-bgset="<?php if (Storage::disk('s3')->exists("Properties/" . $flat->id . "/" . optional($flat->image)->filename)) {
                 echo Storage::disk('s3')->url("Properties/" . $flat->id . "/thumbs/" . optional($flat->image)->filename);
             } else {
                 echo asset('/storage/Properties/' . $flat->id . "/thumbs/" . optional($flat->image)->filename) . ' 1x,'; ?>  <?php echo asset('/storage/Properties/' . $flat->id . "/thumbs/" . optional($flat->image)->filename) . ' 2x';
             }
             ?>"></div>
        <div class="card__bottom">
            <div class="card__title">
                <?php echo substr($flat['direccion'], 0, 15) . ".."; ?>
                <div class="card__desc">
                    <?php echo $flat->rooms; ?> bed,
                    <?php echo $flat->bathrooms; ?> baths
                    <?php echo $flat->sizem2; ?> m
                </div>
            </div>
            <div class="">
                <?php echo $flat->hood->hood."-".$flat->cops; ?>
            </div>
            <div class="card__footer">
                <table>
                    <tr class="green">
                        <td>Listed</td>
                        <td><?php echo date("Y-m-d", strtotime($flat->created_at)); ?></td>
                        <td><div class="card__price green">€<?php echo ($flat->property_deal == "SALE") ? $flat->price: $flat->price. "/mo"; ?></div></td>
                    </tr>
                    <tr class="orange">
                        <td>Sold</td>
                        <td><?php echo date("Y-m-d", strtotime($flat->offers[0]->sold_date)); ?></td>
                        <td><div class="card__price orange">€<?php echo ($flat->property_deal == "SALE") ? $flat->offers[0]->customer_offer_price: $flat->offers[0]->customer_offer_price. "/mo"; ?></div></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } } else{}?>
