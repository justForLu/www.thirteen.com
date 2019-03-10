// 客户端判断
window.useragent=navigator.userAgent,
    useragent_tlc=useragent.toLowerCase(),
    device_type = /iPad/.test(useragent) ? 't' : /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Silk/.test(useragent) ? 'm' : 'd',
    is_ucbro=/UC/.test(useragent),
    is_lteie9=false;
// lte IE9浏览器判断
if(new RegExp('msie').test(useragent_tlc)){
    var iebrowser_ver=(useragent_tlc.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [0, '0'])[1];
    if(iebrowser_ver<10) is_lteie9=true;
}
// 延迟加载参数(模板前台用户设置)
window.met_lazyloadbg=$('input[name=met_lazyloadbg]').val()||'/template/pc/skin/images/loading.gif',
met_lazyloadbg_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC';
if (!!window.ActiveXObject || 'ActiveXObject' in window || is_ucbro) met_lazyloadbg=met_lazyloadbg_base64;
if(typeof Breakpoints != 'undefined') Breakpoints();// 窗口宽度断点函数

$(function(){
    /*导航处理*/
   var aLink=$(".met-nav").find('.dropdown a.nav-link');
    aLink.click(function(){
        if(!Breakpoints.is('xs')){
            if($(this).data("hover"))window.location.href = $(this).attr('href');
        }
    });
    var nav_li=$(".navlist .dropdown");
    (function($){
        $.fn.hoverDelay = function(options){
            var defaults = {
                // 鼠标经过的延时时间
                hoverDuring: 200,
                // 鼠标移出的延时时间
                outDuring: 0,
                // 鼠标经过执行的方法
                hoverEvent: function(){
                    // 设置为空函数，绑定的时候由使用者定义
                    $.noop();
                },
                // 鼠标移出执行的方法
                outEvent: function(){
                    $.noop();    
                }
            };
            var sets = $.extend(defaults,options || {});
            var hoverTimer, outTimer;
            return $(this).each(function(){
                // 保存当前上下文的this对象
                var $this = $(this)
                $this.hover(function(){
                    clearTimeout(outTimer);
                    hoverTimer = setTimeout(function () {
                        // 调用替换
                        sets.hoverEvent.apply($this);
                    }, sets.hoverDuring);
                }, function(){
                    clearTimeout(hoverTimer);
                    outTimer = setTimeout(function () {
                        sets.outEvent.apply($this);
                    }, sets.outDuring);
                });
            });
        }
    })(jQuery);
    // 具体使用，给id为“#test”的元素添加hoverEvent事件
    nav_li.hoverDelay({
        // 自定义，outEvent同
        hoverEvent: function(){
           $(this).addClass('open');
        },
        outEvent: function(){
           $(this).removeClass('open');
        }
    }).hover(function(){
        $(this).toggleClass('open');
    },function(){
        $(this).toggleClass('open', false);
    });

    // 导航下拉菜单三级栏目展开处理
    $met_navlist=$('.met-nav .navlist');
    if(typeof(device_type) != 'undefined' && device_type=='d'){
        if($met_navlist.find('.dropdown-submenu').length){
            $met_navlist.find('.dropdown-submenu').hover(function(){
                $(this).parent('.dropdown-menu').addClass('overflow-visible');
            },function(){
                $(this).parent('.dropdown-menu').removeClass('overflow-visible');
            });
        }
    }else{
        if($met_navlist.find('.dropdown-submenu').length){
            setTimeout(function(){
                $met_navlist.find('.dropdown-submenu .dropdown-menu').addClass('block box-shadow-none').prev('.dropdown-item').addClass('dropdown-a');
            },0)
        }
    }

    /*banner只有一张的时候*/
    var bannerlen=$("#exampleCarouselDefault").find('.carousel-item').length;
    if(bannerlen<=1){
        $(".carousel-control").hide();
        $(".carousel-indicators").hide();
    }
    /*banner 自定义高度*/
    var img = $(".met-banner").find('img').eq(0);
    function imgh(){
        if (typeof img.imageloadFun == 'function') {
            img.imageloadFun(function() {
                Breakpoints.on('md lg', {
                    enter: function() {
                         $(".carousel-item").each(function(){
                            var ph=$(this).find('img').attr('pch');//pc端
                            if(ph!=0){
                                $(this).find('img').height(ph);
                            }
                        });
                    }
                })
                Breakpoints.on('sm', {
                    enter: function() {
                         $(".carousel-item").each(function(){
                            var ah=$(this).find('img').attr('adh');//平板
                            if(ah!=0){
                                $(this).find('img').height(ah);
                            }
                        });
                    }
                })
                Breakpoints.on('xs', {
                    enter: function() {
                         $(".carousel-item").each(function(){
                            var ih=$(this).find('img').attr('iph');//手机端
                            if(ih!=0){
                                $(this).find('img').height(ih);
                            }
                        });
                    }
                })
            })
        }
    }
    imgh();
    $(window).resize(function() {
        imgh();
    });

    //简体繁体互换
    var isSimplified = true;
    $('#btn-convert').click(function() {
         if (isSimplified) {
            $('body').s2t();

            isSimplified = false;
            $(this).text('简体');
         } else {
            $('body').t2s();
            
            isSimplified = true;
            $(this).text('繁體');
         }
    });

    // 底部微信
    if($('#met-weixin').length){
        var met_weixin=$('#met-weixin');
        met_weixin.webuiPopover({content:'Content'});
        if(met_weixin.data('trigger')=='click'){
            met_weixin.mouseup(function(){
                $(this).click();
            });
        }
    }

    // 列表图片高度预设及删除
    var $imagesize=$('.imagesize[data-scale]');
    if($imagesize.length) $imagesize.imageSize();
    // 图片延迟加载
    if(typeof $.fn.lazyload == 'function'){
        var $original=$('[data-original]');
        if($original.length) $original.lazyload({placeholder:met_lazyloadbg});
    }
    // 内页子栏目导航水平滚动
    var $metcolumn_nav=$('.met-column-nav-ul');
    if($metcolumn_nav.length){
        Breakpoints.on('xs',{
            enter:function(){
                $metcolumn_nav.navtabSwiper();
            }
        })
    }
    if($('[boxmh-mh]').length) $('[boxmh-mh]').boxMh('[boxmh-h]');//左右区块最小高度设置
    // 侧栏图片列表
    var $sidebar_piclist=$('.sidebar-piclist-ul');
    if($sidebar_piclist.find('.masonry-child').length>1){
        // 图片列表瀑布流
        Breakpoints.on('xs sm',{
            enter:function(){
                setTimeout(function(){
                    if (typeof $sidebar_piclist.masonry == 'function') {
                        $sidebar_piclist.masonry({itemSelector:".masonry-child"});
                    }
                },500)
            }
        });
    }
});

// 全局函数
$.fn.extend({
    // 选项卡列表水平滚动处理
    navtabSwiper:function(){
        var $self=$(this),
            $navObj_p=$(this).parents('.subcolumn-nav'),
            navtabSdefault=function(){
                if(typeof Swiper =='undefined') return false;
                var navObjW=$self.find('>li').parentWidth();
                if(navObjW>$self.parent().width()){
                    // 添加或初始化水平滚动处理
                    if($self.hasClass('swiper-wrapper')){
                        if(!$self.hasClass('flex-start')) $self.addClass('flex-start');
                    }else{
                        $self
                        .addClass("swiper-wrapper flex-start")
                        .wrap("<div class=\"swiper-container swiper-navtab\"></div>").after('<div class="swiper-scrollbar"></div>')
                        .find(">li").addClass("swiper-slide");
                        var swiperNavtab=new Swiper('.swiper-navtab',{
                            slidesPerView:'auto',
                            scrollbar:'.swiper-scrollbar',
                            scrollbarHide:false,
                            scrollbarDraggable:true
                        });
                    }
                    if($navObj_p.length && $('.product-search').length) $navObj_p.height('auto').css({'margin-bottom':10});
                    // 下拉菜单被隐藏特殊情况处理
                    if($self.find('.dropdown').length && $(".swiper-navtab").length){
                        if(!$(".swiper-navtab").hasClass('overflow-visible')) $(".swiper-navtab").addClass("overflow-visible");
                    }
                }else if($self.hasClass('flex-start')){
                    $self.removeClass('flex-start');
                    $navObj_p.css({'margin-bottom':0});
                }
        };
        navtabSdefault();
        $(window).resize(function(){
            navtabSdefault();
        })
        // 移动端下拉菜单浮动方向
        Breakpoints.on('xs sm',{
            enter:function(){
                $self.find('.dropdown-menu').each(function(){
                    if($(this).parent('li').offset().left > $(window).width()/2-$(this).parent('li').width()/2){
                        $(this).addClass('dropdown-menu-right');
                    }
                });
            }
        });
    },
    // 单张图片加载完成回调
    imageloadFunAlone:function(fun){
        var img=new Image();
        img.src=$(this).data('original') || $(this).data('lazy') || $(this).attr('src');
        if (img.complete){
            if (typeof fun==="function") fun();
            return;
        }
        img.onload=function(){
            if (typeof fun==="function") fun();
        };
    },
    // 图片加载完成回调
    imageloadFun:function(fun){
        $(this).each(function(){
            if($(this).data('lazy') || $(this).data('original')){// 图片延迟加载时
                var thisimg=$(this),
                    loadtime=setInterval(function(){
                        if(thisimg.attr('src')==thisimg.data('original') || thisimg.attr('src')==thisimg.data('lazy')){
                            clearInterval(loadtime);
                            thisimg.imageloadFunAlone(fun);
                        }
                    },100)
            }else if($(this).attr('src')){
                $(this).imageloadFunAlone(fun);
            }
        });
    },
    /**
     * imageSize 图片高度预设及删除
     * @param    {String} imgObj 目标图片类
     */
    imageSize:function(imgObj){
        var imgObj=imgObj||'img';
        $(this).each(function(){
            var scale=$(this).data('scale'),
                $self_scale=$(this),
                $img=$(imgObj,this),
                img_length=$img.length;
            if(!isNaN(scale)) scale=scale.toString();
            // 图片对象筛选
            for (var i = 0; i < img_length; i++) {
                for (var s = 0; s < $img.length; s++) {
                    if($($img[s]).parents('[data-scale]').eq(0).index('[data-scale]')!=$self_scale.index('[data-scale]')){
                        $img.splice(s,1);
                        break;
                    }
                }
                if(s==$img.length) break;
            }
            if($img.length && scale.indexOf('x')>=0){
                scale=scale.split('x');
                scale=scale[0]/scale[1];
                // 图片高度预设
                if($img.attr('src')){
                    $img.height(Math.round($img.width()*scale));
                }else{
                    var time=setInterval(function(){
                        if($img.attr('src')){
                            $img.height(Math.round($img.width()*scale));
                            clearInterval(time);
                        }
                    },30);
                }
                $(window).resize(function(){
                    $img.each(function(){
                        if($(this).is(':visible') && $(this).data('original') && $(this).attr('src')!=$(this).data('original')) $(this).height(Math.round($(this).width()*scale));
                    })
                });
                // 图片高度删除
                $img.each(function(){
                    var $self=$(this);
                    $(this).imageloadFun(function(){
                        $self.height('').removeAttr('height');
                    })
                });
            }
        });
    },
    // 父元素宽度计算
    parentWidth:function(sonNum){
        var sonTrueNum=$(this).length,
            parentObjW=0;
        if(sonNum>sonTrueNum||!sonNum) sonNum=sonTrueNum;
        $(this).each(function(index, el) {
            var sonObjW=$(this).outerWidth()+parseInt($(this).css('marginLeft'))+parseInt($(this).css('marginRight'));
            parentObjW+=sonObjW;
        });
        return parentObjW+sonNum;
    },
    /**
     * scrollFun 窗口距离触发
     * @param  {Number}  top            离窗口触发的距离
     * @param  {Boolean} loop           是否循环触发
     * @param  {Boolean} skip_invisible 是否跳过不可见元素的触发事件
     */
    scrollFun:function(fun,options){
        if (typeof fun==="function") {
            var defaults={
                    top:30,
                    loop:false,
                    skip_invisible:true,
                    is_scroll:false
                };
            $.extend(defaults,options);
            $(this).each(function(){
                var $self=$(this),
                    fun_open=true,
                    windowDistanceFun=function(){// 窗口距离触发回调
                        if(fun_open){
                            var this_t=$self.offset().top,
                                scroll_t=$(window).scrollTop(),
                                this_scroll_t=this_t-scroll_t-$(window).height(),
                                this_scroll_b=this_t+$self.outerHeight()-scroll_t,
                                visible=defaults.skip_invisible?$self.is(":visible"):true;
                            if(this_scroll_t<defaults.top && this_scroll_b>0 && visible){
                                if(!defaults.loop) fun_open=false;
                                fun($self);
                            }
                        }
                    };
                windowDistanceFun();
                // 滚动时窗口距离触发回调
                if(defaults.is_scroll){
                    $(window).scroll(function(){
                        if(fun_open) windowDistanceFun();
                    })
                }
            });
        }
    },
    /**
     * appearDiy 手动appear动画
     * @param  {Boolean} is_reset 是否重置动画
     */
    appearDiy:function(is_reset){
        $(this).each(function(){
            var $self=$(this),
                animation='animation-'+$(this).data('animate');
            if(is_reset){
                // 重置动画
                $(this).removeClass(animation).removeClass('appear-no-repeat').addClass('invisible');
            }else{
                // 执行动画
                $(this).addClass(animation).addClass('appear-no-repeat');
                setTimeout(function(){
                    $self.removeClass('invisible');
                },0)
            }
        });
    },
    /**
     * galleryLoad 画廊
     * @param  {Array} dynamic 自定义图片数组
     */
    galleryLoad:function(dynamic){
        if(typeof $.fn.lightGallery == 'undefined') return false;
        $("body").addClass("met-lightgallery");//画廊皮肤
        if(dynamic){
            // 自定义图片数组
            $(this).lightGallery({
                loop:true,
                dynamic:true,
                dynamicEl:dynamic,
                thumbWidth:64,
                thumbContHeight:84
            });
        }else{
            // 默认加载画廊
            $(this).lightGallery({
                selector:'.lg-item-box:not(.slick-cloned)',
                exThumbImage:'data-exthumbimage',
                thumbWidth:64,
                thumbContHeight:84,
                nextHtml:'<i class="iconfont icon-next"></i>',
                prevHtml:'<i class="iconfont icon-prev"></i>'
            });
        }
    },
    // 内页左右区块最小高度设置
    boxMh:function(boxmh_h){
        if($(this).length && $(boxmh_h).length){
            var $self=$(this),
                $boxmh_h=$(boxmh_h),
                box_mh=function(){
                    var boxmh_mh_t=$self.offset().top,
                        boxmh_h_t=$boxmh_h.offset().top,
                        mh=$boxmh_h.outerHeight();
                    if(boxmh_mh_t==boxmh_h_t){//两个区块并排时
                        if(mh!=$boxmh_h.attr('data-height')){
                            $boxmh_h.attr({'data-height':mh});
                            $self.css({'min-height':mh});
                        }
                    }else{
                        $boxmh_h.attr({'data-height':''});
                        $self.css({'min-height':''});
                    }
                };
            box_mh();
            setInterval(function(){
                box_mh();
            },50)
        }
    },
    // 表格响应式格式化
    tablexys:function(){
        var $self=$(this);
        $(this).addClass('tablesaw table-striped table-bordered table-hover tablesaw-sortable tablesaw-swipe').attr({"data-tablesaw-mode":"swipe",'data-tablesaw-sortable':''});
        Breakpoints.get('xs').on({
            enter:function(){
                $self.each(function(){
                    if(!$('thead',this).length){
                        var td=$("tbody tr:eq(0) td",this),th;
                        if(td.length==0) td=$("tbody tr:eq(0) th",this);
                        td.each(function(){
                            th+='<th data-tablesaw-sortable-col>'+$(this).html()+'</th>';
                        });
                        $(this).prepend("<thead><tr>"+th+"</tr></thead>");
                        $("tbody tr:eq(0)",this).remove();
                        $("tbody td",this).attr('width','auto');
                    }
                });
                $(document).trigger("enhance.tablesaw");
            }
        })
    }
});

/*!
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2015 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.9.7
 *
 */
(function($, window, document, undefined) {
    var $window = $(window),
        canvasPosition=function(from_dom,to_dom){ // canvas设置
            var top=from_dom.position().top,
                left=from_dom.position().left,
                width=from_dom.width(),
                height=from_dom.height();
            to_dom.css({top:top,left:left}).width(width).height(height);
        },
        canvasControl=function(dom,canvas_id){
            $.stackBlurImage(dom, canvas_id, 10, false);
            if(dom.is(':visible')) canvasPosition(dom,$('#'+canvas_id));
            $(window).resize(function() {
                if(dom.is(":visible")) canvasPosition(dom,$('#'+canvas_id));
            });
            $('#'+canvas_id).attr({'data-load':true});
        },
        thumbdir= '';//M['weburl']+'include/thumb.php?dir=';
    $.fn.lazyload = function(options) {
        var elements = this;
        var $container;
        var settings = {
            threshold       : 30,
            failure_limit   : 1000,
            event           : "scroll",
            effect          : "fadeIn",
            effect_speed    : null,
            container       : window,
            data_attribute  : "original",
            data_srcset     : 'srcset',
            skip_invisible  : true,
            appear          : null,
            load            : null,
            placeholder     : met_lazyloadbg,// 'base64',met_lazyloadbg,'blur'
        };

        function update() {
            var counter = 0;

            elements.each(function() {
                var $this = $(this),
                    $this_canvas=$this.next('canvas');
                if (settings.skip_invisible && !$this.is(":visible")) {
                    return;
                }
                if($this_canvas.length && !$this_canvas.attr('data-load') && $.stackBlurImage) canvasControl($this,$this_canvas.attr('id'));
                if ($.abovethetop(this, settings) ||
                    $.leftofbegin(this, settings)) {
                        /* Nothing. */
                } else if (!$.belowthefold(this, settings) &&
                    !$.rightoffold(this, settings)) {
                        $this.trigger("appear");
                        /* if we found an image we'll load, reset the counter */
                        counter = 0;
                } else {
                    if (++counter > settings.failure_limit) {
                        return false;
                    }
                }
            });

        }

        if(options) {
            /* Maintain BC for a couple of versions. */
            if (undefined !== options.failurelimit) {
                options.failure_limit = options.failurelimit;
                delete options.failurelimit;
            }
            if (undefined !== options.effectspeed) {
                options.effect_speed = options.effectspeed;
                delete options.effectspeed;
            }

            $.extend(settings, options);
        }

        /* Cache container as jQuery as object. */
        $container = (settings.container === undefined ||
                      settings.container === window) ? $window : $(settings.container);

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        if (0 === settings.event.indexOf("scroll")) {
            $container.on(settings.event, function() {
                return update();
            });
        }
        if(settings.placeholder=='base64') settings.placeholder=met_lazyloadbg_base64;

        this.each(function(index) {
            var self = this,
                $self = $(self),
                original = $self.attr("data-" + settings.data_attribute),
                placeholder=settings.placeholder,
                placeholder_ok=placeholder!=met_lazyloadbg_base64?true:false;
            self.loaded = false;

            /* If no src attribute given use data:uri. */
            if ($self.is("img") && original && (!$self.attr("src") || $self.attr("src")!=original)) {
                if(placeholder=='blur' && $.stackBlurImage){
                    // 图片高斯模糊加载小图
                    if(!$self.attr('data-minimg')){
                        // 设置小图路径
                        var thumb=original.replace(M['weburl'],M['weburl']),
                            original_array=thumb.split('&');
                        if(thumb.indexOf('http')<0 || (thumb.indexOf('http')>=0 && thumb.indexOf(M['weburl'])>=0)){
                            if(original.indexOf('include/thumb.php?dir=')>-1){
                                var data_minimg=original_array[0]+'&x=50';
                            }else{
                                var data_minimg=thumbdir+thumb+'&x=50';
                            }
                            if(original_array && original_array.length==3){
                                scale_x=original_array[1].substring(2);
                                scale_y=original_array[2].substring(2);
                                scale=scale_y/scale_x;
                                minimg_h=Math.round(50*scale);
                                data_minimg+='&y='+minimg_h;
                            }
                            $(this).attr({src:data_minimg,'data-minimg':true});
                            // 高斯模糊小图
                            var img=new Image();
                            img.src=$self.attr("src");
                            img.onload=function(){
                                setTimeout(function(){
                                    var $self_canvas=$self.next('canvas');
                                    if($self.attr('src')!=original && !$self_canvas.length){
                                        var canvas_id="imgcanvas"+index;
                                        $self.wrapAll('<section style="position: relative;"></section>').after('<canvas id="'+canvas_id+'" data-load="false" width="'+$self.width()+'" height="'+$self.height()+'" style="position:absolute;z-index:10;"></canvas>');
                                        if(!settings.skip_invisible || $self.is(":visible")) canvasControl($self,canvas_id);
                                    }else if($self_canvas.length){
                                        canvasPosition($self,$self_canvas);
                                    }
                                },30)
                            }
                        }
                    }
                }else{
                    if(placeholder=='blur') placeholder=met_lazyloadbg;
                    $self.attr("src", placeholder);
                    if(placeholder_ok && !$self.hasClass('imgloading')) $self.addClass('imgloading');
                }
            }

            /* When appear is triggered load original image. */
            $self.one("appear", function() {
                if (!this.loaded) {
                    if (settings.appear) {
                        var elements_left = elements.length;
                        settings.appear.call(self, elements_left, settings);
                    }
                    var srcset = $self.attr("data-" + settings.data_srcset);
                    $("<img />")
                        .one("load", function() {
                        $self.hide();
                        if ($self.is("img")/* || $self.is("source") || $self.is("video") || $self.is("audio") || $self.is("iframe") || $self.is("script") || $self.is("link")*/) {
                            if(srcset) $self.attr("srcset", srcset);
                            $self.attr("src", original);
                        } else {
                            $self.css("background-image", "url('" + original + "')");
                            if(srcset) $self.css("background-image", "-webkit-image-set(" + srcset + ")");
                        }
                        $self[settings.effect](settings.effect_speed);
                        $self.one('load', function() {
                            $self.removeClass('imgloading');
                            $self.next('canvas').fadeOut("normal",function(){
                                $self.next('canvas').remove();
                            });
                        });

                        self.loaded = true;

                        /* Remove image from array so it is not looped next time. */
                        var temp = $.grep(elements, function(element) {
                            return !element.loaded;
                        });
                        elements = $(temp);

                        if (settings.load) {
                            var elements_left = elements.length;
                            settings.load.call(self, elements_left, settings);
                        }
                    }).attr({srcset:srcset,src:original}).removeClass('imgloading').next('canvas').fadeOut("normal",function(){
                        $("<img />").next('canvas').remove();
                    });
                }
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if (0 !== settings.event.indexOf("scroll")) {
                $self.on(settings.event, function() {
                    if (!self.loaded) {
                        $self.trigger("appear");
                    }
                });
            }
        });

        /* Check if something appears when window is resized. */
        $window.on("resize", function() {
            update();
        });

        /* With IOS5 force loading images when navigating with back button. */
        /* Non optimal workaround. */
        if ((/(?:iphone|ipod|ipad).*os 5/gi).test(navigator.appVersion)) {
            $window.on("pageshow", function(event) {
                if (event.originalEvent && event.originalEvent.persisted) {
                    elements.each(function() {
                        $(this).trigger("appear");
                    });
                }
            });
        }

        /* Force initial check if images should appear. */
        $(document).ready(function() {
            update();
        });

        return this;
    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = (window.innerHeight ? window.innerHeight : $window.height()) + $window.scrollTop();
        } else {
            fold = $(settings.container).offset().top + $(settings.container).height();
        }

        return fold <= $(element).offset().top - settings.threshold;
    };

    $.rightoffold = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.width() + $window.scrollLeft();
        } else {
            fold = $(settings.container).offset().left + $(settings.container).width();
        }

        return fold <= $(element).offset().left - settings.threshold;
    };

    $.abovethetop = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollTop();
        } else {
            fold = $(settings.container).offset().top;
        }

        return fold >= $(element).offset().top + settings.threshold  + $(element).height();
    };

    $.leftofbegin = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollLeft();
        } else {
            fold = $(settings.container).offset().left;
        }

        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };

    $.inviewport = function(element, settings) {
         return !$.rightoffold(element, settings) && !$.leftofbegin(element, settings) &&
                !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
     };

    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() or */
    /* $("img").filter(":below-the-fold").something() which is faster */

    $.extend($.expr[":"], {
        "below-the-fold" : function(a) { return $.belowthefold(a, {threshold : 0}); },
        "above-the-top"  : function(a) { return !$.belowthefold(a, {threshold : 0}); },
        "right-of-screen": function(a) { return $.rightoffold(a, {threshold : 0}); },
        "left-of-screen" : function(a) { return !$.rightoffold(a, {threshold : 0}); },
        "in-viewport"    : function(a) { return $.inviewport(a, {threshold : 0}); },
        /* Maintain BC for couple of versions. */
        "above-the-fold" : function(a) { return !$.belowthefold(a, {threshold : 0}); },
        "right-of-fold"  : function(a) { return $.rightoffold(a, {threshold : 0}); },
        "left-of-fold"   : function(a) { return !$.rightoffold(a, {threshold : 0}); }
    });

})(jQuery, window, document);
