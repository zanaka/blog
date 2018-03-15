$("#modal-save").click(function () {
    var input1 = $("#exampleInput1").val();
    var input2 = $("#exampleInput2").val();

    if (!$.isNumeric(input1) || !$.isNumeric(input2)) {
        alert("数値を入力してください");
        return false;
    }

    var sum = parseInt(input1) + parseInt(input2);
    $("#modal-result").html("<p>足すと " + sum + "になります。</p>");
});