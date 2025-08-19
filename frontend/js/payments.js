document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("paymentForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../backend/process_payment.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Payment Successful!");
                closePaymentModal();
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function openPaymentModal(paymentId) {
    document.getElementById("payment_id").value = paymentId;
    document.getElementById("paymentModal").style.display = "block";
}

function closePaymentModal() {
    document.getElementById("paymentModal").style.display = "none";
}
