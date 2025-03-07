document.addEventListener("DOMContentLoaded", function() {

    // Mapping for letters (A-Z) to primary terms.
    var primaryTerms = {
	  'A': "nalyzing",
	  'B': "ooting",
	  'C': "alculating",
	  'D': "ownloading",
	  'E': "ncrypting",
	  'F': "ormatting",
	  'G': "enerating",
	  'H': "acking",
	  'I': "nitializing",
	  'J': "oining",
	  'K': "icking",
	  'L': "oading",
	  'M': "apping",
	  'N': "ullifying",
	  'O': "ptimizing",
	  'P': "rocessing",
	  'Q': "uantifying",
	  'R': "eticulating",
	  'S': "canning",
	  'T': "ransmitting",
	  'U': "pdating",
	  'V': "erifying",
	  'W': "iping",
	  'X': "-raying",
	  'Y': "ielding",
	  'Z': "ipping"
	};

    // Pool of terms to randomly choose from for digits.
    var numberTerms = [
        " Flagged Malware",
        " Active Thread",
        " Damaged Core",
        "x Redundancy Check",
        " Active Process",
        " Node Cluster",
        " Untitled File",
        " Subroutine",
        "-Bit Encryption",
        " Digit Password"
    ];

    // Pool of secondary terms to append.
    var secondaryTerms = [
        "Assets", "Splines", "Resources", "Meshes",
        "Cores", "Firmware", "Drivers", "Modules"
    ];

    // Function to generate a random 4-digit number as a string.
    function getRandomFourDigit() {
        return Math.floor(Math.random() * 9000) + 1000;
    }

    // Main function to generate the console output.
    function generateConsole() {
        var container = document.getElementById("consoleOverflow");
        if (!container) return;
        
        // Retrieve and decode the Base64 encoded codes.
        var encodedCodes = container.getAttribute("data-codes");
        if (!encodedCodes) return;
        var decodedCodes = atob(encodedCodes);
        var codesArray = decodedCodes.split(",");
        
        // Pick a random code.
        var randomCode = codesArray[Math.floor(Math.random() * codesArray.length)];

        // Clear the container.
        container.innerHTML = "";

        // Create a new unordered list.
        var ul = document.createElement("ul");

        // Loop through each character in the chosen code.
        for (var i = 0; i < randomCode.length; i++) {
            var char = randomCode.charAt(i);
            var li = document.createElement("li");
            var mappedText = "";
            
            // If the character is a letter, use the primary term.
            if (char.match(/[A-Z]/i)) {
                var upperChar = char.toUpperCase();
                mappedText = primaryTerms[upperChar] || "Processing";
            } else if (char.match(/[0-9]/)) { // If it's a digit, pick a random term from the pool.
                mappedText = numberTerms[Math.floor(Math.random() * numberTerms.length)];
            } else {
                mappedText = "Processing";
            }
            
            // Pick a random secondary term.
            var secondary = secondaryTerms[Math.floor(Math.random() * secondaryTerms.length)];
            
            // Build the list item with the first character in bold,
            // the mapped text, a secondary term, and a random 4-digit number.
            li.innerHTML = "<b>" + char + "</b>" + mappedText + " " + secondary + " [" + getRandomFourDigit() + "]";
            ul.appendChild(li);
        }
        container.appendChild(ul);
    }

    // Generate console output immediately.
    generateConsole();
});
