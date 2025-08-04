document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const siteNavigation = document.querySelector('#site-navigation');

    if (menuToggle && siteNavigation) {
        menuToggle.addEventListener('click', function () {
            siteNavigation.classList.toggle('hidden');
        });
    }
});
