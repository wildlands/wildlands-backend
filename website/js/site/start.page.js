// Animate the pieCharts using jQuery.EasyPieChart.js
function animatePieCharts() {
    $('.chart').easyPieChart({
        animate: 2000,
        scaleColor: false,
        barColor: '#007e2b',
        onStep: function(from, to, percent) {
            $(this.el).find('.percentage').text(Math.round(percent));
        }
    });
}

// Load the values for pinpoints, pages, questions and levels
function loadValues() {
    // Send a request to the api using the 'GetCount' command
    api("GetCount", function(data) {
        // If there was an error, show it and abort
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        // Set the received data
        $('#chartPinpoints').attr('data-percent', data.pinpoint);
        $('#chartPages').attr('data-percent', data.page);
        $('#chartQuestions').attr('data-percent', data.question);
        $('#chartLevels').attr('data-percent', data.level);

        // Start the pie chart animation
        animatePieCharts();
    });
}