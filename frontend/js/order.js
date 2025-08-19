document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("orderForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../backend/process_order.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Order Placed Successfully!");
                closeOrderModal();
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function openOrderModal(productId, productName, supplierId, price) {
    document.getElementById("product_id").value = productId;
    document.getElementById("supplier_id").value = supplierId;
    document.getElementById("product_name").value = productName;
    document.getElementById("price").value = price;
    document.getElementById("orderModal").style.display = "block";
}

function closeOrderModal() {
    document.getElementById("orderModal").style.display = "none";
}
