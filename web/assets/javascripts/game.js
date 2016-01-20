var Game = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                Game.headerInitialize();
                Game.mapInitialize();
                Game.townResourcesTableInitialize();
                Game.liveProgressInitialize();

                initialized = true;
                console.log('Game Initialized');
            });
        },
        headerInitialize: function()
        {
            function clock() {
                var time = moment().format('HH:mm:ss');
                jQuery('#header-time span').text(time);
            }
            clock();
            
            setInterval(clock, 1000);
        },
        mapInitialize: function()
        {
            var mapElement = jQuery('#map');
            var mapInnerElement = jQuery('#map-inner');
            
            // Activate nicescroll
            mapElement.niceScroll({
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
            
            // If any active popover, disable him!
            mapElement.on('scroll', function() {
                if (jQuery('.popover.in').length) {
                    jQuery('.popover-click').popover('hide');
                }
            });
        },
        townResourcesTableInitialize: function()
        {
            var townResourcesTableElement = jQuery('#town-resources-table');
            
            if (townResourcesTableElement.length) {
                function interval() {
                    townResourcesTableElement.find('tbody tr').each(function() {
                        var resource = jQuery(this).attr('data-resource');
                        var resourceAvailable = parseFloat(jQuery(this).attr('data-resource-available'));
                        var resourceCapacity = parseInt(jQuery(this).attr('data-resource-capacity'));
                        var resourceProduction = parseInt(jQuery(this).attr('data-resource-production'));

                        if (
                            resourceProduction > 0 &&
                            resourceAvailable < resourceCapacity 
                        ) {
                            resourceAvailable = resourceAvailable + (resourceProduction / 60);
                            jQuery(this).attr('data-resource-available', resourceAvailable);
                            jQuery(this).find('.resource-available').text(parseInt(resourceAvailable));
                        }
                    });
                }
                
                setInterval(interval, 1000);
            }
        },
        liveProgressInitialize: function()
        {
            var liveProgress = jQuery('.live-progress');
            
            function interval() {
                liveProgress.each(function() {
                    var percentage = parseFloat(jQuery(this).attr('data-percentage'));
                    var percentagePerSecond = parseFloat(jQuery(this).attr('data-percentage-per-second'));
                    var secondsUntilDone = parseInt(jQuery(this).attr('data-seconds-until-done'));
                    
                    percentage = percentage + percentagePerSecond;
                    secondsUntilDone--;

                    if (percentage > 100) {
                        percentage = 100;
                    }
                    
                    jQuery(this)
                        .attr('data-percentage', percentage)
                        .attr('data-seconds-until-done', secondsUntilDone)
                    ;
                    
                    jQuery(this)
                        .find('.progress-bar')
                        .attr('aria-valuenow', percentage)
                        .css({ width: percentage+'%' })
                        .text(parseInt(percentage)+'%')
                    ;
                    
                    if (
                        jQuery.isNumeric(secondsUntilDone) &&
                        secondsUntilDone <= 0
                    ) {
                        window.location.reload();
                    }
                });
            }
            
            setInterval(interval, 1000);
        }
    }
}();
