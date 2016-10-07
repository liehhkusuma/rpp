$(window).load(function() {
   
   // Page Preloader
   $('#status').fadeOut();
   $('#preloader').delay(0).fadeOut(function(){
      $('body').delay(0).css({'overflow':'visible'});
   });
});


var Main = function(){return {

   datepicker : function(){
      /* Date Helper */
      if($.fn.datepicker != undefined){
         $('.datepicker').datepicker();
         $('.datepicker-inline').datepicker();
         $('.datepicker-multiple').datepicker({
            numberOfMonths: 3,
            showButtonPanel: true
         });
      }
   },

   tooltip : function(){
      // Tooltip
      if($.fn.tooltip != undefined){
         $('.tooltips').tooltip({ container: 'body'});
      }
   },

   popover : function(){
      // Popover
      if($.fn.popover != undefined){
         $('.popovers').popover();
      }
   },

   zclip : function(){
      /* ZClip Helper */
      if($.fn.zclip != undefined){
         $('.copy-btn').each(function(key){
            $val = $(this).data('copy');
            $(this).addClass('ZCLIP-'+key);
            $('.ZCLIP-'+key).zclip({
               path: path('bo.vendor')+"/zclip/ZeroClipboard.swf",
               copy: $val
            });
         });
      }
   },

   chosen : function(){
      /* Chosen Helper */
      if($.fn.chosen != undefined){
         $(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
      }
   },

   mask : function(){
      /* Masking Helper */
      if($.fn.mask != undefined){
         $(".date_mask").mask("99/99/9999");
         $(".phone_mask").mask("(999) 999-9999");
         $(".ssn_mask").mask("999-99-9999");
      }
   },

   ligthbox : function(){
      if($.fn.prettyPhoto){
         $(".ligthbox").prettyPhoto();
      }
   },

   fancybox : function(){
      if($.fn.fancybox){
         $(".fancybox").fancybox();
      }
   },

   theadfix : function(){
      setTimeout(function(){
         var $thead_fix = [];
         $(".theadfix").each(function(key){
            var $top = $(this).offset().top;
            var $left = $(this).offset().left;
            var $width = $(this).width();
            
            $thead_fix[key] = $('<table class="'+$(this).parents('table').attr('class')+'">'+$(this).html()+'</table>');
            $thead_fix[key].css({position:'fixed', top:0, left:$left, background:'#fff', width:$width}).hide();
            $(this).find("th").each(function(key1){
               $thead_fix[key].find("th").eq(key1).width($(this).innerWidth());
            });
            $("body").append($thead_fix[key]);

            $(window).scroll(function(){
               if($(window).scrollTop() > $top){
                  $thead_fix[key].show();
               }else{
                  $thead_fix[key].hide();
               }
            });
         });
      }, 750);
      // $thead_fix = $(".theadfix");
      // 
      // $thead_fix.css({position:'fixed', top:0, left:100});
      // $("body").append($thead_fix);
   },

   iCheck : function(){
      if($.fn.iCheck){
         $('input[type=checkbox],input[type=radio]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
         });
      }
   },

   colorpicker : function(){
      if($.fn.colorpicker){
         $('.colorpicker').colorpicker();
      }
   },

   select2 : function(){
      if($.fn.select2){
         $('select').select2();
      }
   },

   spinner : function(){
      if($.fn.spinner){
         $('.spinner').spinner();
      }
   },

   init : function(){
      Main.datepicker();
      Main.tooltip();
      Main.popover();
      Main.zclip();
      Main.chosen();
      Main.mask();
      Main.ligthbox();
      Main.fancybox();
      Main.theadfix();
      Main.iCheck();
      Main.colorpicker();
      Main.select2();
      Main.spinner();
   },

}}();

$(document).ready(function() {
   // Toggle Left Menu
   $('.nav-parent > a').click(function() {
      
      var parent = $(this).parent();
      var sub = parent.find('> ul');
      
      // Dropdown works only when leftpanel is not collapsed
      if(!$('body').hasClass('leftpanel-collapsed')) {
         if(sub.is(':visible')) {
            sub.slideUp(200, function(){
               parent.removeClass('nav-active');
               $('.mainpanel').css({height: ''});
               adjustmainpanelheight();
            });
         } else {
            closeVisibleSubMenu();
            parent.addClass('nav-active');
            sub.slideDown(200, function(){
               adjustmainpanelheight();
            });
         }
      }
      return false;
   });
   
   function closeVisibleSubMenu() {
      $('.nav-parent').each(function() {
         var t = $(this);
         if(t.hasClass('nav-active')) {
            t.find('> ul').slideUp(200, function(){
               t.removeClass('nav-active');
            });
         }
      });
   }
   
   function adjustmainpanelheight() {
      // Adjust mainpanel height
      var docHeight = $(document).height();
      if(docHeight > $('.mainpanel').height())
         $('.mainpanel').height(docHeight);
   }
   
   // Close Button in Panels
   $('.panel .panel-close').click(function(){
      $(this).closest('.panel').fadeOut(200);
      return false;
   });
   
   // Form Toggles
   if($.fn.toggles != undefined){
     $('.toggle').toggles({on: true});
     
     $('.toggle-chat1').toggles({on: false});
   }
   
   // Sparkline
   if($.fn.sparkline != undefined){
      $('#sidebar-chart').sparkline([4,3,3,1,4,3,2,2,3,10,9,6], {
        type: 'bar', 
        height:'30px',
         barColor: '#428BCA'
      });
      
      $('#sidebar-chart2').sparkline([1,3,4,5,4,10,8,5,7,6,9,3], {
        type: 'bar', 
        height:'30px',
         barColor: '#D9534F'
      });
      
      $('#sidebar-chart3').sparkline([5,9,3,8,4,10,8,5,7,6,9,3], {
        type: 'bar', 
        height:'30px',
         barColor: '#1CAF9A'
      });
      
      $('#sidebar-chart4').sparkline([4,3,3,1,4,3,2,2,3,10,9,6], {
        type: 'bar', 
        height:'30px',
         barColor: '#428BCA'
      });
      
      $('#sidebar-chart5').sparkline([1,3,4,5,4,10,8,5,7,6,9,3], {
        type: 'bar', 
        height:'30px',
         barColor: '#F0AD4E'
      });
   }
   
   
   // Minimize Button in Panels
   $('.minimize').click(function(){
      var t = $(this);
      var p = t.closest('.panel');
      if(!$(this).hasClass('maximize')) {
         p.find('.panel-body, .panel-footer').slideUp(200);
         t.addClass('maximize');
         t.html('&plus;');
      } else {
         p.find('.panel-body, .panel-footer').slideDown(200);
         t.removeClass('maximize');
         t.html('&minus;');
      }
      return false;
   });
   
   
   // Add class everytime a mouse pointer hover over it
   $('.nav-bracket > li').hover(function(){
      $(this).addClass('nav-hover');
   }, function(){
      $(this).removeClass('nav-hover');
   });
   
   
   // Menu Toggle
   $('.menutoggle').click(function(){
      
      var body = $('body');
      var bodypos = body.css('position');
      
      if(bodypos != 'relative') {
         
         if(!body.hasClass('leftpanel-collapsed')) {
            body.addClass('leftpanel-collapsed');
            $('.nav-bracket ul').attr('style','');
            
            $(this).addClass('menu-collapsed');
            
         } else {
            body.removeClass('leftpanel-collapsed chat-view');
            $('.nav-bracket li.active ul').css({display: 'block'});
            
            $(this).removeClass('menu-collapsed');
            
         }
      } else {
         
         if(body.hasClass('leftpanel-show'))
            body.removeClass('leftpanel-show');
         else
            body.addClass('leftpanel-show');
         
         adjustmainpanelheight();         
      }

   });
   
   // Chat View
   $('#chatview').click(function(){
      
      var body = $('body');
      var bodypos = body.css('position');
      
      if(bodypos != 'relative') {
         
         if(!body.hasClass('chat-view')) {
            body.addClass('leftpanel-collapsed chat-view');
            $('.nav-bracket ul').attr('style','');
            
         } else {
            
            body.removeClass('chat-view');
            
            if(!$('.menutoggle').hasClass('menu-collapsed')) {
               $('body').removeClass('leftpanel-collapsed');
               $('.nav-bracket li.active ul').css({display: 'block'});
            } else {
               
            }
         }
         
      } else {
         
         if(!body.hasClass('chat-relative-view')) {
            
            body.addClass('chat-relative-view');
            body.css({left: ''});
         
         } else {
            body.removeClass('chat-relative-view');   
         }
      }
      
   });
   
   reposition_searchform();
   
   $(window).resize(function(){
      
      if($('body').css('position') == 'relative') {

         $('body').removeClass('leftpanel-collapsed chat-view');
         
      } else {
         
         $('body').removeClass('chat-relative-view');         
         $('body').css({left: '', marginRight: ''});
      }
      
      reposition_searchform();
      
   });
   
   function reposition_searchform() {
      if($('.searchform').css('position') == 'relative') {
         $('.searchform').insertBefore('.leftpanelinner .userlogged');
      } else {
         $('.searchform').insertBefore('.header-right');
      }
   }
   
   if($.fn.cookie != undefined){
      // Sticky Header
      if($.cookie('sticky-header'))
         $('body').addClass('stickyheader');
         
      // Sticky Left Panel
      if($.cookie('sticky-leftpanel')) {
         $('body').addClass('stickyheader');
         $('.leftpanel').addClass('sticky-leftpanel');
      }
      
      // Left Panel Collapsed
      if($.cookie('leftpanel-collapsed')) {
         $('body').addClass('leftpanel-collapsed');
         $('.menutoggle').addClass('menu-collapsed');
      }
      
      // Changing Skin
      var c = $.cookie('change-skin');
      if(c) {
         $('head').append('<link id="skinswitch" rel="stylesheet" href="css/style.'+c+'.css" />');
      }
   }
});