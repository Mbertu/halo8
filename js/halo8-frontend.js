(function($) {
    'use strict';
    $(function() {
        if (typeof halo8_config_object !== 'undefined') {
            if (halo8_config_object.slides.length) {
                if (halo8_config_object.position === 'background') {
                    $('body').vegas(halo8_config_object);
                } else {
                    var halo8_container = $(halo8_config_object.container_selector);

                    if (halo8_container.length === 0) {
                        return;
                    }
                    halo8_container.empty();
                    halo8_container.append($('<div />'));
                    var vegas_container = $(halo8_config_object.container_selector + ' div');

                    // var height = $(window).height();
                    // var admin_bar_height = $('#wpadminbar').height();
                    // if(admin_bar_height)
                    // height = height - admin_bar_height;
                    // $('.site-header').height(height);

                    //vegas_container.css('padding-bottom','50%');

                    if (halo8_config_object.show_controls && halo8_config_object.slides.length > 1) {
                        var controls_container = $('<div />');
                        controls_container.addClass('slider_controls_container');
                        var controls_inner_container = $('<div />');
                        controls_inner_container.addClass('slider_controls_inner_container');
                        var controls_cell_container = $('<div />');
                        controls_cell_container.addClass('slider_controls_cell');
                        var left_arrow = $('<div />');
                        var right_arrow = $('<div />');
                        left_arrow.addClass('left');
                        right_arrow.addClass('right');
                        controls_cell_container.append(left_arrow);
                        controls_cell_container.append(right_arrow);
                        controls_inner_container.append(controls_cell_container);
                        controls_container.append(controls_inner_container);
                        vegas_container.append(controls_container);

                        $(document).on('click', '.slider_controls_container .left', function() {
                            vegas_container.vegas('previous');
                        });

                        $(document).on('click', '.slider_controls_container .right', function() {
                            vegas_container.vegas('next');
                        });
                    }

                    halo8_config_object.walk = function(index, slideSettings)
                    {
                        if (halo8_config_object.show_controls && halo8_config_object.slides.length > 1)
                        {
                            var dom_element_paginator = halo8_container.find('.slider_paginator_container');
                            if (dom_element_paginator.length === 0) {
                                var paginator_container = $('<div />');
                                paginator_container.addClass('slider_paginator_container');
                                halo8_container.append(paginator_container);
                                dom_element_paginator = halo8_container.find('.slider_paginator_container');
                            }


                            dom_element_paginator.html('');
                            for (var i = 0; i < halo8_config_object.slides.length; i++) {
                                var paginator = $('<span />');
                                paginator.addClass('slider_paginator');
                                paginator.attr('slide_index', i);
                                if (parseInt(vegas_container.vegas('current')) === i) {
                                    paginator.addClass('current');
                                }
                                dom_element_paginator.append(paginator);
                            }

                            $(document).on('click', '.slider_paginator', function() {
                                vegas_container.vegas('jump', $(this).attr('slide_index'));
                            });
                        }

                        var description_dom_element = halo8_container.find('.slide_description_container');
                        if(slideSettings.description){
                            if (!description_dom_element.length) {
                                var description_container = $('<div />');
                                description_container.addClass('slide_description_container');
                                halo8_container.append(description_container);
                                description_dom_element = halo8_container.find('.slide_description_container');
                            }
                        }else{
                            if (description_dom_element.length) {
                                description_dom_element.remove();
                            }
                        }

                        var slide_link_dom_element = halo8_container.find('.slide_full_link');
                        if (slide_link_dom_element) {
                            slide_link_dom_element.remove();
                        }

                        if (slideSettings.link) {
                            slide_link_dom_element = $('<a/>');
                            slide_link_dom_element.attr('href', slideSettings.link);
                            if (slideSettings.blank) {
                                slide_link_dom_element.attr('target', '_blank');
                            }
                        }

                        if (slideSettings.description) {
                            description_dom_element.html('');
                            var description_inner_container = $('<div />');
                            description_inner_container.addClass('slide_description_outer');
                            description_dom_element.append(description_inner_container);
                            description_dom_element = halo8_container.find('.slide_description_outer');

                            description_dom_element.html('');
                            description_inner_container = $('<div />');
                            description_inner_container.addClass('slide_description_inner');
                            description_dom_element.append(description_inner_container);
                            description_dom_element = halo8_container.find('.slide_description_inner');

                            description_dom_element.html('');
                            description_inner_container = $('<div />');
                            description_inner_container.addClass('slide_description');
                            description_dom_element.append(description_inner_container);
                            description_dom_element = halo8_container.find('.slide_description');

                            description_dom_element.text(slideSettings.description);

                            if(slideSettings.link && slide_link_dom_element){
                                description_inner_container.wrapInner(slide_link_dom_element);
                            }
                        } else {
                            description_dom_element.html('');
                            if(slide_link_dom_element){
                                halo8_container.append(slide_link_dom_element);
                                slide_link_dom_element.addClass('slide_full_link');
                            }
                        }
                    };
                    vegas_container.vegas(halo8_config_object);
                }
            }
        }
    });
}(jQuery));
