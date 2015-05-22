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

function loadValues() {
    api("GetCount", function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#chartPinpoints').attr('data-percent', data.pinpoint);
        $('#chartPages').attr('data-percent', data.page);
        $('#chartQuestions').attr('data-percent', data.question);
        $('#chartLevels').attr('data-percent', data.level);

        animatePieCharts();
    }, function(data) {

    });
}