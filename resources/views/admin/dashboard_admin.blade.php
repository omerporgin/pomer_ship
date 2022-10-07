@extends( 'admin.layout.admin' , [
    'header' => 'Cargos',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

@endsection

@section('content')

    <div id="admin_dashboard">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">

            <!-- Order Status-Line Chart -->
            <div class="col-12 col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="lineChart"></canvas>
                        <hr>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Total Earnings Of Shipexporgin</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="myBarChart"></canvas>
                        </div>
                        <hr>
                    </div>
                </div>

            </div>

            <!-- Pie Chart -->
            <div class="col-12 col-md-4">
                <div class="card shadow mb-4">
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Monthly User</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <!-- Pie Chart -->
    <script>
        var _ydata = JSON.parse('{!!json_encode($months) !!}');
        var _xdata = JSON.parse('{!!json_encode($monthCount) !!}');

        var ctxP = document.getElementById("pieChart").getContext('2d');
        var myPieChart = new Chart(ctxP, {
            type: 'pie',
            data: {
                labels: _ydata,
                datasets: [{
                    data: _xdata,
                    backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360", "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360", "#F7464A", "#46BFBD",],
                    hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774", "#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774", "#FF5A5E", "#5AD3D1",]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

    <!-- Order Status-Line Chart -->
    <script>
        var orderListKeys = JSON.parse('{!!json_encode(array_keys($orderList)) !!}');
        var orderListValues = JSON.parse('{!!json_encode(array_values($orderList)) !!}');
        var shippedListValues = JSON.parse('{!!json_encode(array_values($shippedList)) !!}');

        var ctxL = document.getElementById("lineChart").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: orderListKeys,
                datasets: [{
                    label: "Initialized Orders",
                    data: orderListValues,
                    backgroundColor: [
                        'rgba(105, 0, 132, .2)',
                    ],
                    borderColor: [
                        'rgba(200, 99, 132, .7)',
                    ],
                    fill: false,
                    borderWidth: 2,
                    stepped: false
                },
                    {
                        label: "Shipped Orders",
                        data: shippedListValues,
                        backgroundColor: [
                            'rgba(0, 137, 132, .2)',
                        ],
                        borderColor: [
                            'rgba(0, 10, 130, .7)',
                        ],
                        fill: false,
                        borderWidth: 2
                    }
                ]

            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        type: 'linear',
                        display: true,
                        position: 'left',
                        id: 'y-axis-1',
                    }, {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        id: 'y-axis-2',


                        gridLines: {
                            drawOnChartArea: false,
                        },
                    }],
                }
            }
        });
    </script>

    <!-- Bar Status-Line Chart -->
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling

        var paymentInfo = JSON.parse('{!!json_encode(array_keys($paymentInfo)) !!}');
        console.log(paymentInfo);
        var total = JSON.parse('{!!json_encode(array_values($total)) !!}');
        //console.log(paymentInfoValues[0][0]['total'] );
        console.log(paymentInfo);
        console.log(total);


        {{--var shippedListValues = JSON.parse('{!!json_encode(array_values($shippedList)) !!}');--}}

            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Bar Chart Example
        var ctx = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: paymentInfo,
                datasets: [{
                    label: "Revenue",
                    backgroundColor: "#46BFBD",
                    hoverBackgroundColor: "#46BFBD",
                    borderColor: "#46BFBD",
                    data: total,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 50000,
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });

    </script>
@endsection
