document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("quotationForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../backend/process_quotation.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Quotation Request Sent Successfully!");
                closeQuotationModal();
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function openQuotationModal(productId, productName, supplierId) {
    document.getElementById("product_id").value = productId;
    document.getElementById("supplier_id").value = supplierId;
    document.getElementById("product_name").value = productName;
    document.getElementById("quotationModal").style.display = "block";
}

function closeQuotationModal() {
    document.getElementById("quotationModal").style.display = "none";
}
