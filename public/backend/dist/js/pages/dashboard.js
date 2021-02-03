/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

 $(function () {

 	'use strict';

 	$(document).on("click",".view-chart-avt",function(){
 		var action = $(this).attr("data-action");
 		var title = $(this).attr("data-title");
 		$.ajax({
 			url: action,
 			type: "get",
 			dataType: "json",
 			beforeSend:function(){
 				blockUI();
 				$('.common-modal').modal('show');
 				$('.common-modal .modal-body').html("Loading...");
 				$('.common-modal .modal-title').html(title);
 				$('.common-modal .modal-body').html('');
 			},
 			success: function (data) {
 				$.unblockUI();
 				$('.common-modal .modal-body').html('<div class="chart tab-pane active" id="avt-user-chart" style="position: relative; height: 300px;"></div>');
 				Morris.Bar({
 					element: 'avt-user-chart',
 					barSizeRatio:0.1,
 					/*data:  data,
 					xkey: 'month',
 					ykeys: ['value'],
 					lineColors: ['#75a5c1'],
 					hideHover: 'auto',
 					labels: ['AVT'],
 					fillOpacity: 0.6,
 					pointStrokeColors: ['black'],
 					lineColors:['red']*/
 					data: data,
 					xkey: 'month',
 					ykeys: ['value'],
 					labels: ['AVT'],
 					fillOpacity: 0.6,
 				});
 			}
 		});
 	});

 	$(document).on("click",".view-chart-apt",function(){
 		var action = $(this).attr("data-action");
 		var title = $(this).attr("data-title");
 		$.ajax({
 			url: action,
 			type: "get",
 			dataType: "json",
 			beforeSend:function(){
 				blockUI();
 				$('.common-modal').modal('show');
 				$('.common-modal .modal-body').html("Loading...");
 				$('.common-modal .modal-title').html(title);
 				$('.common-modal .modal-body').html('');
 			},
 			success: function (data) {
 				$.unblockUI();

 				$('.common-modal .modal-body').html('<div class="chart tab-pane active" id="apt-user-chart" style="position: relative; height: 300px;"></div>');
 				Morris.Bar({
 					element: 'apt-user-chart',
 					barSizeRatio:0.3,
 					/*data:  data,
 					xkey: ['Lead2k1','k12priority','priority2sold','k12sold'],
 					ykeys: ['lead2k1_value','k12priority_value','priority2sold_value','k12sold_value'],
 					lineColors: ['#75a5c1'],
 					hideHover: 'auto',
 					labels: ['APT'],
 					fillOpacity: 0.6,
 					pointStrokeColors: ['black'],
 					lineColors:['red']*/
 					data: data,
 					xkey: 'y',
 					ykeys: ['a'],
 					labels: ['APT'],
 					fillOpacity: 0.6,
 				});
 			}
 		});
 	});

 	$(document).on("click",".view-chart-acr",function(){
 		var action = $(this).attr("data-action");
 		var title = $(this).attr("data-title");
 		$.ajax({
 			url: action,
 			type: "get",
 			dataType: "json",
 			beforeSend:function(){
 				blockUI();
 				$('.common-modal').modal('show');
 				$('.common-modal .modal-body').html("Loading...");
 				$('.common-modal .modal-title').html(title);
 				$('.common-modal .modal-body').html('');
 			},
 			success: function (data) {
 				$.unblockUI();

 				$('.common-modal .modal-body').html('<div class="chart tab-pane active" id="acr-user-chart" style="position: relative; height: 300px;"></div>');
 				Morris.Area({
 					element: 'acr-user-chart',
 					data:  data,
 					xkey: ['k1_count','k1_priority_ratio','priority_count','priority_sold_ratio','sold_count'],
 					ykeys: ['k1_count_value','k1_priority_ratio_value','priority_count_value','priority_sold_ratio_value','sold_count_value'],
 					lineColors: ['#75a5c1'],
 					hideHover: 'auto',
 					labels: ['AVT'],
 					fillOpacity: 0.6,
 					pointStrokeColors: ['black'],
 					lineColors:['red']
 				});
 			}
 		});
 	});

  /*var dataArea = [
  { year: '2019-07', value: 0 },
  { year: '2019-08', value: 5 },
  { year: '2019-9', value: 2 },
  { year: '2019-10', value: 0 },
  { year: '2019-11', value: 5 }];

  var months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

  var area = new Morris.Area({
    element: 'revenue-chart',
    xkey: 'year',
    ykeys: ['value'],
    lineColors: ['#75a5c1'],
    hideHover: 'auto',
    labels: ['Sales'],
    data: dataArea,
    resize: true,
    xLabelAngle: 90,
    parseTime: false,
    xLabelFormat: function (x) {
      return months[parseInt(x.label.slice(5))];
    }
});*/



});
