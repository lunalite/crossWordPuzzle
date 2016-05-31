
    // Declaration of variables
    var qnaPairs = [];
    var sortedAns = [];

$(function () {

    $.ajax({
        url: '../includes/qnOutput.php',
        dataType: 'json',
        success: function (data) {
            var count = 0;
            for (var x = 0; x < data.length; x++) {
                qnaPairs.push({ number: data[x][0], answer: data[x][2] });
                $('#output').append("<b>num: </b>" + qnaPairs[x].number +
                "<b> name: </b>" + qnaPairs[x].answer + '<br>'); //Set output element html
                count++;
            }
            runOnComplete();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }

    });
});

    function runOnComplete() {

        // Formulate a sorted list of answers 
        $.each(qnaPairs, function (index, value) {
            var res = value.answer.split(/\r?\n|\r/);
            sortedAns.push(res[0]);
            sortByLength(sortedAns, sortedAns.length);
            console.log(sortedAns);
        });

    }

    function sortByLength(TBSorted, last) {
        if (last <= 1) {
            return;
        }
        sortByLength(TBSorted, last - 1);
        while (last > 1) {
            if (sortedAns[last - 1] > sortedAns[last - 2]) {
                var temp = sortedAns[last - 2];
                sortedAns[last - 2] = sortedAns[last - 1];
                sortedAns[last - 1] = temp;
                last--;
            }
            else
                break;
        }
    }

