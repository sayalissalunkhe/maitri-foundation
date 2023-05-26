jQuery(document).ready(function () {
    jQuery('.past-event-slider').owlCarousel({
        loop: true,
        nav: false,
        dot: true,
        items: 1,
        singleItem: true 
    });

    jQuery('.owl-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dot: true,
        items: 2,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 2
            }
        }
    });
});

