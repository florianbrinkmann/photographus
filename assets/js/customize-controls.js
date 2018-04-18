/**
 * Custom JavaScript functions for the customizer which change things in the customizer sidebar.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */
;(function () {
    wp.customize.bind('ready', function () {
        /**
         * Get the content type controls for looping them.
         * @type {NodeList}
         */
        var ContentTypeControls = document.querySelectorAll('#sub-accordion-section-photographus_options li[id$="content_type"]');
        // Check if we have content type controls.
        if (0 !== ContentTypeControls.length) {
            // Loop through them.
            for (var i = 1; i < ContentTypeControls.length + 1; i++) {
                wp.customize.control('photographus_panel_' + i + '_content_type', function (control) {
                    // Bind change of setting.
                    control.setting.bind(function (value) {
                        /**
                         * Match the panel number in the control ID.
                         * @type {Array|null}
                         */
                        var panelNumber = control.id.match(/photographus_panel_(\d)_content_type/);
                        // Check if we have a match.
                        if (null !== panelNumber) {
                            // If match, the second value of the Array is the panel number.
                            panelNumber = panelNumber[1];
                            switch (value) {
                                // The content type was switched to the default »— Select —« state
                                case '0':
                                    /**
                                     * Build array with controls which needs to be deactivated.
                                     * @type {[*]}
                                     */
                                    var controlsHideArray = [
                                        '_page',
                                        '_post',
                                        '_latest_posts_title',
                                        '_latest_posts_number',
                                        '_latest_posts_short_version',
                                        '_post_grid_title',
                                        '_post_grid_number',
                                        '_post_grid_hide_title',
                                        '_post_grid_only_gallery_and_image_posts',
                                    ];

                                    // Loop through the array and deactivate all controls.
                                    controlsHideArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).deactivate();
                                    });
                                    break;
                                // The content type was switched to »post«.
                                case 'post':
                                    /**
                                     * Build array with controls which needs to be deactivated.
                                     * @type {[*]}
                                     */
                                    var controlsHideArray = [
                                        '_page',
                                        '_latest_posts_title',
                                        '_latest_posts_number',
                                        '_latest_posts_short_version',
                                        '_post_grid_title',
                                        '_post_grid_number',
                                        '_post_grid_hide_title',
                                        '_post_grid_only_gallery_and_image_posts',
                                    ];

                                    // Loop through the array and deactivate all controls.
                                    controlsHideArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).deactivate();
                                    });

                                    // Activate the post control.
                                    wp.customize.control('photographus_panel_' + panelNumber + '_post').activate();
                                    break;
                                // The content type was switched to »page«.
                                case 'page':
                                    /**
                                     * Build array with controls which needs to be deactivated.
                                     * @type {[*]}
                                     */
                                    var controlsHideArray = [
                                        '_post',
                                        '_latest_posts_title',
                                        '_latest_posts_number',
                                        '_latest_posts_short_version',
                                        '_post_grid_title',
                                        '_post_grid_number',
                                        '_post_grid_hide_title',
                                        '_post_grid_only_gallery_and_image_posts',
                                    ];

                                    // Loop through the array and deactivate all controls.
                                    controlsHideArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).deactivate();
                                    });

                                    // Activate the page control.
                                    wp.customize.control('photographus_panel_' + panelNumber + '_page').activate();
                                    break;
                                // The content type was switched to »Latest Posts«.
                                case 'latest-posts':
                                    /**
                                     * Build array with controls which needs to be deactivated.
                                     * @type {[*]}
                                     */
                                    var controlsHideArray = [
                                        '_post',
                                        '_page',
                                        '_post_grid_title',
                                        '_post_grid_number',
                                        '_post_grid_hide_title',
                                        '_post_grid_only_gallery_and_image_posts',
                                    ];

                                    // Loop through the array and deactivate the controls.
                                    controlsHideArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).deactivate();
                                    });

                                    /**
                                     * Build array with controls which needs to be activated.
                                     * @type {[*]}
                                     */
                                    var controlsShowArray = [
                                        '_latest_posts_title',
                                        '_latest_posts_number',
                                        '_latest_posts_short_version',
                                    ];

                                    // Loop through the array and activate the controls.
                                    controlsShowArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).activate();
                                    });
                                    break;
                                // The content type was switched to »Post Grid«.
                                case 'post-grid':
                                    /**
                                     * Build array with controls which needs to be deactivated.
                                     * @type {[*]}
                                     */
                                    var controlsHideArray = [
                                        '_post',
                                        '_page',
                                        '_latest_posts_title',
                                        '_latest_posts_number',
                                        '_latest_posts_short_version',
                                    ];

                                    // Loop through the array and deactivate the controls.
                                    controlsHideArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).deactivate();
                                    });

                                    /**
                                     * Build array with controls which needs to be activated.
                                     * @type {[*]}
                                     */
                                    var controlsShowArray = [
                                        '_post_grid_title',
                                        '_post_grid_number',
                                        '_post_grid_hide_title',
                                        '_post_grid_only_gallery_and_image_posts',
                                    ];

                                    // Loop through the array and activate the controls.
                                    controlsShowArray.forEach(function (value, index, array) {
                                        wp.customize.control('photographus_panel_' + panelNumber + value).activate();
                                    });
                                    break;
                            }
                        }
                    });
                });
            }
        }
    });
})();
