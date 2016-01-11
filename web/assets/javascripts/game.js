var Game = function () {
    var initialized = false;

    return {
        initialize: function()
        {
            // Some stuff here
            jQuery(document).ready( function() {
                Game.headerInitialize();

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
    }
}();
