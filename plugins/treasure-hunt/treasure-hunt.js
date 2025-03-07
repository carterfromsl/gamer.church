jQuery(document).ready(function($) {
    // Function to get the value of a cookie
    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
        return null;
    }

    // Function to set a cookie
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // Check for the clue cookie
    var clueCookie = getCookie('treasureClue');
    if (!clueCookie) {
        // No cookie found, display the intro clue and set the progress to 1
        $('#introClue').show();
        setCookie('treasureClue', '1', 7); // Set the clue progress to 1
    } else {
        // Cookie found, display the corresponding clue
        var clueId = 'clue' + clueCookie;
        $('#' + clueId).show();
    }

    // Event handler for finding a clue
    $('.treasure-clue').on('click', function() {
        var nextClueNumber = parseInt($(this).data('clue-number')) + 1;
        setCookie('treasureClue', nextClueNumber, 7); // Update cookie for the next clue

        // Hide current clue and show the next one
        $(this).hide();
        $('#clue' + nextClueNumber).show();
    });

    // Event delegation for the close button
    $(document).on('click', '.clue-close-btn', function() {
        $(this).closest('.clue-wrapper').remove();
    });
});
