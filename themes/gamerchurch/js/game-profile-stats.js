// Ad-hoc calculation for displaying the total playtime and sessions of all games donated by the currently viewed user

document.addEventListener("DOMContentLoaded", () => {
    // Get the elements #donorGames and #donorStats
    const donorGames = document.querySelector("#donorGames");
    const donorStats = document.querySelector("#donorStats");

    if (donorGames && donorStats) {
        // Find all articles within .ptb_loops_wrapper inside #donorGames
        const articles = donorGames.querySelectorAll(".ptb_loops_wrapper article");
        
        // Initialize totals for sessions and duration
        let totalSessions = 0;
        let totalDuration = 0;

        // Loop through each article and sum the values
        articles.forEach(article => {
            const sessionsElement = article.querySelector(".donor-sessions");
            const durationElement = article.querySelector(".donor-duration");

            if (sessionsElement && durationElement) {
                totalSessions += parseInt(sessionsElement.innerHTML, 10) || 0;
                totalDuration += parseInt(durationElement.innerHTML, 10) || 0;
            }
        });

        // Create the <ul> structure
        const statsList = document.createElement("ul");
        statsList.innerHTML = `
            <li><i>Total Streams:</i> <b>${totalSessions} sessions</b></li>
            <li><i>Total Playtime:</i> <b>${totalDuration} minutes</b></li>
        `;

        // Append the <ul> to #donorStats
        donorStats.appendChild(statsList);
    }
});
