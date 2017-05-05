/**
 * Custom JavaScript functions for the customizer preview.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */
;(function () {
    document.addEventListener('DOMContentLoaded', function () {
        /**
         * Selectice refresh check from https://github.com/xwp/wp-jetpack/blob/feature/selective-refresh-widget-support/modules/widgets/contact-info/contact-info-map.js#L35
         * @type {*}
         */
        hasSelectiveRefresh = (
            'undefined' !== typeof wp &&
            wp.customize &&
            wp.customize.selectiveRefresh &&
            wp.customize.widgetsPreview &&
            wp.customize.widgetsPreview.WidgetPartial
        );
        if (hasSelectiveRefresh) {
            wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
                buildMasonryGrid(hasSelectiveRefresh);

                addClassToImageLinks();

                fullWidthImages();
            });
        }
    });
})();
