/**
 * Google Translate Custom Dropdown & Banner Suppressor
 * Completely suppresses Google's top banner bar and keeps layout perfectly aligned
 */

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'id',
        autoDisplay: false,
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

(function () {
    // 1. Force remove Google top banner & reset body top offset
    function suppressGoogleBanner() {
        if (document.body) {
            if (document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.top = '0px';
            }
            if (document.body.style.position === 'relative') {
                document.body.style.position = 'static';
            }
        }

        // Hide any banner iframes that Google injects dynamically
        var selectors = [
            '.goog-te-banner-frame',
            'iframe.skiptranslate',
            '.VIpgJd-ZVi9C-bOHAno-CobnVe',
            '.VIpgJd-ZVi9C-OR9Cathfa-b12me',
            '#goog-gt-tt',
            '.goog-te-balloon-frame'
        ];

        selectors.forEach(function (sel) {
            var els = document.querySelectorAll(sel);
            els.forEach(function (el) {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
                el.style.height = '0';
                el.style.width = '0';
                el.style.position = 'absolute';
                el.style.top = '-9999px';
            });
        });
    }

    // Run suppression continuously via MutationObserver
    if (window.MutationObserver) {
        var observer = new MutationObserver(function () {
            suppressGoogleBanner();
        });

        document.addEventListener('DOMContentLoaded', function () {
            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['style', 'class'],
                childList: true
            });
            suppressGoogleBanner();
        });
    }

    // Fallback periodic check for first few seconds of page load
    var intervalCount = 0;
    var bannerInterval = setInterval(function () {
        suppressGoogleBanner();
        intervalCount++;
        if (intervalCount > 30) { // check for 6 seconds
            clearInterval(bannerInterval);
        }
    }, 200);

    // 2. Read current active language from googtrans cookie
    function getGoogleTransCookie() {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var c = cookies[i].trim();
            if (c.indexOf('googtrans=') === 0) {
                var val = c.substring('googtrans='.length);
                var parts = val.split('/');
                return parts[parts.length - 1] || 'id';
            }
        }
        return 'id';
    }

    // 3. Language Switch Function
    window.changeSiteLanguage = function (langCode) {
        if (!langCode || langCode === 'id') {
            // Clear translation cookies to reset to original Bahasa Indonesia
            var hostname = window.location.hostname;
            var domains = ['', hostname, '.' + hostname];
            for (var i = 0; i < domains.length; i++) {
                var d = domains[i] ? '; domain=' + domains[i] : '';
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/' + d;
            }
            window.location.reload();
            return;
        }

        // Set googtrans cookie: /source_lang/target_lang
        var hostname = window.location.hostname;
        var cookieVal = '/id/' + langCode;
        document.cookie = 'googtrans=' + cookieVal + '; path=/;';
        document.cookie = 'googtrans=' + cookieVal + '; path=/; domain=' + hostname + ';';
        document.cookie = 'googtrans=' + cookieVal + '; path=/; domain=.' + hostname + ';';

        // Trigger Google Translate select combo if available
        var combo = document.querySelector('.goog-te-combo');
        if (combo) {
            combo.value = langCode;
            combo.dispatchEvent(new Event('change'));
        }

        // Reload so translation renders seamlessly without Google banner
        window.location.reload();
    };

    // 4. Update custom UI dropdown state on page load
    document.addEventListener('DOMContentLoaded', function () {
        suppressGoogleBanner();

        var currentLang = getGoogleTransCookie();

        // Update button labels/flags if matching item found
        document.querySelectorAll('.dropdown-language').forEach(function (container) {
            var activeItem = container.querySelector('[data-lang-code="' + currentLang + '"]');
            var btn = container.querySelector('.dropdown-toggle');
            if (activeItem && btn) {
                var flag = activeItem.getAttribute('data-lang-flag') || '🌐';
                var label = activeItem.getAttribute('data-lang-label') || currentLang.toUpperCase();
                
                var flagEl = btn.querySelector('.lang-flag');
                var labelEl = btn.querySelector('.lang-label');
                if (flagEl) flagEl.textContent = flag;
                if (labelEl) labelEl.textContent = label;

                // Highlight active item in menu
                container.querySelectorAll('.dropdown-item').forEach(function(item) {
                    item.classList.remove('active', 'bg-primary', 'text-white');
                });
                activeItem.classList.add('active', 'bg-primary', 'text-white');
            }
        });
    });
})();
