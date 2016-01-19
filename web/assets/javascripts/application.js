var Application = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                Application.tooltipsAndPopoversInitialize();
                Application.timeAgoInitialize();
                Application.paginatorInitialize();
                Application.postMetasInitialize();
                Application.districtResourcesInitialize();
                Application.townResourcesInitialize();
                Application.buildingResourcesInitialize();
                Application.unitResourcesInitialize();
                Application.itemResourcesInitialize();
                Application.listActionsInitialize();
                Application.slugsInitialize();
                Application.confirmInitialize();

                jQuery('#preloader').fadeOut(); // Hide preloader, when everything is ready...

                initialized = true;
                console.log('Application initialized');
            });
        },
        tooltipsAndPopoversInitialize: function() {
            jQuery('[data-toggle="popover"], .popover-hover').popover({
                html : true,
                trigger: 'hover',
                title: function() {
                    return jQuery(this).attr('data-popover-title');
                },
                content: function() {
                    return jQuery(this).attr('data-popover-content');
                },
            });
            jQuery('[data-toggle="tooltip"], .tooltip-hover').tooltip({
                html : true,
                trigger: 'hover',
                title: function() {
                    return jQuery(this).attr('data-tooltip');
                },
            });
            
            jQuery('.popover-click').popover({
                html : true,
                trigger: 'click',
                title: function() {
                    return jQuery(this).attr('data-popover-title');
                },
                content: function() {
                    return jQuery(this).attr('data-popover-content');
                },
            });
            
            jQuery('.popover-click').on('click', function() {
                jQuery('.popover-click').not(this).popover('hide');
            });
        },
        timeAgoInitialize: function() {
            function updateTime() {
                var now = moment();

                jQuery('time.time-ago').each( function() {
                    var time = moment(jQuery(this).attr('datetime'));

                    jQuery(this).text(time.from(now));
                });
            }

            updateTime();

            setInterval(updateTime, 10000);
        },
        paginatorInitialize: function() {
            var currentUrl = window.location.href;
            var limitPerPageParameter = 'limit_per_page';
            var pageParameter = 'page';
            var searchParameter = 'search';
            var url = new URI(currentUrl);

            if (jQuery('#paginator-limit-per-page-select').length) {
                jQuery('#paginator-limit-per-page-select').on('change', function() {
                    var value = jQuery(this).val();

                    url.removeQuery(limitPerPageParameter);
                    url.addQuery(limitPerPageParameter, value);

                    url.removeQuery(pageParameter);
                    url.addQuery(pageParameter, 1);

                    window.location.href = url.toString();
                });
            }

            if (jQuery('#paginator-search-input').length) {
                jQuery('#paginator-search-button').on('click', function() {
                    var value = jQuery('#paginator-search-input').val();

                    url.removeQuery(searchParameter);
                    url.addQuery(searchParameter, value);

                    window.location.href = url.toString();
                });

                jQuery('#paginator-search-clear-button').on('click', function() {
                    var value = '';

                    url.removeQuery(searchParameter);

                    window.location.href = url.toString();
                });
            }
        },
        postMetasInitialize: function() {
            var postMetasCount = jQuery('#postMetas-fields-list li').length;
            
            jQuery('#new-post-meta').on('click', function(e) {
                e.preventDefault();
                var postMetas = jQuery('#postMetas-fields-list');
                var newWidget = postMetas.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, postMetasCount);
                postMetasCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-post-meta-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(postMetas);
                initializeRemovePostMetaButton();
            });
            
            function initializeRemovePostMetaButton() {
                jQuery('.remove-post-meta-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    postMetasCount--;
                });
            }
            initializeRemovePostMetaButton();
        },
        districtResourcesInitialize: function() {
            var districtResourcesCount = jQuery('#districtResources-fields-list li').length;
            
            jQuery('#new-district-resource').on('click', function(e) {
                e.preventDefault();
                var districtResources = jQuery('#districtResources-fields-list');
                var newWidget = districtResources.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, districtResourcesCount);
                districtResourcesCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-district-resource-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(districtResources);
                initializeRemoveDistrictResourceButton();
            });
            
            function initializeRemoveDistrictResourceButton() {
                jQuery('.remove-district-resource-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    districtResourcesCount--;
                });
            }
            initializeRemoveDistrictResourceButton();
        },
        townResourcesInitialize: function() {
            var townResourcesCount = jQuery('#townResources-fields-list li').length;
            
            jQuery('#new-town-resource').on('click', function(e) {
                e.preventDefault();
                var townResources = jQuery('#townResources-fields-list');
                var newWidget = townResources.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, townResourcesCount);
                townResourcesCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-town-resource-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(townResources);
                initializeRemoveTownResourceButton();
            });
            
            function initializeRemoveTownResourceButton() {
                jQuery('.remove-town-resource-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    townResourcesCount--;
                });
            }
            initializeRemoveTownResourceButton();
        },
        buildingResourcesInitialize: function() {
            var buildingResourcesCount = jQuery('#buildingResources-fields-list li').length;
            
            jQuery('#new-building-resource').on('click', function(e) {
                e.preventDefault();
                var buildingResources = jQuery('#buildingResources-fields-list');
                var newWidget = buildingResources.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, buildingResourcesCount);
                buildingResourcesCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-building-resource-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(buildingResources);
                initializeRemoveBuildingResourceButton();
            });
            
            function initializeRemoveBuildingResourceButton() {
                jQuery('.remove-building-resource-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    buildingResourcesCount--;
                });
            }
            initializeRemoveBuildingResourceButton();
        },
        unitResourcesInitialize: function() {
            var unitResourcesCount = jQuery('#unitResources-fields-list li').length;
            
            jQuery('#new-unit-resource').on('click', function(e) {
                e.preventDefault();
                var unitResources = jQuery('#unitResources-fields-list');
                var newWidget = unitResources.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, unitResourcesCount);
                unitResourcesCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-unit-resource-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(unitResources);
                initializeRemoveUnitResourceButton();
            });
            
            function initializeRemoveUnitResourceButton() {
                jQuery('.remove-unit-resource-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    unitResourcesCount--;
                });
            }
            initializeRemoveUnitResourceButton();
        },
        itemResourcesInitialize: function() {
            var itemResourcesCount = jQuery('#itemResources-fields-list li').length;
            
            jQuery('#new-item-resource').on('click', function(e) {
                e.preventDefault();
                var itemResources = jQuery('#itemResources-fields-list');
                var newWidget = itemResources.attr('data-prototype');
                newWidget = newWidget.replace(/__name__/g, itemResourcesCount);
                itemResourcesCount++;
                var newLi = jQuery('<li></li>').html(
                    newWidget+
                    '<div class="clearfix">' +
                        '<div class="pull-right">' +
                            '<a class="btn btn-xs btn-danger remove-item-resource-button"' +
                                'href="#">' +
                                '<i class="fa fa-times"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
                );
                newLi.appendTo(itemResources);
                initializeRemoveItemResourceButton();
            });
            
            function initializeRemoveItemResourceButton() {
                jQuery('.remove-item-resource-button').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).closest('li').remove();
                    itemResourcesCount--;
                });
            }
            initializeRemoveItemResourceButton();
        },
        listActionsInitialize: function() {
            jQuery('#check-all-checkbox').on('click', function() {
                var isChecked = jQuery(this).is(':checked');

                if(isChecked) {
                    jQuery('.object-checkbox').prop('checked', true);
                } else {
                    jQuery('.object-checkbox').prop('checked', false);
                }
            });
            
            jQuery('#remove-selected-button').on('click', function() {
                var hasIds = false;
                var ids = [];

                jQuery('.object-checkbox').each(function() {
                    var isChecked = jQuery(this).is(':checked');

                    if(isChecked) {
                        hasIds = true;

                        ids.push(jQuery(this).attr('value'));
                    }
                });

                if(hasIds) {
                    window.location.href = jQuery(this).attr('href')+'?ids='+ids.join(',');

                    return false;
                }
            });
            
            jQuery('.object-checkbox').on('click', function() {
                var all = jQuery('.object-checkbox').length;
                var checked = jQuery('.object-checkbox:checked').length;

                if (all == checked) {
                    jQuery('#check-all-checkbox').prop('checked', true);
                    jQuery('#check-all-checkbox').prop('indeterminate', false);
                } else if(checked == 0) {
                    jQuery('#check-all-checkbox').prop('checked', false);
                    jQuery('#check-all-checkbox').prop('indeterminate', false);
                } else {
                    jQuery('#check-all-checkbox').prop('checked', false);
                    jQuery('#check-all-checkbox').prop('indeterminate', true);
                }
            });
        },
        slugsInitialize: function() {
            var nameEl = jQuery('[id$=_name]');
            var slugEl = jQuery('[id$=_slug]');

            if (slugEl.length) {
                nameEl.on('keyup', function() {
                    var value = jQuery(this).val();
                    
                    slugEl.val(Application.slugify(value));
                })
            }
        },
        slugify: function(text) {
            // https://gist.github.com/mathewbyrne/1280286
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        },
        confirmInitialize: function() {
            jQuery('.confirm-alert').on('click', function() {
                var href = jQuery(this).attr('href');
                var text = jQuery(this).attr('data-confirm-text');
                
                if (confirm(text)) {
                    window.location.href = href;
                }
                
                return false;
            });
        },
    }
}();
