@extends("layouts.admin.app")

@php
  use App\Helpers\Helper;
@endphp

@section("content")
  <main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
      <div class="container-xl px-4">
        <div class="page-header-content pt-4">
          <div class="row align-items-center justify-content-between">
            <div class="col-auto mt-4">
              <h1 class="text-light d-flex h1 align-items-center gap-1">
                <i data-feather="activity"></i>
                Dashboard
              </h1>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
      <div class="row">
        <div class="col-xl-4 mb-4">
          <div class="card lift h-100">
            <div class="card-body d-flex justify-content-center flex-column position-relative">
              <div class="position-absolute" style="top: 1rem; right: 1rem">
                <div
                  class="text-xs fw-bold text-{{ $total_revenue["change"] == "increase" ? "success" : "danger" }} d-flex align-items-center">
                  @if ($total_revenue["change"] == "increase")
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-trending-up me-1">
                      <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                      <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-trending-down me-1">
                      <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                      <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                  @endif
                  <span class="fs-responsive">
                    {{ $total_revenue["percentage"] }}%
                  </span>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                  <i class="feather-xl text-primary mb-3" data-feather="dollar-sign"></i>
                  <h5>{{ Helper::formatCurrency($total_revenue["value"]) }}</h5>
                  <div class="text-muted small">Total pendapatan (bulan)</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 mb-4">
          <div class="card lift h-100">
            <div class="card-body d-flex justify-content-center flex-column position-relative">
              <div class="position-absolute" style="top: 1rem; right: 1rem">
                <div
                  class="text-xs fw-bold text-{{ $total_transactions["change"] == "increase" ? "success" : "danger" }} d-flex align-items-center">
                  @if ($total_transactions["change"] == "increase")
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-trending-up me-1">
                      <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                      <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-trending-down me-1">
                      <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                      <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                  @endif
                  <span class="fs-responsive">
                    {{ $total_transactions["percentage"] }}%
                  </span>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                  <i class="feather-xl text-secondary mb-3" data-feather="file-text"></i>
                  <h5>{{ $total_transactions["value"] }}</h5>
                  <div class="text-muted small">Total transaksi (bulan)</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 mb-4">
          <div class="card lift h-100">
            <div class="card-body d-flex justify-content-center flex-column position-relative">
              <div class="position-absolute" style="top: 1rem; right: 1rem">
                <div
                  class="text-xs fw-bold text-{{ $product_sold["change"] == "increase" ? "success" : "danger" }} d-flex align-items-center">
                  @if ($product_sold["change"] == "increase")
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-trending-up me-1">
                      <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                      <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" class="feather feather-trending-down me-1">
                      <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                      <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                  @endif
                  <span class="fs-responsive">
                    {{ $product_sold["percentage"] }}%
                  </span>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div class="me-3">
                  <i class="feather-xl text-green mb-3" data-feather="shopping-bag"></i>
                  <h5>{{ $product_sold["value"] }}</h5>
                  <div class="text-muted small">Produl terjual (bulan)</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card mb-4">
            <div class="card-header border-bottom">
              <!-- Dashboard card navigation-->
              <ul class="nav nav-tabs card-header-tabs" id="dashboardNav" role="tablist">
                <li class="nav-item me-1" role="presentation"><a class="nav-link active" id="revenue-pill"
                    href="#revenue" data-bs-toggle="tab" role="tab" aria-controls="revenue"
                    aria-selected="true">Pendapatan</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="transaction-pill"
                    href="#transaction" data-bs-toggle="tab" role="tab" aria-controls="transaction"
                    aria-selected="false" tabindex="-1">Transaksi</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="product_sold-pill"
                    href="#product_sold" data-bs-toggle="tab" role="tab" aria-controls="product_sold"
                    aria-selected="false" tabindex="-1">Produk terjual</a></li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="dashboardNavContent">
                <!-- Dashboard Tab Pane 1-->
                <div class="tab-pane fade show active" id="revenue" role="tabpanel" aria-labelledby="revenue-pill">
                  <div class="chart-area mb-4 mb-lg-0" style="height: 20rem">
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                      </div>
                    </div><canvas id="myAreaChartRevenue" width="546" height="213"
                      style="display: block; height: 320px; width: 819px;" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
                <!-- Dashboard Tab Pane 2-->
                <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-pill">
                  <div class="chart-area mb-4 mb-lg-0" style="height: 20rem">
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                      </div>
                    </div><canvas id="myAreaChartTransaction" width="546" height="213"
                      style="display: block; height: 320px; width: 819px;" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
                <!-- Dashboard Tab Pane 3-->
                <div class="tab-pane fade" id="product_sold" role="tabpanel" aria-labelledby="product_sold-pill">
                  <div class="chart-area mb-4 mb-lg-0" style="height: 20rem">
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                      </div>
                    </div><canvas id="myAreaChartProductSold" width="546" height="213"
                      style="display: block; height: 320px; width: 819px;" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection


@push("scripts")
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
  <script>
    document.addEventListener('alpine:init', () => {
      // Set new default font family and font color to mimic Bootstrap's default styling
      (Chart.defaults.global.defaultFontFamily = "Metropolis"),
      '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
      Chart.defaults.global.defaultFontColor = "#858796";


      // chart revenue
      var ctx = document.getElementById("myAreaChartRevenue");
      var myLineChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: @json($charts["revenue"]["labels"]),
          datasets: [{
            label: "Pendapatan",
            lineTension: 0.3,
            backgroundColor: "rgba(0, 97, 242, 0.05)",
            borderColor: "rgba(0, 97, 242, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(0, 97, 242, 1)",
            pointBorderColor: "rgba(0, 97, 242, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
            pointHoverBorderColor: "rgba(0, 97, 242, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: @json($charts["revenue"]["data"])
          }]
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
                unit: "date"
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks: {
                maxTicksLimit: 7
              }
            }],
            yAxes: [{
              ticks: {
                maxTicksLimit: 5,
                padding: 10,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return Alpine.store('globalState').formatPrice(value);
                }
              },
              gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel =
                  chart.datasets[tooltipItem.datasetIndex].label || "";
                return datasetLabel + ": " + Alpine.store('globalState').formatPrice(tooltipItem.yLabel);
              }
            }
          }
        }
      });

      // chart transaction
      var ctx = document.getElementById("myAreaChartTransaction");
      var myLineChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: @json($charts["transaction"]["labels"]),
          datasets: [{
            label: "Transaksi",
            lineTension: 0.3,
            backgroundColor: "rgba(0, 97, 242, 0.05)",
            borderColor: "rgba(0, 97, 242, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(0, 97, 242, 1)",
            pointBorderColor: "rgba(0, 97, 242, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
            pointHoverBorderColor: "rgba(0, 97, 242, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: @json($charts["transaction"]["data"])
          }]
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
                unit: "date"
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks: {
                maxTicksLimit: 7
              }
            }],
            yAxes: [{
              ticks: {
                maxTicksLimit: 5,
                padding: 10,
              },
              gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel =
                  chart.datasets[tooltipItem.datasetIndex].label || "";
                return datasetLabel + ": " + tooltipItem.yLabel;
              }
            }
          }
        }
      });

      // chart product sold
      var ctx = document.getElementById("myAreaChartProductSold");
      var myLineChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: @json($charts["product_sold"]["labels"]),
          datasets: [{
            label: "Transaksi",
            lineTension: 0.3,
            backgroundColor: "rgba(0, 97, 242, 0.05)",
            borderColor: "rgba(0, 97, 242, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(0, 97, 242, 1)",
            pointBorderColor: "rgba(0, 97, 242, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
            pointHoverBorderColor: "rgba(0, 97, 242, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: @json($charts["product_sold"]["data"])
          }]
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
                unit: "date"
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks: {
                maxTicksLimit: 7
              }
            }],
            yAxes: [{
              ticks: {
                maxTicksLimit: 5,
                padding: 10,
              },
              gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel =
                  chart.datasets[tooltipItem.datasetIndex].label || "";
                return datasetLabel + ": " + tooltipItem.yLabel;
              }
            }
          }
        }
      });
    })
  </script>
@endpush
