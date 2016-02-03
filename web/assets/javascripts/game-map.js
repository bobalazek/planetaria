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
                    GameMap.reloadMap();
                    
                    jQuery('#map-construct-building').removeClass('open');
                    jQuery('#map-construct-building').fadeOut();

                    toastr.success(data.message);
                }).fail(function(response) {
                    var data = response.responseJSON;
                    toastr.error(data.error.message);
                });
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
                    
                    jQuery('#map-construct-building').fadeIn(function() {
                        jQuery(this).addClass('open');
                    });
                } else {
                    jQuery('.map-tile.map-tile-selected').removeClass('map-tile-selected');
                    jQuery('#map-construct-building').fadeOut();
                    jQuery('#map-construct-building').removeClass('open');
                }
            });
        },
    }
}();
