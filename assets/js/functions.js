/**
 * Custom JavaScript functions.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */
;(function () {
    var linked_images = document.querySelectorAll('a > img');

    for (var i = 0; i < linked_images.length; i++) {
        if (linked_images[i].parentElement.className == 'linked-img') {
        } else {
            linked_images[i].parentElement.classList.add('linked-img');
            if (linked_images[i].parentElement.parentElement.children.length === 1) {
                linked_images[i].parentElement.parentElement.classList.add('linked-img-wrapper');
            }
        }
    }
})();
