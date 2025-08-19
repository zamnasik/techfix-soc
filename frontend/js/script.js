document.addEventListener("DOMContentLoaded", function () {
    // Ensure text fades in properly and stays
    gsap.from("#headline", { opacity: 0, y: -50, duration: 1 });

    // Color-changing effect (Infinite loop)
    gsap.to("#headline", {
        duration: 2,
        color: "orange",
        repeat: -1,  // Infinite loop
        yoyo: true,  // Reverse animation
        ease: "power1.inOut"
    });

    gsap.from("#subtext", { opacity: 0, y: -30, duration: 1, delay: 0.5 });

    gsap.from(".cta-button", { opacity: 0, scale: 0.8, duration: 1, delay: 1 });

    // Add glowing effect for the button
    gsap.to(".cta-button", {
        duration: 1,
        boxShadow: "0px 0px 15px #f39c12",
        repeat: -1,
        yoyo: true
    });
});
