$(document).ready(function () {
    if($('.datatable-1').length>0){
        $('.datatable-1').dataTable({
		  "iDisplayLength": 50
		});
        $('.dataTables_paginate').addClass('btn-group datatable-pagination');
        $('.dataTables_paginate > a').wrapInner('<span />');
        $('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
        $('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
    
        $( '.slider-range').slider({
			    range: true,
			    min: 0,
			    max: 20000,
			    values: [ 3000, 12000 ],			
			    slide: function(event, ui) {
				    $(this).find('.ui-slider-handle').attr('title', ui.value);
			    },
	    });
	
        $( '#amount' ).val( '$' + $( '.slider-range' ).slider( 'values', 0 ) + ' - $' + $( '.slider-range' ).slider( 'values', 1 ) );
    

    //Graph/Chart index.html

    var d1 = [ [0, 1], [1, 14], [2, 5], [3, 4], [4, 5], [5, 1], [6, 14], [7, 5],  [8, 5] ];
    var d2 = [ [0, 5], [1, 2], [2, 10], [3, 1], [4, 9],  [5, 5], [6, 2], [7, 10], [8, 8] ];

		/*var plot = $.plot($('#placeholder2'),
			   [ { data: d1, label: 'Profits'}, { data: d2, label: 'Expenses' } ], {
					lines: {
						show: true,
						fill: true, 
						lineWidth: 2
					},
					points: {
						show: true,
						lineWidth: 5
					},
					grid: {
						clickable: true,
						hoverable: true,
						autoHighlight: true,
						mouseActiveRadius: 10,
						aboveData: true,
						backgroundColor: '#fff',
						borderWidth: 0,
						minBorderMargin: 25,
					},
					colors: [ '#55f3c0', '#0db37e',  '#b4fae3', '#e0d1cb'],
					shadowSize: 0
				 });
*/
		function showTooltip(x, y, contents) {
			$('<div id="gridtip">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 5
			}).appendTo('body').fadeIn(300);
		}

		var previousPoint = null;
		$('#placeholder2').bind('plothover', function (event, pos, item) {
			$('#x').text(pos.x.toFixed(2));
			$('#y').text(pos.y.toFixed(2));

			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;

					$('#gridtip').remove();
					var x = item.datapoint[0].toFixed(0),
						y = item.datapoint[1].toFixed(0);

					showTooltip(item.pageX, item.pageY,
								'x : ' + x + '&nbsp;&nbsp;&nbsp; y : ' + y);
				}
			}
			else {
				$('#gridtip').remove();
				previousPoint = null;
			}
		});
    }

    else
    {
        var d1 = [ [0, 1], [1, 14], [2, 5], [3, 4], [4, 5], [5, 1], [6, 14], [7, 5],  [8, 5] ];
		var d2 = [ [0, 5], [1, 2], [2, 10], [3, 1], [4, 9],  [5, 5], [6, 2], [7, 10], [8, 8] ];

		/*var plot = $.plot($("#placeholder"), 
		[ { data: d1, label: "Data A" }, { data: d2, label: "Data B" } ], {
			lines: { 
				show: true, 
				fill: false, 
				lineWidth: 2 
			},
			points: { 
				show: true, 
				lineWidth: 5 
			},
			grid: {
				clickable: true,
				hoverable: true,
				autoHighlight: true,
				mouseActiveRadius: 10,
				aboveData: true,
				backgroundColor: "#fafafa",
				borderWidth: 0,
				minBorderMargin: 25,
			},
			colors: [ "#090", "#099",  "#609", "#900"],
			shadowSize: 0
		});*/

        var d1 = [ [0, 1], [1, 14], [2, 5], [3, 4], [4, 5], [5, 1], [6, 14], [7, 5],  [8, 5] ];
		var d2 = [ [0, 5], [1, 2], [2, 10], [3, 1], [4, 9],  [5, 5], [6, 2], [7, 10], [8, 8] ];

		/*var plot = $.plot($("#placeholder2"),
			   [ { data: d1, label: "Data Y"}, { data: d2, label: "Data X" } ], {
					lines: { 
						show: true, 
						fill: true, 
						lineWidth: 2 
					},
					points: { 
						show: true, 
						lineWidth: 5 
					},
					grid: {
						clickable: true,
						hoverable: true,
						autoHighlight: true,
						mouseActiveRadius: 10,
						aboveData: true,
						backgroundColor: "#fafafa",
						borderWidth: 0,
						minBorderMargin: 25,
					},
					colors: [ "#090", "#099",  "#609", "#900"],
					shadowSize: 0
				 });*/

		function showTooltip(x, y, contents) {
			$('<div id="gridtip">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 5
			}).appendTo("body").fadeIn(300);
		}

		var previousPoint = null;
		$("#placeholder2").bind("plothover", function (event, pos, item) {
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));

			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;
					
					$("#gridtip").remove();
					var x = item.datapoint[0].toFixed(0),
						y = item.datapoint[1].toFixed(0);
					
					showTooltip(item.pageX, item.pageY,
								"x : " + x + "&nbsp;&nbsp;&nbsp; y : " + y);
				}
			}
			else {
				$("#gridtip").remove();
				previousPoint = null;            
			}
		});

          // PREDEFINED DATA
        var data = [
						{ label: "Series1", data: [[1, 10]] },
						{ label: "Series2", data: [[1, 30]] },
						{ label: "Series3", data: [[1, 90]] }
					];

        // DEFAULT
       /* $.plot($("#pie-default"), data,
		{
		    series: {
		        pie: {
		            show: true
		        }
		    }
		});*/

        // DEFINE ACTIONS FOR pieHover & pieClick
        function pieHover(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            $("#hover").html('<span>' + obj.series.label + ' - ' + percent + '%</span>');
        }

        function pieClick(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            alert('' + obj.series.label + ': ' + percent + '%');
        }

        // DONUT
       /* $.plot($("#pie-donut"), data,
		{
		    series: {
		        pie: {
		            innerRadius: 50,
		            show: true
		        }
		    }
		});

        // DONUT + INTERACTIVE
        $.plot($("#pie-interactive"), data,
		{
		    series: {
		        pie: {
		            innerRadius: 50,
		            show: true
		        }
		    },
		    grid: {
		        hoverable: true,
		        clickable: true
		    }
		});
*/
        $("#pie-interactive").bind("plothover", pieHover);
        $("#pie-interactive").bind("plotclick", pieClick);
    }
});


//document.addEventListener("DOMContentLoaded", init, false);
$(document).ready(function(){

	$(document).on('change','.files',function(e){	
			
	},handleFileSelect);
	
	
	function handleFileSelect(e) {

		
		var class_val=e.currentTarget.parentNode.parentNode.childNodes[3].className;

		if(!e.target.files || !window.FileReader) return;
		
		
		
		var files = e.target.files;
		var filesArr = Array.prototype.slice.call(files);
		filesArr.forEach(function(f) {
			if(!f.type.match("image.*")) {
				return;
			}
			//alert(f.size);
			if(f.size>2 * 1024 * 1024){
				sweetAlert("Oops...", "Image size should be less than 2MB", "error");
				return;
			}
	
			var reader = new FileReader();
			
			reader.onload = function (g) {

				var image = new Image();flag=0;
			    image.src = g.target.result;

			    image.onload = function() {
			       
			        // access image size here 
			        console.log(this.width);
			        if(this.width<771){
			        	sweetAlert("Oops...", "Image width should not be less than 771 px", "error");
						//alert('image width should not be less than 771 px');
						return;
					}
					if(this.height<517){
						
						sweetAlert("Oops...", "Image height should not be less than 517 px", "error");
						return;
					}

					var html = "<span class=\"image_up\"><img src=\"" + g.target.result + "\"> <input class=\"edit_icon\" type=\"file\" name=\"files\" accept=\"image/*\"></span> <br clear=\"left\"/>";
					//alert(g.target.result);
					//e.currentTarget.parentNode.parentNode.childNodes[3].attr('id').append(html);
					e.currentTarget.parentNode.parentNode.childNodes[3].innerHTML='';	
					e.currentTarget.parentNode.parentNode.childNodes[3].innerHTML=html;	

			        
			    };


				//console.log(g.target.result);
									
			}
			reader.readAsDataURL(f); 
			
		});
	}

	$(document).on('change','.edit_icon',function(e){
		alert('files')
		var $this=$(this);
		e.addClass('asdas');
		//console.log(selDiv);	
		//handleFileSelect;
			
	},handleFileSelect_edit);
	
	function handleFileSelect_edit(e) {
		
		console.log(e.currentTarget.parentNode.parentNode);	
					   
		if(!e.target.files || !window.FileReader) return;
		
		
		
		var files = e.target.files;
		var filesArr = Array.prototype.slice.call(files);
		filesArr.forEach(function(f) {
			if(!f.type.match("image.*")) {
				return;
			}
			if(f.size>2 * 1024 * 1024){
				sweetAlert("Oops...", "Image size should be less than 2MB", "error");
				return;
			}
	
			var reader = new FileReader();
			reader.onload = function (g) {

				var image = new Image();flag=0;
			    image.src = g.target.result;
				
				image.onload = function() {

			        // access image size here 			     
			        if(this.width<771){
			        	sweetAlert("Oops...", "Image width should not be less than 771 px", "error");
						//alert('image width should not be less than 771 px');
						return;
					}
					if(this.height<517){
						
						sweetAlert("Oops...", "Image height should not be less than 517 px", "error");
						return;
					}

					var html = "<span class=\"image_up\"><img src=\"" + g.target.result + "\"> <input class=\"edit_icon\" type=\"file\" name=\"files\" accept=\"image/*\"></span> <br clear=\"left\"/>";
					//e.currentTarget.parentNode.parentNode.childNodes[3].attr('id').append(html);
					console.log(g.target);
					//alert(g.target.result);
					//e.currentTarget.parentNode.parentNode.innerHTML='';	
					e.currentTarget.parentNode.parentNode.innerHTML=html;	

			        
			    };


												
			}
			reader.readAsDataURL(f); 
			
		});
		
		
	}

})
	
	
	