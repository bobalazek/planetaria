var GameMap = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                GameMap.mapInitialize();

                initialized = true;
                console.log('Game Map Initialized');
            });
        },
        mapInitialize: function()
        {
            GameMap.reloadMap();

            // Map controls
            jQuery('#map-controls .btn').on('click', function() {
                var x = jQuery('#map-controls-x').val();
                var y = jQuery('#map-controls-y').val();

                currentUrl = updateUrlParameter(currentUrl, 'x', x);
                currentUrl = updateUrlParameter(currentUrl, 'y', y);

                GameMap.reloadMap();

                return false;
            });

            // Map handle for buildings sidebar
            jQuery('#map-construct-building-handle').on('click', function() {
                jQuery('#map-construct-building').toggleClass('open');
            });

            // Construct building sidebar
            var townsListActiveElement = jQuery('#towns-list .active');
            var townId = townsListActiveElement.attr('data-id');
            var planetId = townsListActiveElement.attr('data-planet-id');

            jQuery('.btn-construct-building').on('click', function() {
                var buildingElement = jQuery(this).parent();
                var selectedMapTileElement = jQuery('.map-tile.map-tile-selected');
                var x = selectedMapTileElement.attr('data-x');
                var y = selectedMapTileElement.attr('data-y');
                var building = buildingElement.attr('data-key');

                jQuery.get(
                    baseUrl+'/game/api/map/'+planetId+
                    '/build?x='+x+'&y='+y+'&town_id='+townId+'&building='+building
                ).done(function(data) {
                    currentUrl = updateUrlParameter(currentUrl, 'x', x);
                    currentUrl = updateUrlParameter(currentUrl, 'y', y);

                    GameMap.reloadMap();
                    GameMap.reloadMapSidebar(data.building_checks);
                    Game.reloadTownResources();

                    jQuery('#map-construct-building').removeClass('open');
                    jQuery('#map-construct-building').fadeOut();

                    jQuery('#town-no-buildings-alert').fadeOut();

                    var townBuildings = parseInt(jQuery('#town-buildings-data').text());
                    jQuery('#town-buildings-data').text(townBuildings+1);

                    toastr.success(data.message);
                }).fail(function(response) {
                    var data = response.responseJSON;
                    toastr.error(data.error.message);
                });
            });
            
            // Full screen
            jQuery('#map-full-screen-mode-toggle-button').on('click', function() {
                jQuery('#body').toggleClass('map-full-screen');
                
                var isFullScreen = jQuery('#body').hasClass('map-full-screen');
                if (isFullScreen) {
                    jQuery(this)
                        .find('i')
                        .removeClass('fa-expand')
                        .addClass('fa-compress')
                    ;
                } else {
                    jQuery(this)
                        .find('i')
                        .removeClass('fa-compress')
                        .addClass('fa-expand')
                    ;
                }
            })

            // Show types
            jQuery('#map-construct-building-content-building-types-list a').on('click', function() {
                var type = jQuery(this).attr('data-type');

                jQuery('#map-construct-building-content-building-types-list li').removeClass('active');
                jQuery(this).parent().addClass('active');

                if (type === '*') {
                    jQuery('#map-construct-building-content-buildings .building').fadeIn();
                } else {
                    jQuery('#map-construct-building-content-buildings .building:not([data-type="'+type+'"])').fadeOut();
                    jQuery('#map-construct-building-content-buildings .building[data-type="'+type+'"]').fadeIn();
                }

                return false;
            });

            // Helper functions
            function updateUrlParameter(url, param, value){
                var regex = new RegExp('('+param+'=)[^\&]+');
                return url.replace( regex , '$1' + value);
            }
        },
        reloadMap: function() {
            jQuery('#map-overlay').fadeIn();

            // Hack
            jQuery('.tooltip, .popover').remove()

            currentUrl = currentUrl.replace(/&amp;/g, '&');

            jQuery('#map').load(currentUrl + ' #map-inner', function() {
                jQuery('#map-overlay').fadeOut();

                var x = jQuery('#map-inner').attr('data-center-x');
                var y = jQuery('#map-inner').attr('data-center-y');
                jQuery('h2 small').text('('+x+','+y+')');

                GameMap.onMapInitialized();
            });
        },
        reloadMapSidebar: function(buildingChecks) {
            jQuery('#map-construct-building-content-buildings .building').each(function() {
                var building = jQuery(this).attr('data-key');
                var buildingCheck = buildingChecks[building];

                if (buildingCheck === true) {
                    jQuery(this)
                        .find('.building-overlay')
                        .fadeOut()
                    ;
                } else {
                    jQuery(this)
                        .find('.building-overlay')
                        .fadeIn()
                        .text(buildingCheck)
                    ;
                }
            });
        },
        onMapInitialized: function() {
            Application.tooltipsAndPopoversInitialize();

            var mapElement = jQuery('#map');
            var mapInnerElement = jQuery('#map-inner');

            // Set the width of the inner map
            var firstMapRow = mapInnerElement.find('.map-row:first');
            if (firstMapRow.length) {
                var firstMapRowElements = firstMapRow.find('.map-tile');
                var elementWidth = firstMapRowElements.outerWidth();

                mapInnerElement.css('width', firstMapRowElements.length * elementWidth);
            }

            // Activate nicescroll
            mapElement.niceScroll({
                touchbehavior: true,
                preventmultitouchscrolling: false,
            });

            // Map construct
            jQuery('#map-construct-building-content').niceScroll({
                touchbehavior: true,
                preventmultitouchscrolling: false,
            });

            // Get heights, widths and calculate scroll ofsets
            var mapHeight = mapElement.outerHeight();
            var mapWidth = mapElement.outerWidth();
            var mapInnerHeight = mapInnerElement.outerHeight();
            var mapInnerWidth = mapInnerElement.find('> div').outerWidth(); // Hack
            var scrollTop = (mapInnerHeight / 2) - (mapHeight / 2);
            var scrollLeft = (mapInnerWidth / 2) - (mapWidth / 2);
            // Set to center
            mapElement.scrollTop(scrollTop);
            mapElement.scrollLeft(scrollLeft);

            // If any active / shown popover, disable it when scrolling!
            mapElement.on('scroll', function() {
                if (jQuery('.popover.in').length) {
                    jQuery('.popover-click').popover('hide');
                }
            });

            // Center map button
            jQuery('.map-tile').on('shown.bs.popover', function() {
                jQuery('.btn-center-map').on('click', function() {
                    currentUrl = jQuery(this).attr('href');
                    var x = jQuery(this).attr('data-x');
                    var y = jQuery(this).attr('data-y');

                    GameMap.reloadMap();

                    return false;
                });
            });

            jQuery('.map-tile').on('click', function() {
                if (
                    !jQuery(this).hasClass('map-tile-selected') &&
                    jQuery(this).attr('data-occupied') == 'false'
                ) {
                    jQuery('.map-tile.map-tile-selected').removeClass('map-tile-selected');
                    jQuery(this).addClass('map-tile-selected');

                    // If we have already shown the sidebar (and then closed again),
                    // don't show again (there's probably a reason why we have closed it)!
                    if (!jQuery('#map-construct-building').is(":visible")) {
                        jQuery('#map-construct-building').fadeIn(function() {
                            jQuery(this).addClass('open');
                        });
                    }

                    jQuery('#map-construct-building .building').on({
                        mouseenter: function() {
                            var size = jQuery(this).attr('data-size');
                            var building = jQuery(this).attr('data-key');
                            var buildingSlug = jQuery(this).attr('data-slug');

                            if (size === '1x1') {
                                jQuery('.map-tile.map-tile-selected')
                                    .addClass('map-tile-building-tiles')
                                    .append(
                                        jQuery('<img />')
                                            .addClass('remove-after-hover-out img-responsive')
                                            .attr(
                                                'src',
                                                baseUrl+'/assets/images/buildings/'+
                                                buildingSlug+'/operational/'+size+'.png'
                                            )
                                            .css('opacity', 0.6)
                                    )
                                ;
                            } else {
                                var coordinates = [];
                                var selectedElement = jQuery('.map-tile.map-tile-selected');
                                var sizeX = size.split('x')[0];
                                var sizeY = size.split('x')[1];
                                var startX = parseInt(selectedElement.attr('data-x'));
                                var startY = parseInt(selectedElement.attr('data-y'));
                                var x = startX;
                                var y = startY;

                                for (var i = 1; i <= sizeY; i++) {
                                    x = startX;

                                    for (var j = 1; j <= sizeX; j++) {
                                        coordinates.push({
                                            x: x,
                                            y: y,
                                            buildingSectionX: j,
                                            buildingSectionY: i,
                                        });

                                        x++;
                                    }

                                    y++;
                                }

                                for (var i = 0; i < coordinates.length; i++) {
                                    var coordinate = coordinates[i];
                                    var dataCoordinates = coordinate.x + ',' + coordinate.y;
                                    size = coordinate.buildingSectionX + 'x' + coordinate.buildingSectionY;

                                    jQuery('.map-tile[data-coordinates="'+dataCoordinates+'"]')
                                        .addClass('map-tile-building-tiles')
                                        .append(
                                            jQuery('<img />')
                                                .addClass('remove-after-hover-out img-responsive')
                                                .attr(
                                                    'src',
                                                    baseUrl+'/assets/images/buildings/'+
                                                    buildingSlug+'/operational/'+size+'.png'
                                                )
                                                .css('opacity', 0.6)
                                        )
                                    ;
                                }
                            }
                        },
                        mouseleave: function() {
                            jQuery('.remove-after-hover-out').remove();
                            jQuery('.map-tile.map-tile-building-tiles')
                                .removeClass('map-tile-building-tiles')
                            ;
                        },
                    });
                } else {
                    jQuery('.map-tile.map-tile-selected').removeClass('map-tile-selected');
                    jQuery('#map-construct-building').fadeOut();
                    jQuery('#map-construct-building').removeClass('open');
                }
            });
            
            // Check for constructing images on map
            jQuery('.map-tile-town-building-constructing').each(function() {
                var imageElement = jQuery(this).find('.map-tile-building-image');
                var imageUrl = jQuery(this).attr('data-town-building-image-url');
                var secondsUntilDone = parseInt(
                    jQuery(this).attr('data-town-building-constructing-seconds-until-done')
                );

                setTimeout(function() {
                    imageElement.attr('src', imageUrl);
                }, secondsUntilDone*1000);
            });
        },
    }
}();
