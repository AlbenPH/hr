$(document).ready(function () {
    // Staff Attendance Report
    $("#attendances-filter").click(function () {
        var table = $("#lateness-closing-attendance-table");
        table.on("preXhr.dt", function (e, settings, data) {
            data.employee_id = $("#employee_id").val();
            data.year = $("#year").val();
            data.month = $("#month").val();
        });
        table.DataTable().ajax.reload();
    });

    $("#attendances-search-reset").click(function () {
        $("#employee_id").val("").trigger("change");
        $("#year").val("").trigger("change");
        $("#month").val("").trigger("change");
        var table = $("#lateness-closing-attendance-table");
        table.on("preXhr.dt", function (e, settings, data) {
            data.employee_id = "";
            data.year = "";
            data.month = "";

            $("#employee_id").select2({
                placeholder: "All Employees",
            });
            $("#year").select2({
                placeholder: "Select Year",
            });
            $("#month").select2({
                placeholder: "Select Month",
            });
        });
        table.DataTable().ajax.reload();
    });
});
