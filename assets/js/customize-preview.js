/**
 * Custom JavaScript functions for the customizer preview.
 *
 * @version 1.0.0
 *
 * @package Photographus
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

                /**
                 * Get the front page panels.
                 * @type {NodeList}
                 */
                var panels = document.querySelectorAll('.frontpage-section');
                /**
                 * Check if we have panels.
                 */
                if (0 !== panels.length) {
                    var panelsWithContent = 0;
                    /**
                     * Loop through them.
                     */
                    for (var i = 0; i < panels.length; i++) {
                        /**
                         * Check if it is a panel placeholder.
                         */
                        if (!panels[i].classList.contains('frontpage-section-placeholder')) {
                            panelsWithContent++;
                        }
                    }
                    /**
                     * Refresh the preview if we have only panel placeholders, so the default homepage is displayed
                     * correctly.
                     *
                     * @link https://make.xwp.co/2015/12/08/implementing-selective-refresh-in-the-customizer/
                     */
                    if (panelsWithContent === 0) {
                        wp.customize.preview.send('refresh');
                    }
                }
            });
        }
    });
})();
