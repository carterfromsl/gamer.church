(function($) {
    'use strict';
    
    function updateCountdown() {
        const now = new Date().getTime();
        const target = countdownVars.targetTime; // From wp_localize_script
        
        let days, hours, minutes, seconds;
        
        if (now < target) {
            // Normal countdown when the target time is in the future
            const timeLeft = target - now;
            days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        } else {
            // When target time is past, show 99 for days/hours/minutes...
            days = 99;
            hours = 99;
            minutes = 99;
            // ...and for seconds, count down repeatedly from 99.
            // This uses the total seconds elapsed (from the epoch) modulo 100, so it resets every 100 seconds.
            seconds = 99 - (Math.floor(now / 1000) % 100);
        }
        
        // Update digits
        updateDigits('.count-days', days);
        updateDigits('.count-hours', hours);
        updateDigits('.count-minutes', minutes);
        updateDigits('.count-seconds', seconds);
    }
    
    function updateDigits(selector, value) {
        const digits = $(selector).find('.digit');
        const paddedValue = String(value).padStart(2, '0');
        
        digits.each(function(index) {
            $(this).text(paddedValue[index]);
        });
    }
    
    // Update every second
    $(document).ready(function() {
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
})(jQuery);
