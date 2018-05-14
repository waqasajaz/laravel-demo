 var loquare_commission = {
    label: "LOQUARE",
    data: [],
    backgroundColor: [ 'rgba(0, 171, 138, 0.5)' ],
    borderColor: [ 'rgba(0, 171, 138, 1)' ],
    borderWidth: 1
};

var real_estate = {
    label: "STANDARD",
    data: [],
    backgroundColor: [ 'rgba(255, 151, 0, 0.5)' ],
    borderColor: ['rgba(255, 151, 0, 1)'],
    borderWidth: 1
};

var commission = [real_estate, loquare_commission];
var commission_graph_offer = document.getElementById("commission_graph_offer");
var ctx = (commission_graph_offer)?commission_graph_offer.getContext('2d'):false;

var commission_chart = ctx?new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: ['STANDARD', 'LOQUARE'],
        datasets: commission
    },
    options: {
        tooltips: {
            enabled: true
        },
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
}):false;

 if(ctx){
     Chart.plugins.register({
         afterDatasetsDraw: function(chart) {
             var ctx = chart.ctx;

             chart.data.datasets.forEach(function(dataset, i) {
                 var meta = chart.getDatasetMeta(i);
                 if (!meta.hidden) {
                     meta.data.forEach(function(element, index) {
                         // Draw the text in black, with the specified font
                         ctx.fillStyle = 'rgb(0, 0, 0)';

                         var fontSize = 12;
                         var fontStyle = 'normal';
                         var fontFamily = 'Helvetica Neue';
                         ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                         // Just naively convert to string for now
                         var dataString = dataset.data[index].toString();

                         // Make sure alignment settings are correct
                         ctx.textAlign = 'center';
                         ctx.textBaseline = 'middle';

                         var padding = 5;
                         var position = element.tooltipPosition();
                         ctx.fillText("€"+dataString, 120, position.y+3);
                     });
                 }
             });
         }
     });
 }

$(document).ready(function()
{
    if(ctx){ update_offer_commission();}
	
	$("#customer_offer_price, #slider_input").on("change", function(){
        if(ctx){ update_offer_commission();}
	});
});

function update_offer_commission()
{
    var  suggested_price = $('#customer_offer_price').val().trim().replace(/,/g , "");

    suggested_price = parseInt(suggested_price);

    commission_loquare = (suggested_price < 1000)?((suggested_price/1000)*0.5)*suggested_price:(suggested_price*0.5);

    if(commission_loquare.toString().indexOf(".") > -1)
    {
        commission_loquare = commission_loquare.toFixed(2);
    }

    commission_realestate = suggested_price;

    $("#loquare_commission").val(commission_loquare);
    $("#realestate_commission").val(commission_realestate);

    loquare_commission.data = [];
    loquare_commission.data.push(commission_loquare);

    real_estate.data = [];
    real_estate.data.push(commission_realestate);

    if(!isNaN(commission_loquare) && !isNaN(commission_realestate))
    {
        loquare_commission.label = "LOQUARE";
        real_estate.label = "STANDARD";
    }

    commission = [loquare_commission, real_estate];
    commission_chart.update();
}
