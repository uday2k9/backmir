/* ----------------- Start JS Document ----------------- */

// Page Loader
$(window).load(function () {
    "use strict";    
	$('#loader').fadeOut();
	$('body').removeClass('fixed_style');
});

$(document).ready(function ($) {
	"use strict";	
	////	Hidder Header
	$('body').addClass('fixed_style');
		
	var headerEle = function () {
		var $headerHeight = $('header').height();
		$('.hidden-header').css({ 'height' : $headerHeight  + "px" });
	};
	
	$(window).load(function () {
	    headerEle();
	});
	
	$(window).resize(function () {
	    headerEle();
	});
	
    
    /*---------------------------------------------------*/
    /* Progress Bar
    /*---------------------------------------------------*/    
    
    /*--------------------------------------------------*/
    /* Counter
    /*--------------------------------------------------*/   
        
    
	
	/*----------------------------------------------------*/
	/*	Nice-Scroll
	/*----------------------------------------------------*/
	
	
		
	/*----------------------------------------------------*/
	/*	Nav Menu & Search
	/*----------------------------------------------------*/
	
	$(".nav > li:has(ul)").addClass("drop");
	$(".nav > li.drop > ul").addClass("dropdown");
	$(".nav > li.drop > ul.dropdown ul").addClass("sup-dropdown");
	
	$('.show-search').click(function() {
		$('.search-form').fadeIn(300);
		$('.search-form input').focus();
	});
	$('.search-form input').blur(function() {
		$('.search-form').fadeOut(300);
	});
				
	/*----------------------------------------------------*/
	/*	Back Top Link
	/*----------------------------------------------------*/
	
    var offset = 200;
    var duration = 500;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.back-to-top').fadeIn(400);
        } else {
            $('.back-to-top').fadeOut(400);
        }
    });
    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 600);
        return false;
    })
		
	/*----------------------------------------------------*/
	/*	Sliders & Carousel
	/*----------------------------------------------------*/
	
	////------- Touch Slider
	var time = 4.4,
		$progressBar,
		$bar,
		$elem,
		isPause,
		tick,
		percentTime;
	$('.touch-slider').each(function(){
		var owl = jQuery(this),
			sliderNav = $(this).attr('data-slider-navigation'),
			sliderPag = $(this).attr('data-slider-pagination'),
			sliderProgressBar = $(this).attr('data-slider-progress-bar');
			
		if ( sliderNav == 'false' || sliderNav == '0' ) {
			var returnSliderNav = false
		}else {
			var returnSliderNav = true
		}
		
		if ( sliderPag == 'true' || sliderPag == '1' ) {
			var returnSliderPag = true
		}else {
			var returnSliderPag = false
		}
		
		if ( sliderProgressBar == 'true' || sliderProgressBar == '1' ) {
			var returnSliderProgressBar = progressBar
			var returnAutoPlay = false
		}else {
			var returnSliderProgressBar = false
			var returnAutoPlay = true
		}
		
		owl.owlCarousel({
			navigation : returnSliderNav,
			pagination: returnSliderPag,
			slideSpeed : 400,
			paginationSpeed : 400,
			lazyLoad : true,
			singleItem: true,
			autoHeight : true,
			autoPlay: returnAutoPlay,
			stopOnHover: returnAutoPlay,
			transitionStyle : "fade",
			afterInit : returnSliderProgressBar,
			startDragging : pauseOnDragging
		});
		
	});

    function progressBar(elem){
		$elem = elem;
		buildProgressBar();
		start();
    }
	
    function buildProgressBar(){
		$progressBar = $("<div>",{
			id:"progressBar"
		});
		$bar = $("<div>",{
			id:"bar"
		});
		$progressBar.append($bar).prependTo($elem);
    }
	
    function pauseOnDragging(){
      isPause = true;
    }
	
	////------- Projects Carousel
	
	
	////------- Custom Carousel
	$('.custom-carousel').each(function(){
		var owl = jQuery(this),
			itemsNum = $(this).attr('data-appeared-items'),
			sliderNavigation = $(this).attr('data-navigation');
			
		if ( sliderNavigation == 'false' || sliderNavigation == '0' ) {
			var returnSliderNavigation = false
		}else {
			var returnSliderNavigation = true
		}
		if( itemsNum == 1) {
			var deskitemsNum = 1;
			var desksmallitemsNum = 1;
			var tabletitemsNum = 1;
		} 
		else if (itemsNum >= 2 && itemsNum < 4) {
			var deskitemsNum = itemsNum;
			var desksmallitemsNum = itemsNum - 1;
			var tabletitemsNum = itemsNum - 1;
		} 
		else if (itemsNum >= 4 && itemsNum < 8) {
			var deskitemsNum = itemsNum -1;
			var desksmallitemsNum = itemsNum - 2;
			var tabletitemsNum = itemsNum - 3;
		} 
		else {
			var deskitemsNum = itemsNum -3;
			var desksmallitemsNum = itemsNum - 6;
			var tabletitemsNum = itemsNum - 8;
		}
		owl.owlCarousel({
			slideSpeed : 300,
			stopOnHover: true,
			autoPlay: false,
			navigation : returnSliderNavigation,
			pagination: false,
			lazyLoad : true,
			items : itemsNum,
			itemsDesktop : [1000,deskitemsNum],
			itemsDesktopSmall : [900,desksmallitemsNum],
			itemsTablet: [600,tabletitemsNum],
			itemsMobile : false,
			transitionStyle : "goDown",
		});
	});
	
    
    
    ////------- Testimonials Carousel
	
	
	
	/*----------------------------------------------------*/
	/*	Tabs
	/*----------------------------------------------------*/
	
	$('#myTab a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	})
	
	
	
	
	/*----------------------------------------------------*/
	/*	Css3 Transition
	/*----------------------------------------------------*/
	
	$('*').each(function(){
		if($(this).attr('data-animation')) {
			var $animationName = $(this).attr('data-animation'),
				$animationDelay = "delay-"+$(this).attr('data-animation-delay');
			$(this).appear(function() {
				$(this).addClass('animated').addClass($animationName);
				$(this).addClass('animated').addClass($animationDelay);
			});
		}
	});
	
	
	
	
	/*----------------------------------------------------*/
	/*	Pie Charts
	/*----------------------------------------------------*/
	
	var pieChartClass = 'pieChart',
        pieChartLoadedClass = 'pie-chart-loaded';
		
	function initPieCharts() {
		var chart = $('.' + pieChartClass);
		chart.each(function() {
			$(this).appear(function() {
				var $this = $(this),
					chartBarColor = ($this.data('bar-color')) ? $this.data('bar-color') : "#F54F36",
					chartBarWidth = ($this.data('bar-width')) ? ($this.data('bar-width')) : 150
				if( !$this.hasClass(pieChartLoadedClass) ) {
					$this.easyPieChart({
						animate: 2000,
						size: chartBarWidth,
						lineWidth: 2,
						scaleColor: false,
						trackColor: "#eee",
						barColor: chartBarColor,
					}).addClass(pieChartLoadedClass);
				}
			});
		});
	}
	initPieCharts();
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Animation Progress Bars
	/*----------------------------------------------------*/
	
	$("[data-progress-animation]").each(function() {
		
		var $this = $(this);
		
		$this.appear(function() {
			
			var delay = ($this.attr("data-appear-animation-delay") ? $this.attr("data-appear-animation-delay") : 1);
			
			if(delay > 1) $this.css("animation-delay", delay + "ms");
			
			setTimeout(function() { $this.animate({width: $this.attr("data-progress-animation")}, 800);}, delay);

		}, {accX: 0, accY: -50});

	});
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Milestone Counter
	/*----------------------------------------------------*/
	
	jQuery('.milestone-block').each(function() {
		jQuery(this).appear(function() {
			var $endNum = parseInt(jQuery(this).find('.milestone-number').text());
			jQuery(this).find('.milestone-number').countTo({
				from: 0,
				to: $endNum,
				speed: 4000,
				refreshInterval: 60,
			});
		},{accX: 0, accY: 0});
	});
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Nivo Lightbox
	/*----------------------------------------------------*/
	
	
	
	
	/*----------------------------------------------------*/
	/*	Tooltips & Fit Vids & Parallax & Text Animations
	/*----------------------------------------------------*/
	
	
	
	
	/*----------------------------------------------------*/
	/*	Sticky Header
	/*----------------------------------------------------*/
	
	(function() {
		
		var docElem = document.documentElement,
			didScroll = false,
			changeHeaderOn = 100;
			document.querySelector( 'header' );
			
		function init() {
			window.addEventListener( 'scroll', function() {
				if( !didScroll ) {
					didScroll = true;
					setTimeout( scrollPage, 250 );
				}
			}, false );
		}
		
		function scrollPage() {
			var sy = scrollY();
			if ( sy >= changeHeaderOn ) {
				$('.top-bar').slideUp(300);
				$("header").addClass("fixed-header");
				$('.navbar-brand').css({ 'padding-top' : 19 + "px", 'padding-bottom' : 19 + "px" });
				
				if (/iPhone|iPod|BlackBerry/i.test(navigator.userAgent) || $(window).width() < 479 ){
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 0 + "px", 'padding-bottom' : 0 + "px" })
				}else{
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 20 + "px", 'padding-bottom' : 20 + "px" })
					$('.search-side').css({ 'margin-top' : -7 + "px" });
				};
				
			}
			else {
				$('.top-bar').slideDown(300);
				$("header").removeClass("fixed-header");
				$('.navbar-brand').css({ 'padding-top' : 27 + "px", 'padding-bottom' : 27 + "px" });
				
				if (/iPhone|iPod|BlackBerry/i.test(navigator.userAgent) || $(window).width() < 479 ){
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 0 + "px", 'padding-bottom' : 0 + "px" })
				}else{
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 28 + "px", 'padding-bottom' : 28 + "px" })
					$('.search-side').css({ 'margin-top' : 0  + "px" });
				};
				
			}
			didScroll = false;
		}
		
		function scrollY() {
			return window.pageYOffset || docElem.scrollTop;
		}
		
		init();
		
		
		
	})();
});




/*----------------------------------------------------*/
/*	Portfolio Isotope
/*----------------------------------------------------*/

jQuery(window).load(function(){
	
	
	$('.portfolio-filter ul a').click(function(){
		var selector = $(this).attr('data-filter');
		$container.isotope({
			filter: selector,
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false,
			}
		});
	  return false;
	});

	var $optionSets = $('.portfolio-filter ul'),
	    $optionLinks = $optionSets.find('a');
	$optionLinks.click(function(){
		var $this = $(this);
		if ( $this.hasClass('selected') ) { return false; }
		var $optionSet = $this.parents('.portfolio-filter ul');
		$optionSet.find('.selected').removeClass('selected');
		$this.addClass('selected'); 
	});
	
});
/* ----------------- End JS Document ----------------- */








// Styles Switcher JS
function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
}

function getActiveStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
  }
  return null;
}

function getPreferredStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1
       && a.getAttribute("rel").indexOf("alt") == -1
       && a.getAttribute("title")
       ) return a.getAttribute("title");
  }
  return null;
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

window.onload = function(e) {
  var cookie = readCookie("style");
  var title = cookie ? cookie : getPreferredStyleSheet();
  setActiveStyleSheet(title);
}

window.onunload = function(e) {
  var title = getActiveStyleSheet();
  createCookie("style", title, 365);
}

var cookie = readCookie("style");
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);



$(document).ready(function(){
	
	// Styles Switcher
	$(document).ready(function(){
		$('.open-switcher').click(function(){
			if($(this).hasClass('show-switcher')) {
				$('.switcher-box').css({'left': 0});
				$('.open-switcher').removeClass('show-switcher');
				$('.open-switcher').addClass('hide-switcher');
			}else if(jQuery(this).hasClass('hide-switcher')) {
				$('.switcher-box').css({'left': '-212px'});
				$('.open-switcher').removeClass('hide-switcher');
				$('.open-switcher').addClass('show-switcher');
			}
		});
	});
	
	//Top Bar Switcher
	$(".topbar-style").change(function(){
		if( $(this).val() == 1){
			$(".top-bar").removeClass("dark-bar"),
			$(".top-bar").removeClass("color-bar"),
			$(window).resize();
		} else if( $(this).val() == 2){
			$(".top-bar").removeClass("color-bar"),
			$(".top-bar").addClass("dark-bar"),
			$(window).resize();
		} else if( $(this).val() == 3){
			$(".top-bar").removeClass("dark-bar"),
			$(".top-bar").addClass("color-bar"),
			$(window).resize();
		}
	});
	
	//Layout Switcher
	$(".layout-style").change(function(){
		if( $(this).val() == 1){
			$("#container").removeClass("boxed-page"),
			$(window).resize();
		} else{
			$("#container").addClass("boxed-page"),
			$(window).resize();
		}
	});
	
	//Background Switcher
	$('.switcher-box .bg-list li a').click(function() {
		var current = $('.switcher-box select[id=layout-style]').find('option:selected').val();
		if(current == '2') {
			var bg = $(this).css("backgroundImage");
			$("body").css("backgroundImage",bg);
		} else {
			alert('Please select boxed layout');
		}
	});

});

/**
 * Slick Nav 
 */

$('.wpb-mobile-menu').slicknav({
  prependTo: '.navbar-header',
  parentTag: 'MIRAMIX',
  allowParentLinks: true,
  duplicate: false,
  label: '',
  closedSymbol: '<i class="fa fa-angle-right"></i>',
  openedSymbol: '<i class="fa fa-angle-down"></i>',
});
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 50) {
        $("header").addClass("fixed-header");
    } else {
        $("header").removeClass("fixed-header");
    }
});



  var selDiv = "";
		


  // ++++++++++++++++++++++ For Brand Image +++++++++++++++++++++++++++++++

  $(document).on('change','.filesbrand',function(e){
		
	},handleFileSelectUser);
	/*function init() {
		if ($('.files').length > 0) {
		 document.querySelector('.files').addEventListener('change', handleFileSelect, false);
		}
		
		selDiv = document.querySelector(".selectedFiles");
	}*/
		
	function handleFileSelectUser(e) {

		//console.log(e.currentTarget.parentNode.parentNode.childNodes[3]);	
					   
		//console.log(e.currentTarget.parentNode.parentNode.childNodes[3].className);
		var class_val=e.currentTarget.parentNode.parentNode.childNodes[3].className;
		//selDiv=e.currentTarget.parentNode.parentNode.childNodes[3];
		if(!e.target.files || !window.FileReader) return;
		
		selDiv.innerHTML = "";
		
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
			        if(this.width<430){
			        	sweetAlert("Oops...", "Image width should not be less than 430 px", "error");
						//alert('image width should not be less than 771 px');
						return;
					}
					if(this.height<430){
						
						sweetAlert("Oops...", "Image height should not be less than 430 px", "error");
						return;
					}

					var html = "<span class=\"image_up\"><img src=\"" + g.target.result + "\"> <input class=\"edit_icon\" type=\"file\" name=\"files\" accept=\"image/*\"></span> <br clear=\"left\"/>";
					//e.currentTarget.parentNode.parentNode.childNodes[3].attr('id').append(html);
					e.currentTarget.parentNode.parentNode.childNodes[3].innerHTML='';	
					e.currentTarget.parentNode.parentNode.childNodes[3].innerHTML=html;	

			        
			    };


				//console.log(g.target.result);
									
			}
			reader.readAsDataURL(f); 
			
		});	
	}

  // ++++++++++++++++++++++ For Brand Image +++++++++++++++++++++++++++++++




	//document.addEventListener("DOMContentLoaded", init, false);
	$(document).on('change','.files',function(e){
		
			
	},handleFileSelect);
	/*function init() {
		if ($('.files').length > 0) {
		 document.querySelector('.files').addEventListener('change', handleFileSelect, false);
		}
		
		selDiv = document.querySelector(".selectedFiles");
	}*/
		
	function handleFileSelect(e) {

		//console.log(e.currentTarget.parentNode.parentNode.childNodes[3]);	
					   
		//console.log(e.currentTarget.parentNode.parentNode.childNodes[3].className);
		var class_val=e.currentTarget.parentNode.parentNode.childNodes[3].className;
		//selDiv=e.currentTarget.parentNode.parentNode.childNodes[3];
		if(!e.target.files || !window.FileReader) return;
		
		selDiv.innerHTML = "";
		
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
					   
		//console.log(e.currentTarget.parentNode.parentNode);
		//var class_val=e.currentTarget.parentNode.parentNode.childNodes[3].className;
		//selDiv=e.currentTarget.parentNode.parentNode.childNodes[3];
		if(!e.target.files || !window.FileReader) return;
		
		selDiv.innerHTML = "";
		
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
	
	var x=1;
  $(document).ready(function(e) {
	  //for removing a ingredient
  		
		
		
		//for adding new form factor
		/*$(document).on('click','.add_form',function(){
			$('#formfactortable').append('<tr class="form_factore_info"><td><select class="form-control"><option>Powder</option></select></td><td><input class="form-control" type="text"></td><td><select class="form-control"><option>Capsule</option></select></td><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td align="left" valign="middle" width="10%"><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a></td></tr>');
		});*/
		
		
		
		$(document).on("show.bs.collapse", ".form_ingredient_group .collapse_pan", function (event) {
			var $this=$(this);
			$this.parent().find('a.collapsing_btn').html('<i class="fa fa-minus-square"></i>');
		});
		$(document).on("hide.bs.collapse", ".form_ingredient_group .collapse_pan", function (event) {
			var $this=$(this);
			$this.parent().find('a.collapsing_btn').html('<i class="fa fa-plus-square"></i>');
		});  
  });
  
  $(document).on('blur','#routing_number',function(){
	 var $this=$(this);
	 if($this.val()==''){
		 
	 }
	 else{
		$('#banking_address1').trigger('click');
		//$('#banking_address1').attr('checked',true);
	 }
  });
  
  $(document).on('blur','#paypal_email',function(){
	 var $this=$(this);
	 if($this.val()==''){
		 
	 }
	 else{
		$('#banking_address2').trigger('click');
		//$('#banking_address1').attr('checked',true);
	 }
  });
  
  $(document).on('blur','#mailing_address',function(){
	 var $this=$(this);
	 if($this.val()==''){
		 
	 }
	 else{
		$('#banking_address3').trigger('click');
		//$('#banking_address1').attr('checked',true);
	 }
  });
  
  /*$(document).on('change','input[type="radio"]',function(){
	  //alert();
	 if($("#banking_address1").is(":checked")){
  		$('#paypal_email').val('');
		$('#mailing_address').val('');
	 }
	 else if($("#banking_address2").is(":checked")) {
		 $('#routing_number').val('');
		 $('#mailing_address').val('');
	 }
	 else if($("#banking_address3").is(":checked")){
		 $('#routing_number').val('');
		 $('#paypal_email').val('');
	 }
	 else{
		 
	 }
  });*/

 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).on('click','.acct_box',function(){
	var $this=$(this);
	var thishref=$this.find('.link_wholediv').attr('href');
	if(thishref=='#' || thishref=='javascript:void(0);' || thishref=='javascript:void();' || thishref=='javascript:void(0)'){}
	else
	window.location.href=thishref;
});

/********for dashboard Menu**********/
$(document).ready(function(e) {
   
   
   $(document).on('click','.mob_topmenu_back',function(){
	  $('.top_menu_port').toggleClass('activate_dash');
	  $('.mob_topmenu_back').fadeToggle();
	  $('#nav-icon2').toggleClass('open'); 
   });
    
});

$(document).ready(function(){
	$('#nav-icon2').click(function(){
		$(this).toggleClass('open');
		$('.top_menu_port').toggleClass('activate_dash');
	  	$('.mob_topmenu_back').fadeToggle();
		$('body').toggleClass('havefixed');
	});
});

//for menu close 
$(document).click(function(e) {
    if (!$(e.target).parents("header").size()) {
		//console.log(e.target);
        $('header .navbar-collapse').collapse('hide');        
    }
});

//for image section click redirection in mobile
$(document).ready(function(e) {
	if($(window).width()<768){
		/*$(document).on('click','.image_section',function(){
			var $this=$(this);
			var this_redirecturl=$this.find('.butt.butt-green:first-child').attr('href');
			window.location.href=this_redirecturl;
		});*/
		$(document).on('click','.product',function(){
			var $this=$(this);
			var this_redirecturl=$this.find('.butt.butt-green:first-child').attr('href');
			window.location.href=this_redirecturl;
		});
	}
});

//FOR CART SECTION IN MOBILE
$(document).ready(function(e) {
    $(document).on('click','.edit_cart_qty',function(){
		var $this=$(this);
		$this.parent().parent().parent().parent().find('.edit_qty_thisprod').slideToggle();
	});
	/*$(document).on('click','.remove_this_prod',function(){
		var $this=$(this);
		$this.parent().parent().parent().parent().fadeOut(function(){
			$(this).remove();
		});
	});*/
	
});



	

