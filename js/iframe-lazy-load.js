var iframe_lazy_load = function() {

    function _toConsumableArray(arr) {
        if (Array.isArray(arr)) {
            for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
                arr2[i] = arr[i];
            }
            return arr2;
        } else {
            return Array.from(arr);
        }
    }

    var iframes = [].concat(_toConsumableArray(document.querySelectorAll('iframe[data-src]')));

    var interactSettings = {
        root: document.querySelector('.center'),
        rootMargin: '0px 0px 200px 0px'
    };

    function onIntersection(iframeEntites) {
        iframeEntites.forEach(function(iframe) {
            if (iframe.isIntersecting) {
                observer.unobserve(iframe.target);
                iframe.target.src = iframe.target.dataset.src;
                iframe.target.onload = function() {
                    return iframe.target.classList.add('loaded');
                };
            }
        });
    }

    var observer = new IntersectionObserver(onIntersection, interactSettings);

    iframes.forEach(function(iframe) {
        return observer.observe(iframe);
    });

}();
