var Game = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                Game.headerInitialize();
                Game.mapInitialize();

                initialized = true;
                console.log('Game Initialized');
            });
        },
        headerInitialize: function()
        {
            function tikTak() {
                var time = moment().format('HH:mm:ss');
                jQuery('#header-time span').text(time);
            }
            tikTak();
            
            setInterval(tikTak, 1000);
        },
        mapInitialize: function()
        {
            jQuery('#map').niceScroll({
                touchbehavior: true,
                preventmultitouchscrolling: false, 
            });
            
            var mapElement = jQuery('#map');
            var mapHeight = mapElement.outerHeight();
            var mapWidth = mapElement.outerWidth();
            
            var mapInnerElement = jQuery('#map-inner');
            var mapInnerHeight = mapInnerElement.outerHeight();
            var mapInnerWidth = mapInnerElement.find('> div').outerWidth(); // Hack
            
            var scrollTop = (mapInnerHeight / 2) - (mapHeight / 2);
            var scrollLeft = (mapInnerWidth / 2) - (mapWidth / 2);
            
            mapElement.scrollTop(scrollTop);
            mapElement.scrollLeft(scrollLeft);
        },
    }
}();
