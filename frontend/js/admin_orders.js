function filterOrders() {
    let statusFilter = $("#statusFilter").val();

    $("#ordersBody tr").each(function () {
        let status = $(this).find(".status").text().toLowerCase();
        let matchesStatus = (statusFilter === "" || status === statusFilter.toLowerCase());

        if (matchesStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function updateOrderStatus(orderId, newStatus) {
    $.ajax({
        type: "POST",
        url: "../backend/update_order_status.php",
        data: { id: orderId, status: newStatus },
        success: function () {
            location.reload();
        }
    });
}

function viewOrderDetails(orderId) {
    window.location.href = "order_details.php?id=" + orderId;
}
