$(document).ready(function(){
    var crop_divider = 0.5;
    /* 
    * Function: Check whether browser is IE or not.
    */
    var isIE = function(){
          var rv = -1; // Return value assumes failure.
          if (navigator.appName == 'Microsoft Internet Explorer')
          {
            var ua = navigator.userAgent;
            var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null) rv = parseFloat( RegExp.$1 );
          }
          
          return rv == -1 ? false : true;
    };

    $(".btnnds").click(function(){
        setTimeout(function() {
            $('button,a,[type="submit"]').removeAttr('disabled');
        }, 20);
    });

    /*
    * Function : Ajax form submission
    * Target : form that has 'form-ajax' atribute
    * Method : default method is POST, can be changed with 'method' attribute
    */
    $(document).on("submit","[ajax-form]",function(e){
        e.preventDefault();
        if(typeof nds != "undefined") return false;
        // nds is no double submission, create nds variable after this function call
        nds = true;
        $form = $(this);
        $url = "";
        // Default POST Method
        $method = "post";
        $type = $(this).attr('ajax-form');

        /*
        * Loading table
        */
        if($type == "table") $(".ajax-table").block();

        /*
        * Getting ajax url from 'action' attribute
        * Getting ajax url from 'method' attribute
        */
        if($form.attr('action')) $url = $form.attr('action');
        if($form.attr('method')) $method = $form.attr('method');
        
        /*
        * run loading button if button submit has 'btn-loading' class
        */
        $button = $form.find(".btn-loading").html();
        $form.find(".btn-loading").html('<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');

        // Run ajax submission
        $.ajax({
            url : $url,
            type : $method,
            data : $form.serialize(),
            beforeSend : ajaxBeforeSendResp,
            error : ajaxErrorResp,
            success : ajaxSuccessResp
        });
    });
    
    ajaxBeforeSendResp = function(resp){
        // Remove all element with 'error' class
        $form.find(".error").each(function(){
            $(this).remove();
        });
    }

    ajaxErrorResp = function(resp){
        // Clear Submission
        $form.find(".btn-loading").html($button);
        delete nds;
    }

    ajaxSuccessResp = function(resp){
        try{
            // Parse response string to json
            resp = $.parseJSON(resp);
        }catch(err){
            // if not json format then eval this response
            eval(resp);
        }

        /* Create Eval javascript */
        if(typeof resp.eval != "undefined"){
            eval(resp.eval);
        }
        
        // Clear Submission
        if(typeof resp.redirect == "undefined") $form.find(".btn-loading").html($button);
        if($form.attr('loading')) $("#proccess").modal('hide');
        delete nds;

        /* 
        * Function: Push Url State.
        */
        if(typeof resp.url != "undefined"){
            history.pushState({}, '', resp.url);
        }

        /* 
        * Function: Table ajax.
        */
        if($type == "table"){
            if($form.attr('table')){
                $($form.attr('table')).html(resp.table);
            }else{
                $(".ajax-table").html(resp.table).unblock();
            }
        }

        /* Info Message */
        if(typeof resp.info != "undefined"){
            $target = typeof resp.target != "undefined" ? resp.target : ".notif";
            $info = $($target).hide().html(resp.info).fadeIn(300);
            if(typeof $delay != "undefined"){
                setTimeout(function(){$info.fadeOut(300)}, $delay);
            }
        }

        /* Popup Alert */
        if(typeof resp.pAlert != "undefined"){
            pAlert(resp.pAlert);
        }

        /* Popup Confirm */
        if(typeof resp.pConfirm != "undefined"){
            pConfirm(resp.pConfirm);
        }

        /* Validation */
        if(typeof resp.validation != "undefined"){
            $.each(resp.validation, function(key,val){
                message = $('<span class="error">'+val[0]+'</span>');
                $('[name="'+key+'"]').after(message);
            });
        }

        /* Redirect */
        if(typeof resp.redirect != "undefined"){
            if(typeof $delay != "undefined"){
                setTimeout(function(){
                    window.location = resp.redirect;
                }, $delay);
            }else{
                window.location = resp.redirect;
            }
        }
        Main.init();
    }

    /*
    * Refresh Table button
    */
    $(document).on('click', '.refresh-table', function(){
        updateTable();
    });

    /* 
    * Function: Pagination Ajax.
    */
    $(document).on('click', 'ul.pagination a', function(e){
        e.preventDefault();
        nds = true;
        $url = $(this).attr('href');
        $target = ".ajax-table";

        /* 
        * Function: Loading.
        */
        $($target).block();

        $.ajax({
            url : $url,
            type : 'get',
            success : function(resp){
                try{
                    resp = $.parseJSON(resp);
                }catch(err){
                    eval(resp);
                }
                
                /* 
                * Function: Clear Submission.
                */
                // el.unblock();
                delete nds;
                
                /* 
                * Function: Push URL State.
                */
                if(typeof resp.url != "undefined"){
                    history.pushState({}, '', resp.url);
                }

                /* 
                * Function: Table Ajax.
                */
                $($target).html(resp.table).unblock();
                Main.init();
            }
        });
    });

    /* 
    * Hra
    */
    $(document).off("click", "[ajax-confirm]");
    $(document).on("click", "[ajax-confirm]", function(e){
        e.preventDefault();
        $url = $(this).attr('href');
        $type = $(this).attr('ajax-confirm');
        $title = lang('gen.confirm_notif');

        if($type == "status"){
            $msg = lang('gen.status_confirm');
        }if($type == "delete"){
            $msg = lang('gen.delete_confirm');
        }
        bootbox.dialog({
            title: $title,
            message: $msg,
            buttons: {
                success: {
                    label: "OK",
                    className: "btn-primary",
                    callback: function() {
                        $.ajax({
                            url : $url,
                            type : "post",
                            success : function(a){
                                if(a){
                                    eval(a);
                                }else{
                                    updateTable();
                                }
                            }
                        });
                    }
                },
                cancel: {
                    label: "Cancel",
                    className: "btn-default"
                }
            },
        });
    });

    /* function pAlert
    * Function : Create alert dialog with bootstrap modal, this function usually use on ajax submission with json parameter
    * library : bootbox modal dialog link : bootboxjs.com
    */
    function pAlert($a){
        bootbox.dialog({
          title: $a['title'],
          message: $a['msg'],
          buttons: {
            success: {
              label: "OK",
              className: "btn-success",
              callback: function() {
                if(typeof $a['callback'] != "undefined") eval($a['callback']);
                if(typeof $a['redirect'] != "undefined") window.location = $a['redirect'];
              }
            },
          }
        });
    }

    /* function pConfirm
    * Function : Create confirm dialog with bootstrap modal, this function usually use on ajax submission with json parameter
    * library : bootbox modal dialog link : bootboxjs.com
    */
    function pConfirm($a){
        bootbox.dialog({
          title: $a['title'],
          message: $a['msg'],
          buttons: {
            success: {
              label: "OK",
              className: "btn-success",
              callback: function() {
                if(typeof $a['callback'] != "undefined") eval($a['callback']);
                if(typeof $a['redirect'] != "undefined") window.location = $a['redirect'];
              }
            },
            cancel: {
              label: "Cancel",
              className: "btn-default button-light",
              callback: function() {
                if(typeof $a['callback'] != "undefined") eval($a['cancel']);
              }
            }
          }
        });
    }

    /* 
    * Function: Btn Uploader.
    */
    var uploaderFile = [];
    var flname = [];
    $('.uploader').each(function(key){
        // Generate
        var self = $(this);
        key += 1;
        var dataget = [];
        pageType = self.data('page');
        dataget[dataget.length] = "pageType="+pageType;
        dataget[dataget.length] = self.data('type') ? "uploadType="+self.data('type') : "uploadType=upload";
        if(self.data('lang')) dataget[dataget.length] = "lang="+self.data('lang');
        var databtn = self.data('btn');
        $("#uploadBtn"+key).html("Upload "+databtn);

        uploaderFile[key] = new plupload.Uploader({
            runtimes : isIE() ? 'flash,html4,html5' : 'html5,html4',
            browse_button : 'uploadBtn'+key,
            container : 'uploaderContainer'+key,
            flash_swf_url : path['vendor']+'uploader/plupload.flash.swf',
            url : $base_url+'/ajax/upload?'+dataget.join("&"),
            filters : {
                title : "Image files",
                extensions : "*",
                max_file_size : $uploader['max_file_size'],
            }
        });

        // Initialize
        uploaderFile[key].init();
        
        // On Added
        uploaderFile[key].bind('FilesAdded', function(up, files) {
            $.each(files, function(i, file) {
                $('#uploaderContainer'+key+' .filemsg').html('<img alt="uploader" src="'+path('bo.icon')+'/uploader.gif" />');
            });
            
            $('#uploaderContainer'+key+' span.errormsg').remove();
            $('#uploadBtn'+key+' span').text('Uploading...');
            
            uploaderFile[key].start();
        });

        // File Uploaded
        uploaderFile[key].bind('FileUploaded', function(up, file, resp) {
            var resp = $.parseJSON(resp.response);
            // Validation error
            if(typeof resp.error != "undefined"){
                $("#uploadBtn"+key).html("Update "+databtn);
                $('#uploaderContainer'+key+' .filemsg').html("");
                pAlert(resp.error);
                return;
            }

            $filename = self.find('.filename');
            $JCrop_preview = self.find('.JCrop_preview');
            $x = self.find('.x');
            $y = self.find('.y');
            $w = self.find('.w');
            $h = self.find('.h');

            // Upload Type
            if(resp['uploadtype'] == "multiple"){
                $("#uploadBtn"+key).html("Update "+databtn);
                flname[flname.length] = resp.filename;
                $('.filename').val(flname.join(","));
                $('#uploaderContainer'+key+' .view-multiple').append(resp.preview);
                $('#uploaderContainer'+key+' .filemsg').html("");
                Main.init();
                return 0;
            }

            if(resp['uploadtype'] != "cropping"){
                $("#uploadBtn"+key).html("Update "+databtn);
                $('.filename').val(resp.filename);
                $('#uploaderContainer'+key+' .filemsg').html(resp.preview);
                Main.init();
                return 0;
            }
            
            var previewArea = '<img alt="uploader" src="'+path('bo.icon')+'/uploader.gif" /><img class="img-responsive preview" alt="Preview Photo" src="'+resp.targetfile+'" style="display:none" />';
            
            /* 
            * Function: Image ratio calculation.
            */
            var real_height, real_width, y1, y2, x1, x2;
            var x,y,w,h;
            real_width = resp.width;
            real_height = resp.height;
            image_dpi = resp.dpi;
            ratio_image = real_width / real_height;
            x1 = (real_width * crop_divider) - ((real_width * crop_divider) * crop_divider);
            x2 = (real_width * crop_divider) + ((real_width * crop_divider) * crop_divider);            
            y1 = (real_height * crop_divider) - ((real_height * crop_divider) * crop_divider);
            y2 = (real_height * crop_divider) + ((real_height * crop_divider) * crop_divider);

            $('#uploaderContainer'+key+' .filemsg').html('');
            $JCrop_preview.find('.modal-body').css("text-align", "center");
            $JCrop_preview.find('.modal-body').html(previewArea);

            /* 
            * Function: Run Modal.
            */
            $JCrop_preview.modal({
                backdrop : false,
                keyboard : false,
            });

            /* 
            * Function: Ratio.
            */
            ratio = 0;
            if(typeof $uploader[pageType]['img_ratio'] != "undefined"){
                ratio = $uploader[pageType]['img_ratio'];
            }

            /* 
            * Function: J-Crop Initialize.
            */
            $(".preview").one("load", function() {
                $(this).prev().remove();
                $(this).show();

                self.find('.preview').Jcrop({
                    aspectRatio: ratio,
                    setSelect: [ x1, y1, x2, y2],
                    onSelect: updateCoords,
                    trueSize: [real_width,real_height],
                    boxWidth : '100%',
                    bgColor: 'transparent'
                });
                
                function updateCoords(c)
                {
                    $x.val(c.x);
                    $y.val(c.y);
                    $w.val(c.w);
                    $h.val(c.h);
                };
            });

            $(".JC-cancel").off("click");
            $(".JC-upload").off("click");
            /* 
            * Function: Cancel J-Crop.
            */
            $(".JC-cancel").click(function(e){
                $JCrop_preview.modal('hide');
            });

            /* 
            * Function: Execute J-Crop.
            */
            $(".JC-upload").click(function(e){
                e.preventDefault();
                x = $x.val();
                y = $y.val();
                w = $w.val();
                h = $h.val();

                var postdata = []
                postdata[postdata.length] = "x="+x;
                postdata[postdata.length] = "y="+y;
                postdata[postdata.length] = "w="+w;
                postdata[postdata.length] = "h="+h;
                postdata[postdata.length] = "pageType="+self.data('page');
                postdata[postdata.length] = "filename="+ resp.filename;
                postdata[postdata.length] = "real_width=" + real_width;
                postdata[postdata.length] = "real_height=" + real_height;
                if(self.data('lang')) postdata[postdata.length] = "lang="+self.data('lang');

                $button = $(this).html();
                $(this).html('<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
                
                $.ajax({
                    type: "POST",
                    url: $base_url+"/ajax/crop",
                    data: postdata.join("&"),
                    success: function(resp){
                        $(".JC-upload").html($button);
                        $JCrop_preview.modal('hide');
                        $("#uploadBtn"+key).html("Update "+databtn);
                        self.find('.filename').val(resp.filename);
                        $('#uploaderContainer'+key+' .filemsg').html(resp.preview);
                        Main.init();
                        return 0;
                    }
                })
            });
        });
    });

    /*
    * Jquery Sorting Table with ajax
    */
    $(document).on('click', '.btn-sorting', function(){
        $('.sortable').addClass('tableorder');
        $(this).toggleClass('active');
        if($(this).hasClass('active')){
            $('body').append('<button class="btn btn-sm btn-warning modal-sorting"><span class="fa fa-sort"></span></button>');
            $(".modal-sorting").hide();
            if($.fn.sortable) $('.sortable tbody').sortable({
                update: function(e, ui) {
                    var self = this, idSorts = $(this).sortable('toArray'), ids = [], sorts = [], maxSort, nums = [];
                    var $table = $(this).parents('table');

                    for(var i=0;i<idSorts.length;i++) {
                        var splitIdSort = idSorts[i].split('_');
                        ids.push(parseInt(splitIdSort[0]));
                        sorts.push(parseInt(splitIdSort[1]));
                    }
                    
                    maxSort = Math.max.apply(Math, sorts);
                    $table.find('tbody tr').each(function(idx){
                        $(this).find('td:first').text(idx + 1);
                    });
                    
                    $.post($base_url+'/ajax/sorter',{table:$table.data('table'),new_sorts:ids.toString(),max:maxSort}, function(resp){});
                }
            }).disableSelection();
        }else{
            $(".modal-sorting").remove();
            $('.sortable').removeClass('tableorder');
            $('.sortable tbody').sortable('destroy');
            if($.gritter != undefined){
                $.gritter.add({
                    title: lang('gen.notif'),
                    text: lang('gen.sort_update'),
                    sticky: false,
                    time: 2000
                });
            }
            updateTable();
        }
    });
    
    $(document).on({
        mouseenter: function() {
            var $top = $(this).offset().top + 2;
            var $left = $(this).offset().left + 20;
            $(".modal-sorting").css({top : $top, left : $left}).show();

            sortid = $(this).attr("id");
        }
    }, '.sortable tbody tr');

    // $thead_fix = $(".thead-fix");
    // $thead_fix = $('<table class="'+$thead_fix.parents('table').attr('class')+'">'+$thead_fix.html()+'</table>');
    // $thead_fix.css({position:'fixed', top:0, left:100});
    // $("body").append($thead_fix);

    $(document).on("click", ".modal-sorting", function(){
        var $si = sortid.split("_");
        bootbox.prompt({
            title: "Change order number?",
            value: $si[1],
            callback: function(res) {
                var $sort = parseInt(res);
                if($sort < 0) $sort = $sort * -1;
                $.post($base_url+'/ajax/manual_sorter',{table:$(".sortable").data('table'),id:$si[0],sort:$sort}, function(resp){
                    $('.btn-sorting').trigger('click');$(".modal").modal("hide");
                });
            }
        });
    });
});

/* 
* Function: Refresh table with Ajax.
*/
function updateTable($url){
    // Loading
    var el = $(".ajax-table");
    el.block();

    if(!$url) $url = location.href;
    $.ajax({
        url : $url,
        type : 'get',
        success : function(resp){
            try{
                resp = $.parseJSON(resp);
            }catch(err){
                eval(resp);
            }
            el.unblock();
            history.pushState({}, '', $url);
            $(".ajax-table").html(resp.table);
            Main.init();
        }
    });
}

/*
* Function : to make conditions javascript run on right media query
*/
$(function(){
    function mediaQuery(){
        media = $(this).width();
        if(media <= 1199){

        }
        if(media >= 992 && media <= 1199){

        }
        if(media <= 991){

        }
        if(media <= 991){

        }
        if(media >= 768 && media <= 991){

        }
        if(media <= 767){

        }
        if(media >= 480 && media <= 767){

        }
        if(media <= 479){

        }else{
        }
    }
    $(window).resize(function(){
        mediaQuery(media)
    });
    mediaQuery();
});

/*
* Helper function
*/

function path($name){
    $arr = $name.split(".");
    $res = $path;
    $arr.forEach(function(data){
        $res = $res[data];
    });
    return $base_url+$res;
}

function lang($name){
    $arr = $name.split(".");
    $res = $lang;
    $arr.forEach(function(data){
        $res = $res[data];
    });
    return $res;
}

// Run Main Custom Javascript
$(document).ready(function(){
    Main.init();
})