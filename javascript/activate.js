jQuery(document).ready(function($) {

    //equal height columns jquery
    $.fn.equalHeights = function(minHeight, maxHeight) {
        tallest = (minHeight) ? minHeight : 0;
        this.each(function() {
            $(this).height("");
            if($(this).height() > tallest) {
                tallest = $(this).height();
            }
        });
        if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
        return this.each(function() {
            $(this).height(tallest);
        });
    }

    function handleResize() {

        //check if there's enough width to display totara custom menu
        var space = $("#totara-header-bar").width() - $("#side-panel-button").outerWidth(true) - $("#totara-header-bar-icons").outerWidth(true);
        var menuwidth = $("#totara-menu-header").outerWidth(true);
        if (space >= menuwidth){
            $("#totara-menu-header").removeClass("hide-menu");
            $(".totara-menu-sidebar").addClass("hide-menu");
            $("#side-panel-button").removeClass("show");
        } else {
            $("#totara-menu-header").addClass("hide-menu");
            $(".totara-menu-sidebar").removeClass("hide-menu");
            $("#side-panel-button").addClass("show");
        }

        //force equal heights on action blocks
        $(".action-content").equalHeights();

        //force equal heights on frontpage course boxes
        $("#frontpage-course-list .frontpage-course-list-all div.gridcoursebox").equalHeights();
        $("#frontpage-course-list .frontpage-course-list-enrolled div.gridcoursebox").equalHeights();

        //force equal heights on rein top-tabs box
        //$(".custom-theme .tabs-top .ui-tabs-nav li a").equalHeights();

        //force equal heights on grid course tile headers
        $("#gridiconcontainer .icon_content").equalHeights();
    }

    $(window).resize(handleResize).resize(); // Trigger resize handlers.
    $(window).load(handleResize);

    // Initialize slidebar functionality.
    $.slidebars();

    //force side column to be 100% height of #page
    //$(".two-column #block-region-side-pre").height( $("#page").outerHeight() );

    //add bootstrap class to table on reports
    $('#page-totara-coursecatalog-courses .rb-display-table-container').addClass('span9');

    //fix for gradebook conflict with side panel
    //adds class to html element on gradebook pages
    $('html:has(body.path-grade-report)').addClass("grade-fix");

    // Handle front page background video if present.
    if ($('.jquery-background-video').length >= 1) {
        $('.jquery-background-video').bgVideo({
            fullScreen: false, // Sets the video to be fixed to the full window - your <video> and it's container should be direct descendents of the <body> tag
            fadeIn: 1000, // Milliseconds to fade video in/out (0 for no fade)
            pauseAfter: 120, // Seconds to play before pausing (0 for forever)
            fadeOnPause: false, // For all (including manual) pauses
            fadeOnEnd: true, // When we've reached the pauseAfter time
            showPausePlay: true, // Show pause/play button
            pausePlayXPos: 'right', // left|right|center
            pausePlayYPos: 'top', // top|bottom|center
            pausePlayXOffset: '15px', // pixels or percent from side - ignored if positioned center
            pausePlayYOffset: '15px' // pixels or percent from top/bottom - ignored if positioned center
        });
    }

    // Handle front page banner slider if present.
    if ($('#slider').length >= 1) {
        var timeSetting = $("#slider").attr("data-pausetime");
        var slider = $('#slider').leanSlider({
            pauseTime: timeSetting,
            directionNav: '#slider-direction-nav',
            controlNav: '#slider-control-nav'
        });
    }
    setTimeout(function() {
        $("#banner-nav, .banner-title").show();
        $("#banner-nav .init").addClass("animated fadeInLeft");
        $(".banner-title .init, #slideimg").addClass("animated fadeIn");
    }, 1000);

    /*
     * Disable course category links and make them click parent collapse/expand toggle.
     * Open course info when category is expanded.
     */
    if ($('body.totara110base-settings-coursecatajax').length > 0) {
        if ($(".course_category_tree .category .categoryname a").length > 0) {
            // Find each category.
            var handle_category_behavior = function($category) {
                // Find each category link and prevent default behavior.
                $category.find(".categoryname a").each(function() {
                    $(this).click(function(e) {
                        e.preventDefault();
                        // Toggle collapse/expand behavior.
                        $(this).parent().click();
                    });
                });
                // Handle additional functionality assignment/activation on .categoryname
                $category.find(".categoryname").each(function() {
                    // If currently collapsed, wait for courses to load and expand info button where present.
                    if ($category.hasClass("collapsed")) {
                        var infocheck = setInterval(function() {
                            if ($category.hasClass("loaded")) {
                                clearInterval(infocheck);
                                setTimeout(function() {
                                    // Check for additional categories and assign functionality.
                                    if ($category.find(".subcategories").length > 0) {
                                        $category.find(".subcategories .category").each(function() {
                                            var $subcategory = $(this);
                                            handle_category_behavior($subcategory);
                                        });
                                    }
                                    // Check for course info boxes, click and display them if present.
                                    if ($category.find(".courses .coursebox").length > 0) {
                                        $category.find(".courses .coursebox").each(function() {
                                            var $course = $(this);
                                            if ($course.hasClass('collapsed')) {
                                                setTimeout(function() {
                                                    if ($course.find(".moreinfo img").length > 0) {
                                                        $course.find(".moreinfo img").click();
                                                        $course.find(".moreinfo img").hide();
                                                    }
                                                }, 100);
                                            }
                                        });
                                    }
                                }, 100);
                            }
                        }, 100);
                    }
                });
            }
            // Assign category behavior to top level categories.
            $(".course_category_tree .category").each(function() {
                handle_category_behavior($(this));
            })
        }
    }

    /*
     * Managed fixed/relative position of sub-header nav bar.
     */
    function changeposition() {
        var $header = $("nav.navbar");
        var $menu = $("#totara-header-bar");
        if ($menu.length > 0) {
            // If scrolled below the header's height, then fix the postion
            // of the persistent menu, and add padding to the header to prevent
            // the content below from jumping up.
            if (window.pageYOffset >= $header.height() && !$menu.hasClass('position_fixed')) {
                $header.css('margin-bottom', $menu.height());
                $menu.addClass("position_fixed");
            }
            if (window.pageYOffset < $header.height() && $menu.hasClass('position_fixed')) {
                $header.css('margin-bottom', '0');
                $menu.removeClass("position_fixed");
            }
        }
    }

    //Check to see if the window is top if not then display button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300){
            $('.scrollToTop').addClass("show");
        } else {
            $('.scrollToTop').removeClass("show");
        }
        changeposition();
    });

    //Click event to scroll to top
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

    // Assign .back-to-grid button the same url as the breadcrumb link leading back to the parent course.
    if ($('.breadcrumb a').length > 0 && $('.back-to-grid').length > 0) {
        $('.breadcrumb a').each(function() {
            if ($(this).attr('href').indexOf('/course/view.php') > -1) {
                var gridurl = $(this).attr('href');
                $('.back-to-grid').attr('href', gridurl);
                return false;
            }
        });
    }

});
