var Game = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                Game.headerInitialize();
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
        townResourcesTableInitialize: function()
        {
            var townResourcesTableElement = jQuery('#town-resources-table');
            if (townResourcesTableElement.length) {
                function interval() {
                    townResourcesTableElement.find('tbody tr').each(function() {
                        var resource = jQuery(this).attr('data-resource');
                        var resourceAvailable = parseFloat(jQuery(this).attr('data-resource-available'));
                        var resourceCapacity = parseInt(jQuery(this).attr('data-resource-capacity'));
                        var resourceCapacityPercentage = parseFloat(jQuery(this).attr('data-resource-capacity-percentage'));
                        var resourceProduction = parseInt(jQuery(this).attr('data-resource-production'));

                        if (resourceProduction > 0) {
                            resourceAvailable = resourceAvailable + (resourceProduction / 60);

                            if (
                                resourceCapacity !== -1 &&
                                resourceAvailable > resourceCapacity
                            ) {
                                resourceAvailable = resourceCapacity;
                            }

                            jQuery(this).attr('data-resource-available', resourceAvailable);
                            jQuery(this).find('.resource-available').text(parseInt(resourceAvailable));
                            
                            if (
                                resourceCapacity > 0 && 
                                resourceAvailable > 0
                            ) {
                                resourceCapacityPercentage = (resourceAvailable / resourceCapacity) * 100;
                                
                                jQuery(this).attr('data-resource-capacity-percentage', resourceCapacityPercentage);
                                
                                var liveProgressElement = jQuery('.live-progress-resource-percentage[data-resource="'+resource+'"]');
                                
                                if (liveProgressElement) {
                                    liveProgressElement
                                        .find('.progress-bar')
                                        .attr('aria-valuenow', resourceCapacityPercentage)
                                        .attr('title', parseInt(resourceCapacityPercentage)+'%')
                                        .css({ width: resourceCapacityPercentage+'%' })
                                        .text(parseInt(resourceCapacityPercentage)+'%')
                                    ;
                                }
                            }
                        }
                    });
                }

                setInterval(interval, 1000);
                
                // Synchronize every 20 seconds
                setInterval(function() {
                    Game.reloadTownResources();
                }, 20000);
            }
        },
        reloadTownResources: function()
        {
            var townResourcesTableElement = jQuery('#town-resources-table');
            var townId = parseInt(townResourcesTableElement.attr('data-town-id'));

            jQuery.get(
                baseUrl+'/game/api/towns/'+townId
            ).done(function(data) {
                // When we already got the data, update stuff
                jQuery('#town-buildings-data').text(data.town_buildings.length);
                jQuery('#buildings-limit-data').text(data.buildings_limit);
                jQuery('#population-data').text(data.population);
                jQuery('#population-capacity-data').text(data.population_capacity);
                
                // Update the town resources table
                var townResources = data.resources;
                jQuery.each(townResources, function(resource, townResource) {
                    var resourceRow = jQuery('.resource-row[data-resource="'+resource+'"]');
                    var resourceAvailable = parseFloat(townResource.available);
                    var resourceCapacity = parseInt(townResource.capacity);
                    var resourceProduction = parseInt(townResource.production);
                    
                    resourceRow
                        .attr('data-resource-available', resourceAvailable)
                        .attr('data-resource-capacity', resourceCapacity)
                        .attr('data-resource-production', resourceProduction)
                    ;
                    resourceRow.find('.resource-available').text(parseInt(resourceAvailable));
                    resourceRow.find('.resource-capacity').html(
                        resourceCapacity === -1
                            ? '&infin;'
                            : parseInt(resourceCapacity)
                    );
                    resourceRow.find('.resource-production').text(parseInt(resourceProduction));
                });
            }).fail(function(response) {
                var data = response.responseJSON;
                toastr.error(data.error.message);
            });
        },
        liveProgressInitialize: function()
        {
            var liveProgress = jQuery('.live-progress');
            if (liveProgress.length) {
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

                        jQuery(this)
                            .parent()
                            .find('.seconds-until-done')
                            .text(secondsUntilDone)
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
    }
}();
