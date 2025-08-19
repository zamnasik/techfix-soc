$(document).ready(function () {
    console.log("Inventory JS Loaded!");

    // Function to filter products
    window.filterProducts = function() {
        let searchText = $("#searchBox").val().toLowerCase();
        $(".inventory-card").each(function () {
            let productName = $(this).find("h3").text().toLowerCase();
            $(this).toggle(productName.includes(searchText));
        });
    };

    // Function to sort products
    window.sortProducts = function() {
        let sortBy = $("#sortOptions").val();
        let inventoryList = $(".inventory-card").get();

        inventoryList.sort(function (a, b) {
            let priceA = parseFloat($(a).find("p strong").text().replace('$', ''));
            let priceB = parseFloat($(b).find("p strong").text().replace('$', ''));

            if (sortBy === "price_low") return priceA - priceB;
            if (sortBy === "price_high") return priceB - priceA;
            return 0;
        });

        $(".inventory-grid").html(inventoryList);
    };

    // Function to request a quotation
    window.requestQuotation = function (productId) {
        $.ajax({
            type: "POST",
            url: "../backend/request_quotation.php",
            data: { product_id: productId },
            success: function (response) {
                alert(response);
            },
            error: function () {
                alert("Error submitting quotation request.");
            }
        });
    };
});
