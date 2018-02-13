<?php
/**
 * Created by PhpStorm.
 * User: sunnydrake
 * Date: 13.02.18
 * Time: 7:18
 */
include_once('db.php');

class Price extends cDB {
    function getItemsWithPrice(){
        $itemsa=array();
        $items=$this->getItems();
        if ($items===false) return;
        foreach ($items as $item) {
            $prices = $this->getPrices($item->id);
            //$itemsa[]= object(array_merge($item,$prices));
            $itemsa[]=[$item,$prices];//object();$obj["item"]=$item;$obj["prices"]=$prices;
            //var_dump(array_merge($item,$prices));
            //var_dump($itemsa);
        }
        //if ($prices===false) return;
        return json_encode($itemsa);
    }
}
$price=new Price();
if (isset($_POST["idprice"])) {
    $result=$price->SaveItem($_POST["idprice"],$_POST["type"],$_POST["value"]);
    //var_dump($result);
    echo "сохранение измений для ".$_POST["type"].", результат: ".$result;
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css"
          integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <!--  jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker//1.6.4/css/bootstrap-datepicker3.css"/>
    <!--  Charts -->
    <script type="text/javascript" src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
    <script type="text/javascript" src="http://www.chartjs.org/samples/latest/utils.js"></script>
    <script type="text/javascript" src="servertalk.js"></script>
    <style>
        ul {
            float:left;
            width:300px;
            display:inline;
        }
        .badge-default {
            background-color: #636c72;
            color:white;
        }
        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
        .list-group-item:first-child {
            border-top-right-radius: .25rem;
            border-top-left-radius: .25rem;
        }
        .justify-content-between {
            -webkit-box-pack: justify!important;
            -webkit-justify-content: space-between!important;
            -ms-flex-pack: justify!important;
            justify-content: space-between!important;
        }

        .list-group-item {
            position: relative;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-flow: row wrap;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            padding: .75rem 1.25rem;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,.125);
        }
        #output {
            /*float:left;
            width:00px;*/
        }
        .details {
            float:left;
            width:800px;
            display:none;
            /*border:2px solid red;*/
        }
        .width {
            width:615px;
        }
        input {
            display:inline;
            /*padding-right:20px;*/
        }
        .form-control {
            width:150px;
            display:inline;
        }
        #canvas {
            width:200px;
            display:inline;
        }
    </style>

</head>
<body>
<div id="output">
</div>
<div id="response">
</div>
<script type="application/javascript">
var data=<?= $price->getItemsWithPrice(); ?>;
for (var i=0;i<data.length;i++)
    for (var z=0;z<data[i][1].length;z++)
        data[i][1][z].orignum=z;
function changeData(id,i,itemb,type) {
    var item=data[id][1][i];
    var obj=new Object();
    obj.idprice=item["idprice"];
    obj.type=type;
    obj.value=itemb.value;
//    console.log(i);
//    console.log(obj);
    sendtoserver(obj);
    //update local copy
    item[type]=itemb.value;
    //redraw
    showDetails(id,globalsort);
}
var MONTHS = ["2016-01","2016-02", "2016-03", "2016-04", "2016-05", "2016-06", "2016-07", "2016-08", "2016-09", "2016-10", "2016-11", "2016-12",
    "2017-01","2017-02", "2017-03", "2017-04", "2017-05", "2017-06", "2017-07", "2017-08", "2017-09", "2017-10", "2017-11", "2017-12",
    "2018-01"
    ];
var config = {
    type: 'line',
    data: {
        labels: MONTHS,
        datasets: [{
            label: "Цена",
            backgroundColor: window.chartColors.red,
            borderColor: window.chartColors.red,
            data: [
            ],
            fill: false
        }]
    },
    options: {
        responsive: true,
        title:{
            display:true,
            text:'Цена Line Chart'
        },
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true
        },
        scales: {
            xAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Дата'
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Цена'
                },
                ticks: {
                    min: 0,
                    max: 20000,

                    // forces step size to be 5 units
                    stepSize: 1000
                }
            }]
        }
    }
};
function getPriceGraph(datao,baseprice,sort) {
    var prices=[];
    for (var i=0;i<MONTHS.length;i++) {
        var price=baseprice;
        for (var z=0;z<datao.length;z++) {
            var sd=new Date(datao[z]["datestart"]);
            if (datao[z]["dateend"]!==null)
            var ed=new Date(datao[z]["dateend"]);
            else var ed=new Date("9999-12-01");
            var cd=new Date(MONTHS[i]);
            if (sort==1) {
                if (sd < cd || sd.valueOf() == cd.valueOf())
                    if (cd < ed || cd.valueOf() == ed.valueOf()) {
                        price = datao[z]["price"];
                            break;
                    }
            }
            if (sort==2 || sort==0) {
                if (sd < cd || sd.valueOf() == cd.valueOf()) {
                    if (cd < ed || cd.valueOf() == ed.valueOf()) {

                            price = datao[z]["price"];
                    }
                }
            }
        }
        prices.push(price);
    }
    return prices;
}
function diffdates(date1,date2) {
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
}
var globalsort=0;
function showDetails(id,sort=0){
    globalsort=sort;
    var cont=document.getElementsByClassName("details")[0];
    cont.innerHTML='';
    cont.style.display='inline';
    if (data[id][1]===false) return;
    var datao=[];
    switch (sort)
    {
        case 0:
            datao = data[id][1];
            break;
        case 1: //lesser days value
            var datao= Object.create(data[id][1]);
            for (var i=0;i<datao.length;i++) {
                if (datao[i]['dateend']===null)
                var diff=diffdates(new Date(datao[i]['datestart']),new Date('9999-12-01'));
                else var diff=diffdates(new Date(datao[i]['datestart']),new Date(datao[i]['dateend']));
                for (var z=i;z<datao.length;z++) {
                    if (datao[z]['dateend']===null)
                        var diff2=diffdates(new Date(datao[z]['datestart']),new Date('9999-12-01'));
                    else var diff2=diffdates(new Date(datao[z]['datestart']),new Date(datao[z]['dateend']));
                    if (diff>diff2) {
                        var oldd=datao[i];
                        datao[i]=datao[z];
                        datao[z]=oldd;
                        diff=diff2;
                    }
                }
            }
            break;
        case 2: // date that occurs first
            var datao= Object.create(data[id][1]);
            for (var i=0;i<datao.length;i++) {
                var diff=new Date(datao[i]['datestart']);
                for (var z=i;z<datao.length;z++) {
                    var diff2=new Date(datao[z]['datestart']);
                    if (diff>diff2) {
                        var oldd=datao[i];
                        datao[i]=datao[z];
                        datao[z]=oldd;
                        diff=diff2;
                    }
                }
            }
            break;
    }
    var sortb=document.createElement("div");sort.className="sort_block list-group-item justify-content-between width";
    sortb.innerHTML="<input type='button' value='Сортировка по умолчанию' onclick='showDetails("+id+")'/>" +
        "<input type='button' value='Сортировка по времени'  onclick='showDetails("+id+",2)'/>" +
        "<input type='button' value='Сортировка по минимуму' onclick='showDetails("+id+",1)'/>"
    cont.appendChild(sortb);
    var container=document.createElement("ul");
    container.className="list-group width";
    for (var i=0;i<datao.length;i++){
        var item=document.createElement("li");
        item.className="list-group-item justify-content-between ";
        var itemb=document.createElement("input");
        itemb.className="list-group-item list-group-item-action form-control";
        itemb.value=datao[i]["price"];
        item.appendChild(itemb);
        itemb.onchange=( function(n,inn,ib,typ){ return function(){changeData(n,inn,ib,typ);} } )( id,datao[i]["orignum"],itemb,"price" );
        var itemn=document.createElement("input");
        itemn.className="list-group-item list-group-item-action form-control";

        itemn.value=datao[i]['datestart'];
        $(itemn).datepicker({
            format: "yyyy-mm-dd",
            todayBtn: true,
            clearBtn: true,
            language: "ru",
            autoclose: true,
            todayHighlight: true
        });
        item.appendChild(itemn);
        itemn.onchange=( function(n,inn,ib,typ){ return function(){changeData(n,inn,ib,typ);} } )( id,datao[i]["orignum"],itemn,"datestart" );
        var itemn2=document.createElement("input");
        itemn2.className="list-group-item list-group-item-action form-control";

        itemn2.value=datao[i]['dateend'];
        $(itemn2).datepicker({
            format: "yyyy-mm-dd",
            todayBtn: true,
            clearBtn: true,
            language: "ru",
            autoclose: true,
            todayHighlight: true
        });
        item.appendChild(itemn2);
        itemn2.onchange=( function(n,inn,ib,typ){ return function(){changeData(n,inn,ib,typ);} } )( id,datao[i]["orignum"],itemn2,"dateend" );


        container.appendChild(item);
    }
    cont.appendChild(container);

    //chart
    config.data.datasets[0].data=getPriceGraph(datao,data[id][0]["baseprice"],sort);
    var graph=document.createElement("canvas");
    graph.id="canvas";
    var ctx=graph.getContext("2d");
    window.myLine = new Chart(ctx, config);
    cont.appendChild(graph);

}
function PrintData(order='') {
    console.log(data);
    var out=document.getElementById("output");
    out.innerHTML="";
    var container=document.createElement("ul");
    container.className="list-group";
    for (var di=0;di<data.length;di++) {
        var item=document.createElement("li");
        item.className="list-group-item justify-content-between";
        item.onmouseover=( function(n){ return function(){showDetails(n);} } )( di );
        if (data[di][0]!==false)
            item.innerHTML=data[di][0]["name"];
        var itemn=document.createElement("span");
        itemn.className="badge badge-default badge-pill";
        if (data[di][1]!==false)
            itemn.innerHTML=data[di][1].length;
        itemn.innerHTML+=" >>";
        item.appendChild(itemn);
        container.appendChild(item);

    }
    out.appendChild(container);
    var detailscontainer=document.createElement("div");
    detailscontainer.className="details  justify-content-between ";
    out.appendChild(detailscontainer);
}
PrintData();
</script>
</body>
</html>
