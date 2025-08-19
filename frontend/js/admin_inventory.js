function updateStockStatus(stockId, newStatus) {
    $.ajax({
        type: "POST",
        url: "../backend/update_stock_status.php",
        data: { id: stockId, status: newStatus },
        success: function () {
            location.reload();
        }
    });
}

function deleteStock(stockId) {
    if (confirm("Are you sure you want to delete this stock?")) {
        $.ajax({
            type: "POST",
            url: "../backend/delete_stock.php",
            data: { id: stockId },
            success: function () {
                location.reload();
            }
        });
    }
}


function filterInventory() {
    let searchQuery = $("#searchBox").val().toLowerCase();
    let statusFilter = $("#statusFilter").val();

    $("#inventoryBody tr").each(function () {
        let productName = $(this).find("td:nth-child(3)").text().toLowerCase();
        let status = $(this).find(".status").text().toLowerCase();
        
        let matchesSearch = productName.includes(searchQuery);
        let matchesStatus = (statusFilter === "" || status === statusFilter.toLowerCase());

        if (matchesSearch && matchesStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function showLowStock() {
    $("#inventoryBody tr").each(function () {
        let stock = parseInt($(this).find(".stock").text());
        if (stock <= 5) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function sortInventory() {
    let sortBy = $("#sortBy").val();
    let rows = $("#inventoryBody tr").get();

    rows.sort(function (a, b) {
        let valA, valB;
        if (sortBy === "stock") {
            valA = parseInt($(a).find(".stock").text());
            valB = parseInt($(b).find(".stock").text());
        } else if (sortBy === "price") {
            valA = parseFloat($(a).find(".price").text().replace("$", ""));
            valB = parseFloat($(b).find(".price").text().replace("$", ""));
        }

        return valA - valB;
    });

    $.each(rows, function (index, row) {
        $("#inventoryBody").append(row);
    });
}
