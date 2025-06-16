(function($) {
    "use strict";

    // Solo inicializa el scrollbar si el elemento existe en la página
    if (document.querySelector('.header-dropdown-list')) {
        const ps1 = new PerfectScrollbar('.header-dropdown-list', {
            useBothWheelAxes: true,
            suppressScrollX: true,
            suppressScrollY: false,
        });
    }

    if (document.querySelector('.notifications-menu')) {
        const ps2 = new PerfectScrollbar('.notifications-menu', {
            useBothWheelAxes: true,
            suppressScrollX: true,
            suppressScrollY: false,
        });
    }

    if (document.querySelector('.message-menu-scroll')) {
        const ps3 = new PerfectScrollbar('.message-menu-scroll', {
            useBothWheelAxes: true,
            suppressScrollX: true,
            suppressScrollY: false,
        });
    }

})(jQuery);
