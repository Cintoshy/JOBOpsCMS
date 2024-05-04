@extends('layouts.template')

@section('content')

  <!-- page content -->
  <div class="right_col" role="main">
    <!-- top tiles -->

    <div class="row" style="display: inline-block;" >
    <div class="tile_count">
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
        <div class="count">{{ $totalUsers }}</div>
        <span class="count_bottom"><i class="green">4% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-book"></i> Total Tickets</span>
        <div class="count">{{ $totalTickets }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-folder-open"></i> Open Tickets</span>
        <div class="count">{{ $allOpen }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-line-chart"></i> In Progress</span>
        <div class="count">{{ $allInProgress }}</div>
        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-folder"></i> Closed Tickets</span>
        <div class="count">{{ $allClosed }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-flag"></i> High Priority</span>
        <div class="count">{{ $allHigh }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-minus-square"></i> Mid Priority</span>
        <div class="count">{{ $allMid }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-sort-amount-asc"></i> Low Priority</span>
        <div class="count">{{ $allLow }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-times"></i> Unassigned Tickets</span>
        <div class="count">{{ $unassignedTickets }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-check-square-o"></i> For Approval Users</span>
        <div class="count">{{ $forApproval }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
      <div class="col-md-2 col-sm-4  tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Assigned to Me</span>
        <div class="count">{{ $assignedTickets }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
      </div>
    </div>
  </div>

  <!-- /top tiles -->
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="dashboard_graph">

        <div class="row x_title">
          <div class="col-md-6">
            <!-- <h3>Ticket Summary</h3> -->
            <h3>Ticket Summary <small>Yearly requests</small></h3>
          </div>
        </div>

        <div class="col-md-9 col-sm-9 ">
          <canvas id="ticketsChart" width="400" height="150"  style="height:280px"></canvas>
        </div>
        <div class="col-md-3 col-sm-3 bg-white">
            <div>
              <div class="x_title align-items-center">
                <h2><i class="fa fa-users"></i> Pending User Approvals</h2>
                <div class="clearfix"></div>
              </div>
              <ul class="list-unstyled top_profiles scroll-view">
  
              <style>
                .avatar-img {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    padding: 5px; /* Adjust as needed */
                }
              </style>            
              @forelse ($unapprovedUsers as $user)
                <li class="media event d-flex align-items-center">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}'s avatar" style="width: 55px; height: 55px; border-radius: 50%; padding: 5px;">
                        
                    @else
                        <a class="pull-left border-green profile_thumb">
                        <i class="fa fa-user green" aria-hidden="true"></i> <!-- Font Awesome icon -->
                        </a>
                        
                    @endif
                    <div class="media-body" style="margin-left: 10px;">
                        <a class="title" href="{{ route('user.edit', $user->id) }}">{{ $user->name }}</a>
                        <p>{{ $user->job_position }} </p>
                        <p> <small>{{ $user->created_at }}</small></p>
                    </div>
                  </li>
                  
                  <hr>
                @empty
                  <li>No unapproved users found.</li>
                @endforelse
              </ul>
            </div>
            </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  <br />
  <!-- other rows here -->
</div>
<!-- /page content -->

<script src="{{ asset('vendors/Chart.js/dist/Chart.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('ticketsChart').getContext('2d');
        var ticketsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [@foreach($monthlyTicketsData as $data) '{{ $data->year }}-{{ $data->month }}', @endforeach],
                datasets: [{
                    label: 'Monthly Ticket Counts',
                    data: [@foreach($monthlyTicketsData as $data) {{ $data->count }}, @endforeach],
                    backgroundColor: 'rgba(38, 185, 154, 0.31)',
                    borderColor: 'rgba(38, 185, 154, 0.7)',
                    pointBorderColor: 'rgba(38, 185, 154, 0.7)',
                    pointBackgroundColor: 'rgba(38, 185, 154, 0.7)',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(220,220,220,1)',
                    pointBorderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        });
    });
</script>
@endsection
