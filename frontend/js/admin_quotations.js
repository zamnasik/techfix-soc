function updateQuotationStatus(quotationId, newStatus) {
    $.ajax({
        type: "POST",
        url: "../backend/update_quotation_status.php",
        data: { id: quotationId, status: newStatus },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                alert(response.success);
                location.reload();
            } else {
                alert(response.error);
            }
        },
        error: function () {
            alert("Error processing request.");
        }
    });
}
