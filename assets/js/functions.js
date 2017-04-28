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
    });

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
        if (linked_images[i].parentElement.className == 'linked-img') {
        } else {
            linked_images[i].parentElement.classList.add('linked-img');
            if (linked_images[i].parentElement.parentElement.children.length === 1) {
                linked_images[i].parentElement.parentElement.classList.add('linked-img-wrapper');
            }
        }
    }

    /**
     * Get the gallery grids.
     *
     * @type {Element}
     */
    var gridElem = document.querySelector('.gallery-grid');

    /**
     * Check if we have grid elements.
     */
    if (gridElem != null) {
        /**
         * Masonry options.
         *
         * @type {{itemSelector: string, columnWidth: number, gutter: number, transitionDuration: number, resize: boolean, fitWidth: boolean}}
         */
        var masonryOptions = {
            itemSelector: '.gallery-grid-item',
            columnWidth: 1,
            gutter: 10,
            transitionDuration: 0,
            resize: true,
            isFitWidth: true
        };

        /**
         * Var for saving if we have a small window or not.
         */
        var smallWindow;

        /**
         * Function for creating and destroying the masonry grids.
         */
        function masonryGrid() {
            var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            /**
             * Only init a grid if the window is greater or equal 730
             */
            if (w >= 730) {
                new Masonry(gridElem, masonryOptions);
                smallWindow = false;
            } else {
                if (smallWindow == false) {
                    var msnry = Masonry.data(gridElem);
                    msnry.destroy();
                }
                smallWindow = true;
            }
        }

        document.addEventListener('DOMContentLoaded', masonryGrid);
        window.addEventListener('resize', masonryGrid);
    }

    /**
     * Get the fullWidthImages
     *
     * @type {NodeList}
     */
    var fullWidthImages = document.querySelectorAll('.-large-featured-image-template .wp-post-image');

    /**
     * Add an inline style max-width to the images to not let them grow over their natural width.
     */
    if (fullWidthImages.length != 0) {
        for (var fullWidthImage of fullWidthImages) {
            var naturalWidth = fullWidthImage.naturalWidth;
            fullWidthImage.style.maxWidth = naturalWidth + 'px';
        }
    }
})();
