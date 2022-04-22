@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{$title}}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$totalUsers}}</h3>
                            <p>Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('admin/user')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{$totalFiles}}</h3>
                            <p>Files</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{url('admin/user')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{$totalPackagesUsed}}</h3>
                            <p>Users have used packages</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{url('admin/package')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Donut Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;" width="487" height="250" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Area Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;" width="487" height="250" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Line Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@stop
@section('js')
    <script>
        $(function () {


            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.

            $.ajax ({
                url : '{{url('admin/dashboard/donut-chart')}}',
                type : 'get',
                data : {},
                success : function (res) {
                    if (res.code === 200) {
                        console.log('donut: ');
                        console.log(res);
                        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
                        var donutData        = {
                            labels: [
                                res.data[0].name,
                                res.data[1].name,
                                res.data[2].name,
                            ],
                            datasets: [
                                {
                                    data: [
                                        res.data[0].total_user,
                                        res.data[1].total_user,
                                        res.data[2].total_user,
                                    ],
                                    backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
                                }
                            ]
                        }
                        var donutOptions     = {
                            maintainAspectRatio : false,
                            responsive : true,
                        }
                        //Create pie or douhnut chart
                        // You can switch between pie and douhnut using the method below.
                        new Chart(donutChartCanvas, {
                            type: 'doughnut',
                            data: donutData,
                            options: donutOptions
                        })
                    }
                }
            })

            //--------------
            //- AREA CHART -
            //--------------

            $.ajax({
                url : '{{url('admin/dashboard/area-chart')}}',
                type : 'get',
                data : {},
                success : function (res) {
                    console.log(res);
            // Get context with jQuery - using jQuery's .get() method.
                    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

                    var areaChartData = {
                        labels  : [
                            res.data[6].date,
                            res.data[5].date,
                            res.data[4].date,
                            res.data[3].date,
                            res.data[2].date,
                            res.data[1].date,
                            res.data[0].date,
                        ],
                        datasets: [
                            {
                                label               : 'Total Files Upload',
                                backgroundColor     : 'rgba(60,141,188,0.9)',
                                borderColor         : 'rgba(60,141,188,0.8)',
                                pointRadius          : false,
                                pointColor          : '#3b8bba',
                                pointStrokeColor    : 'rgba(60,141,188,1)',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                fill: 'origin',
                                data                : [
                                    res.data[6].total_file,
                                    res.data[5].total_file,
                                    res.data[4].total_file,
                                    res.data[3].total_file,
                                    res.data[2].total_file,
                                    res.data[1].total_file,
                                    res.data[0].total_file,
                                ]
                            },
                        ]
                    }

                    var areaChartOptions = {
                        maintainAspectRatio : false,
                        responsive : true,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                gridLines : {
                                    display : false,
                                }
                            }],
                            yAxes: [{
                                gridLines : {
                                    display : false,
                                }
                            }]
                        }
                    }
// This will get the first returned node in the jQuery collection.
                    new Chart(areaChartCanvas, {
                        type: 'line',
                        data: areaChartData,
                        options: {
                            plugins: {
                                filler: {
                                    propagate: true
                                }
                            }
                        }
                    })
                }
            })
            //--------------
            //- LINE CHART -
            //--------------


            $.ajax({
                url : "{{url('/admin/dashboard/line-chart')}}",
                type : 'get',
                data : {},
                success : function (res) {
                    console.log(res);
                    const labels = [
                        res.data[29].date,
                        res.data[28].date,
                        res.data[27].date,
                        res.data[26].date,
                        res.data[25].date,
                        res.data[24].date,
                        res.data[23].date,
                        res.data[22].date,
                        res.data[21].date,
                        res.data[20].date,
                        res.data[19].date,
                        res.data[18].date,
                        res.data[17].date,
                        res.data[16].date,
                        res.data[15].date,
                        res.data[14].date,
                        res.data[13].date,
                        res.data[12].date,
                        res.data[11].date,
                        res.data[10].date,
                        res.data[9].date,
                        res.data[8].date,
                        res.data[2].date,
                        res.data[7].date,
                        res.data[6].date,
                        res.data[5].date,
                        res.data[4].date,
                        res.data[3].date,
                        res.data[2].date,
                        res.data[1].date,
                        res.data[0].date,
                    ];

                    const data = {
                        labels: labels,
                        datasets: [{
                            label: 'Total Files Upload',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [res.data[29].date,
                                res.data[28].total_file,
                                res.data[27].total_file,
                                res.data[26].total_file,
                                res.data[25].total_file,
                                res.data[24].total_file,
                                res.data[23].total_file,
                                res.data[22].total_file,
                                res.data[21].total_file,
                                res.data[20].total_file,
                                res.data[19].total_file,
                                res.data[18].total_file,
                                res.data[17].total_file,
                                res.data[16].total_file,
                                res.data[15].total_file,
                                res.data[14].total_file,
                                res.data[13].total_file,
                                res.data[12].total_file,
                                res.data[11].total_file,
                                res.data[10].total_file,
                                res.data[9].total_file,
                                res.data[8].total_file,
                                res.data[2].total_file,
                                res.data[7].total_file,
                                res.data[6].total_file,
                                res.data[5].total_file,
                                res.data[4].total_file,
                                res.data[3].total_file,
                                res.data[2].total_file,
                                res.data[1].total_file,
                                res.data[0].total_file
                            ],
                        }]
                    };

                    const config = {
                        type: 'line',
                        data: data,
                        options: {}
                    };

                    const myChart = new Chart(
                        document.getElementById('line-chart'),
                        config
                    );
                }
            })

        })
    </script>
@stop

