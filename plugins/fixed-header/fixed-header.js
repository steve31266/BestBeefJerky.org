document.addEventListener("DOMContentLoaded", function () {
    function adjustMargin() {
        var header = document.querySelector(".site-header");
        var navigation = document.querySelector(".main-navigation");
        var content = document.querySelector(".site-content");
        var adminBar = document.querySelector("#wpadminbar");

        if (header && navigation && content) {
            var headerHeight = header.offsetHeight;
            var navHeight = navigation.offsetHeight;
            var adminBarHeight = adminBar ? adminBar.offsetHeight : 0;

            // Apply dynamic adjustments
            header.style.top = adminBarHeight + "px";
            navigation.style.top = (headerHeight + adminBarHeight) + "px";
            content.style.marginTop = (headerHeight + navHeight + adminBarHeight) + "px";
        }
    }

    adjustMargin();
    window.addEventListener("resize", adjustMargin);
});
