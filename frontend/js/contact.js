document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");

    contactForm.addEventListener("submit", function (e) {
        let email = document.querySelector("input[name='email']").value;
        let message = document.querySelector("textarea[name='message']").value;

        if (email.trim() === "" || message.trim() === "") {
            e.preventDefault();
            alert("Please fill in all required fields!");
        }
    });
});
