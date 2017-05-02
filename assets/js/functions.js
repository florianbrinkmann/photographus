/**
 * Custom JavaScript functions.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */
;(function () {
    document.addEventListener('DOMContentLoaded', function () {
        /**
         * Get the html element.
         *
         * @type {Element}
         */
        var root = document.documentElement;

        /**
         * Remove the no-js class
         */
        root.removeAttribute('class', 'no-js');

        /**
         * Set a js class.
         */
        root.setAttribute('class', 'js');

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
            });
        }
    });

    addClassToImageLinks();

    buildMasonryGrid();

    fullWidthImages();
})();

function addClassToImageLinks() {
    /**
     * Get the images which live inside a link.
     *
     * @type {NodeList}
     */
    var linked_images = document.querySelectorAll('a > img');

    /**
     * Loop through the images and add a class.
     */
    for (var i = 0; i < linked_images.length; i++) {
        if (linked_images[i].parentElement.className == 'img-link') {
        } else {
            linked_images[i].parentElement.classList.add('img-link');
            if (linked_images[i].parentElement.parentElement.children.length === 1) {
                linked_images[i].parentElement.parentElement.classList.add('img-link-wrapper');
            }
        }
    }
}

function buildMasonryGrid(hasSelectiveRefresh = false) {
    var msnry;

    /**
     * Get the gallery grids.
     *
     * @type {Element}
     */
    var gridElem = document.querySelector(".gallery-grid");

    /**
     * Check if we have grid elements.
     */
    if (!gridElem) {
        return
    }
    /**
     * Function for creating and destroying the masonry grids.
     */
    function masonryGrid() {
        var w = Math.max(
            document.documentElement.clientWidth,
            window.innerWidth || 0
        );

        /**
         * Only init a grid if the window is greater or equal 730
         */

        if (w >= 730 && !msnry) {
            msnry = new Masonry(gridElem, {
                itemSelector: ".gallery-grid-item",
                columnWidth: 1,
                gutter: 10,
                transitionDuration: 0,
                resize: true,
                fitWidth: true
            });
        } else if (w < 730 && msnry) {
            msnry.destroy();
            msnry = null;
        }
    }

    if (hasSelectiveRefresh) {
        window.setTimeout(masonryGrid, 1);
    }
    document.addEventListener("DOMContentLoaded", masonryGrid);
    window.addEventListener("resize", masonryGrid);
}

function fullWidthImages() {
    /**
     * Get the fullWidthImages
     *
     * @type {NodeList}
     */
    var fullWidthImages = document.querySelectorAll('.-with-sidebar .-large-featured-image-template .wp-post-image, img.size-full');

    /**
     * Add an inline style max-width to the images to not let them grow over their natural width on sidebar templates.
     */
    if (fullWidthImages.length != 0) {
        for (var fullWidthImage of fullWidthImages) {
            var naturalWidth = fullWidthImage.naturalWidth;
            if (naturalWidth > 750) {
                fullWidthImage.className += ' full-bleed-img';
                fullWidthImage.style.maxWidth = naturalWidth + 'px';
            }
        }
    }
}
