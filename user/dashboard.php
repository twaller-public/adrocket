<?php
	require_once "_dashboard_ctrl.php";
	require_once "../assets/_header.php";
	
	//showme($campaigns[0]);
	//echo count($campaigns);
	
	
	//showme(checkForEmptyCampaign());
?>

<!-- main page content -->
<div class="container-fluid">
	<?php ?>
	
	<div class="row">
	
		<div class="col-lg-1"></div>
		

		
		
		
		<div class="col-lg-10 main-content dashboard" style="background-color:white;">
		
			
		
			
			<h1>Adrocket Dashboard</h1>
			<h3><?php echo $CURRENT_USER["fullname"]; ?></h3>
			<h4><?php echo date("l F jS, Y - g:i a", time());?></h4>
		
		
			<div style="color: #C00; font-weight: bold; font-size: 16px;">
				<?php echo $alerts; ?><br/>
			</div>
			
			
			
			<!-- Quick Links -->
			<div class="row">
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<label>
								<a href="/campaign/create/title.php">New Campaign</a>
							</label>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<label>
								<a href="/user/purchase_history.php">Purchase History</a>
							</label>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<label>
								<a href="/pages/learningcenter.php">Learning Center</a>
							</label>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<label>
								<a href="/pages/roi.php">ROI Calculator</a>
							</label>
						</div>
					</div>
				</div>
			
			
			</div>
			
			
			
			<!-- recent events -->
			<div class="row">
				
				<div class="col-sm-6">
			
					<h2>Account Overview</h2>
					
          <?php foreach($campaignAlerts as $alert): ?>
            <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $alert['text']; ?>
            </div>
          <?php endforeach; ?>
          
<!--
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Warning!</strong> <a href="#" class="alert-link">Campaign 000</a> Your most recent payment was declined.
					</div>
					
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Warning!</strong> <a href="#" class="alert-link">Campaign 123</a> will expire tomorrow
					</div>
					
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Success!</strong> <a href="#" class="alert-link">Campaign 456</a> was approved.
					</div>
					
					<div class="alert alert-info alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Alert!</strong> <a href="#" class="alert-link">Campaign 789</a> will begin tomorrow.
					</div>
-->
				</div>
			
			</div>
			
			
			
			<!-- campaigns quick view -->
			<div class="row quick-vals">
			
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<p>
								<a href="list.php?filter-campaign_status=4">
									<span class="qv-title" style="font-weight:bold; font-size:18px;">Active<br />Campaigns</span><br />
									<span class="qv-value" style="font-weight:bold; font-size:64px;"><?php echo $active_cpns; ?></span>
								</a>
							</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<p>
								<a href="list.php?filter-campaign_status=3">
									<span class="qv-title" style="font-weight:bold; font-size:18px;">Waiting<br />to Start</span><br />
									<span class="qv-value" style="font-weight:bold; font-size:64px;"><?php echo $waiting_cpns; ?></span>
								</a>
							</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<p>
								<a href="list.php?filter-campaign_status=1">
									<span class="qv-title" style="font-weight:bold; font-size:18px;">Under<br />Construction</span><br />
									<span class="qv-value" style="font-weight:bold; font-size:64px;"><?php echo $uconstr_cpns; ?></span>
								</a>
							</p>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<p>
								<a href="list.php">
									<span class="qv-title" style="font-weight:bold; font-size:18px;">All<br />Campaigns</span><br />
									<span class="qv-value" style="font-weight:bold; font-size:64px;"><?php echo $total_cpns; ?></span>
								</a>
							</p>
						</div>
					</div>
				</div>
			
			</div>
			
      <div class="row" style="margin-bottom:30px;">
          <div class="col-md-12">
            <h2>Campaign Activity Chart</h2>
          </div>
          
          <?php

          $displayData = array();
          foreach($gData as $c  => $cData) {
            $displayData[$c] = array();
            foreach($cData as $d => $dData) {
              $displayData[$c][] = array("$d", $dData['clicks'], $dData['impressions']);
            }
            
          }

          ?>
          
          <?php if(@$displayData['all']): ?>
          <div id="chart_menu">
            <select name="chartCampaign" id="chartCampaign">
              <option value="All">All</option>
              <?php foreach($campaigns as $index=>$c): ?>
                <?php $campaign = @$c[0];
                      $cNum = $campaign->get("num");
                      $cTitle = $campaign->get("title");
                ?>
                <?php if(@$displayData[$cNum]): ?>
                  <option value="<?php echo $cNum; ?>"><?php echo $cTitle; ?></option>
                <?php elseif(@$cTitle): ?>
                  <option disabled="disabled"><?php echo $cTitle; ?> - Graph data not available yet.</option>

                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div id="chart_wrapper">
            <div id='chart_div' style='width: 70% !important'></div>
          </div>
          
          <?php else: ?>
          <p>No data to graph yet</p>
          
          <?php endif; ?>

          
      </div>
<?php
//			<!-- Example Chart -->
//			<div class="row" style="margin-bottom:30px;">
//				<div class="col-md-12">
//					<h2>Campaign Activity Chart</h2>
//					
//					
//					<!-- Nav tabs -->
//					<ul class="nav nav-tabs">
//						
//						<li class="active"><a href="#Search" data-toggle="tab">Google Search</a></li>
//						<li><a href="#Display" data-toggle="tab">Google Display</a></li>
//						<li><a href="#Remarket" data-toggle="tab">Google Remarketing</a></li>
//						<li><a href="#Statuses" data-toggle="tab">Campaign Statuses</a></li>
//					</ul>
//
//					<!-- Tab panes -->
//					<div class="tab-content">
//						
//						
//						
//						<div class="tab-pane active" id="Search">
//							<div class="chart-container" style="position: relative; height:auto; width:100%">
//								<canvas id="chartSearch"></canvas>
//							</div>
//						</div>
//						
//						
//						<div class="tab-pane" id="Display">
//							<div class="chart-container" style="position: relative; height:auto; width:100%">
//								<canvas id="chartDisplay"></canvas>
//							</div>
//						</div>
//						
//						
//						<div class="tab-pane" id="Remarket">
//							<div class="chart-container" style="position: relative; height:auto; width:100%">
//								<canvas id="chartRemarket"></canvas>
//							</div>
//						</div>
//						
//						
//						<div class="tab-pane" id="Statuses">
//							<div class="chart-container" style="position: relative; height:auto; width:100%">
//								<canvas id="chart"></canvas>
//							</div>
//						</div>
//					</div>
//					
//					
//					
//					
//					
//				</div>
//			</div>
//			
//			
?>
			
			
			
		</div>
		
		<!--<div class="col-sm-2 right-sidebar"></div>-->
	</div>
</div>

<?php require_once "../assets/_footer.php"; ?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>




<?php if(@$displayData['all']): ?>
  <script>
    
  var chartAll =  <?php echo json_encode($displayData['all']); ?>;
  <?php foreach($campaignNums as $cNum): ?>
    <?php if(@$displayData[$cNum]): ?>
      var chart<?php echo $cNum; ?> =  <?php echo json_encode($displayData[$cNum]); ?>;
    <?php endif; ?>
  <?php endforeach; ?>
  
  var currentChart = chartAll;
  
    
  function resetGraph() {
  
  
  
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawLineColors);
    
    function drawLineColors() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'X');
      data.addColumn('number', 'Clicks');
      data.addColumn('number', 'Impressions');
    
      data.addRows(currentChart);
    
      var options = {
        hAxis: {
          title: 'Month'
        },
      series: {
        1: {
          targetAxisIndex: 1
        }
      },
      vAxes: {
        0: {
          title: 'Clicks',
        },
        1: {
         title: 'Impressions'
        }
      },

        colors: ['#a52714', '#097138'],
        legend: {position: 'top'},
        pointSize: 10
      };
    
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    
    
  }
  
  $(function() {
    resetGraph();
    $('#chartCampaign').change(function() {
      $('#chart_div').remove();
      $('#chart_wrapper').append("<div id='chart_div' style='width: 70% !important'></div>");
      currentChart = window['chart'+$('#chartCampaign').val()];
      resetGraph();
    });
  });
  
  </script>

<?php endif; ?>










<script>





//$(function(){
//	
//	$('.collapse').collapse();
//	
//	$('#campaign-panels').show("slow");
//	
//
//	var ctx = document.getElementById("chart");
//	var myChart = new Chart(ctx, {
//		type: 'line',
//		data: {
//			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
//			datasets: [
//				{
//					label: 'In Progress',
//					data: [12, 19, 3, 5, 2, 3],
//					backgroundColor: [
//						'rgba(255, 99, 132, 0.1)',
//					],
//					borderColor: [
//						'rgba(255,99,132,1)',
//					],
//					borderWidth: 1
//				},
//				{
//					label: 'Waiting',
//					data: [0, 0, 12, 10, 2, 1],
//					backgroundColor: [
//						'rgba(54, 162, 235, 0.1)',
//					],
//					borderColor: [
//						'rgba(54, 162, 235, 1)',
//					],
//					borderWidth: 1
//				},
//				{
//					label: 'Active',
//					data: [0, 1, 7, 9, 9, 12],
//					backgroundColor: [
//						'rgba(255, 206, 86, 0.1)',
//					],
//					borderColor: [
//						'rgba(255, 206, 86, 1)',
//					],
//					borderWidth: 1
//				},
//				{
//					label: 'Paused',
//					data: [1, 1, 0, 1, 1, 1],
//					backgroundColor: [
//						'rgba(75, 192, 192, 0.1)',
//					],
//					borderColor: [
//						'rgba(75, 192, 192, 1)',
//					],
//					borderWidth: 1
//				}
//			]
//			
//		},
//		options: {
//			scales: {
//				yAxes: [{
//					ticks: {
//						beginAtZero:true
//					}
//				}]
//			}
//		}
//	});
//	
//	
//	var ctx2 = document.getElementById("chartSearch");
//	var myChart2 = new Chart(ctx2, {
//		type: 'line',
//		data: {
//			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
//			datasets: [
//				{
//					label: 'Total Clicks',
//					data: [100, 130, 67, 94, 111, 145],
//					backgroundColor: [
//						'rgba(255, 99, 132, 0.1)',
//					],
//					borderColor: [
//						'rgba(255,99,132,1)',
//					],
//					borderWidth: 1
//				},
//			]
//			
//		},
//		options: {
//			scales: {
//				yAxes: [{
//					ticks: {
//						beginAtZero:true
//					}
//				}]
//			}
//		}
//	});
//	
//	
//	var ctx3 = document.getElementById("chartDisplay");
//	var myChart3 = new Chart(ctx3, {
//		type: 'line',
//		data: {
//			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
//			datasets: [
//				{
//					label: 'Total Clicks',
//					data: [45, 89, 44, 123, 145, 110],
//					backgroundColor: [
//						'rgba(54, 162, 235, 0.1)',
//					],
//					borderColor: [
//						'rgba(54, 162, 235, 1)',
//					],
//					borderWidth: 1
//				},
//			]
//			
//		},
//		options: {
//			scales: {
//				yAxes: [{
//					ticks: {
//						beginAtZero:true
//					}
//				}]
//			}
//		}
//	});
//	
//	
//	
//	var ctx4 = document.getElementById("chartRemarket");
//	var myChart4 = new Chart(ctx4, {
//		type: 'line',
//		data: {
//			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
//			datasets: [
//				{
//					label: 'Total Clicks',
//					data: [56, 90, 78, 130, 108, 112],
//					backgroundColor: [
//						'rgba(255, 206, 86, 0.1)',
//					],
//					borderColor: [
//						'rgba(255, 206, 86, 1)',
//					],
//					borderWidth: 1
//				},
//			]
//			
//		},
//		options: {
//			scales: {
//				yAxes: [{
//					ticks: {
//						beginAtZero:true
//					}
//				}]
//			}
//		}
//	});
//
//})
</script>































</body>
</html>