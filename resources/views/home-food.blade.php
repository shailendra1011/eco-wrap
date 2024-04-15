@extends('layouts.main')
@section('content')

<div class="row m-t-n-md">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content" style="min-height: 575px;">
            <div class="row">
                {{-- <div class="col-sm-3">
                    <div class="widget style1 navy-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> Today Orders </span>
                                <h2 class="font-bold">0</h2>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-sm-3">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-euro fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> {{__('StaticWords.total_revenue')}}</span>
                                <h2 class="font-bold">{{$count->total_revenue}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="widget style1 yellow-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-product-hunt fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> {{__('StaticWords.total_product')}} </span>
                                <h2 class="font-bold">{{$count->total_product}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="widget style1 red-bg" style="background-color: #7d099a;">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-shopping-basket fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span>{{__('StaticWords.total_order')}}</span>
                                <h2 class="font-bold">{{$count->total_order}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-product-hunt fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> {{__('StaticWords.total_com_order')}} </span>
                                <h2 class="font-bold">{{$count->total_compelet_order}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class=" col-12">
                    <h3 style="margin-bottom: -30px">{{__('StaticWords.recent_order')}}</h3>
                    <a href="{{route('product.orders')}}"
                        class="btn btn-primary float-right">{{__('StaticWords.view_all')}}</a>
                </div>
            </div>
            <hr>
            <div class="table-responsive" style="margin-bottom: 20px">
                <table class="table table-striped  table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Order no</th>
                            <th>Total</th>
                            <th>Shipping charge</th>
                        </tr>
                    </thead>
                    <tbody id="user_data">
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 mt-5">
        <div id="container"></div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
<script src="{{asset('js/highcharts.js')}}"></script>
<script src="{{asset('js/venn.js')}}"></script>
<script>
    $(document).ready(function() {
        get_data();
    });
    var revenue;
    var orderCount;
    function get_data(link = null) {       

        $.ajax({
            url: link != null ? link : "{{route('product.recent')}}",
            type: "get",
            data: {
                _token: "{{ csrf_token() }}",
                
            },
            success: function(res) {
                console.log(res);
                revenue= res.data.revenue;
                orderCount= res.data.order_count;
                viewChart();
                 if (res.data.order.length) {
                    

                    $('#user_data').html('');
                    let row = ``;
                    $.each(res.data.order, function(key, value) {
                        console.log(value);
                        
                        row += `
							<tr>
							<td> ${key+1} </td>                       
                            <td> ${value.user_details? value.user_details.name : '-'}</td>
                            <td> ${value.user_details? value.user_details.email : '-'} </td>
                            <td> ${value.user_details? value.user_details.mobile : '-'} </td>  
                            <td> ${value.order_no? value.order_no : '-'} </td>  
                            <td> ${value.total_price? value.total_price : '-'} </td>  
                            <td> ${value.shipping_charge? value.shipping_charge : '-'} </td>  
                                                            
                            `;
                    })
                    $('#user_data').html(row);                    ;
                } else {
                    let row = `
                            <tr>
                            <td colspan="12"> Record not found! </td>
                            </tr>
                            `;
                    $('#user_data').html(row);
                }
            },
        });
    }

   //chart
   function viewChart(){
    Highcharts.chart('container', {
        legend: {     
        labelFormatter: function () {
            return '<span style="color:' + this.color + '">' + this.name + '</span>';
        }, 
    },
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{       
        name: 'Revenue',        
        color:"#49c6c8",
        data: revenue
    }, {
        name: 'Order',
        color:"#ed5565",
        data: orderCount


    },    
    ]
  });
}
</script>

@endsection