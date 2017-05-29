<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/x-icon" href="{{ Config::get('cms.favicon') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="token" name="token" value="{{ csrf_token() }}">  

        <title>{!! Config::get('cms.name') !!}</title>

        <meta name="description" content="">
        <meta name="author" content="{{ Config::get('cms.designer') }}">
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Sticky Footer -->
        <link rel="stylesheet" href="{{ asset('css/sticky-footer-navbar.css') }}">

        <!-- Custom Font -->
        <link rel="stylesheet" href="{{ asset('css/font.css') }}">

        <!-- MDB CSS -->
        <link rel="stylesheet" href="{{ asset('css/mdb.css') }}">

        <!-- Toastr Styling -->
        <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="" id="manage-dashboard">
            <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#">Navbar</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active"><a class="nav-link" href="#">Summary <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Performance</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Trends</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Drilldown</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="" data-target="#myModal" data-toggle="modal"><i class="fa fa-user-circle"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="" data-target="#myModal" data-toggle="modal"><i class="fa fa-user-circle"></i> Register</a></li>
                    </ul>
                </div>
            </nav>
            <p class="" style="padding-top:50px;"></p>
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-mdb">
                            <div class="card-block bg-mdb">
                                <div class="rotate">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <h6 class="text-uppercase">Programs</h6>
                                <h1 class="display-1">134</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-dark-green">
                            <div class="card-block bg-dark-green">
                                <div class="rotate">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <h6 class="text-uppercase">Enrolments</h6>
                                <h1 class="display-1">134</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-blue-gray">
                            <div class="card-block bg-blue-gray">
                                <div class="rotate">
                                    <i class="fa fa-list fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase">Shipments</h6>
                                <h1 class="display-1">87</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-cyan">
                            <div class="card-block bg-cyan">
                                <div class="rotate">
                                    <i class="fa fa-twitter fa-5x"></i>
                                </div>
                                <h6 class="text-uppercase">Results</h6>
                                <h1 class="display-1">125</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-lime">
                            <div class="card-block bg-lime">
                                <div class="rotate">
                                    <i class="fa fa-share fa-5x"></i>
                                </div>
                                <h6 class="text-uppercase">Satisfactory</h6>
                                <h1 class="display-1">36</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6">
                        <div class="card card-inverse card-deep-orange">
                            <div class="card-block bg-deep-orange">
                                <div class="rotate">
                                    <i class="fa fa-share fa-5x"></i>
                                </div>
                                <h6 class="text-uppercase">Unsatisfactory</h6>
                                <h1 class="display-1">36</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <div id="gContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <div id="pContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <div id="sContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top:10px;">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <div id="fContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <h3 class="card-title">Special title treatment</h3>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-block">
                                <h3 class="card-title">Special title treatment</h3>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <hr>
            <div class="container">
                <span class="text-muted">Place sticky footer content here.</span>
            </div>
        </footer>
    </body>
    <!-- JQuery v1.9.1 -->
    <script src="{{ asset('js/jquery-1.12.3.min.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- Vue JS -->
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/vue-resource.min.js') }}"></script>
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/exporting.js') }}"></script>
    <script src="{{ asset('controllers/dashboard.js') }}"></script>
    <script>
        // Shipment receiving rate
        $(function () {
            Highcharts.setOptions({
            colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263',      '#6AF9C4']
            });
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'sContainer',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: 'Shipment receiving, Round 16'
                    },
                    credits: {
                      enabled: false
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                color: '#000000',
                                connectorColor: '#000000',
                                formatter: function() {
                                    return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                                }
                            }
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Browser share',
                        data: [
                            ['Firefox',   45.0],
                            ['IE',       26.8],
                            {
                                name: 'Chrome',
                                y: 12.8,
                                sliced: true,
                                selected: true
                            },
                            ['Safari',    8.5],
                            ['Opera',     6.2],
                            ['Others',   0.7]
                        ]
                    }]
                });
            });
        });
        // First chart
        var chart = new Highcharts.Chart({
          chart: {
              renderTo:'fContainer',
              type:'column'
          },
          title:{
              text:'Chart Title'
          },
          credits:{enabled:false},
          legend:{
          },
          plotOptions: {
              series: {
                  shadow:false,
                  borderWidth:0,
              }
          },
          xAxis:{
              lineColor:'#999',
              lineWidth:1,
              tickColor:'#666',
              tickLength:3,
              title:{
                  text:'X Axis Title'
              }
          },
          yAxis:{
              lineColor:'#999',
              lineWidth:1,
              tickColor:'#666',
              tickWidth:1,
              tickLength:3,
              gridLineColor:'#ddd',
              title:{
                  text:'Y Axis Title',
                  rotation:0,
                  margin:50,
              }
          },    
          series: [{
              data: [7,12,16,32,64]
          },{
              data: [7,12,16,32,64]
          },{
              data: [7,12,16,32,64]
          }]
      });
      var json = [{
    "key": "Apples",
    "value": "4"
}, {
    "key": "Pears",
    "value": "7"
}, {
    "key": "Bananas",
    "value": "9"
}];
var processed_json = new Array();
$.map(json, function(obj, i) {
    processed_json.push([obj.key, parseInt(obj.value)]);
});
console.log(json);
    </script>
</html>