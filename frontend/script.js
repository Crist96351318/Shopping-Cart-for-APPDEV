// Back to Top Button Logic
document.addEventListener("DOMContentLoaded", () => {
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");

    if (scrollToTopBtn) {
        // Show or hide the button based on scroll position
        window.addEventListener("scroll", () => {
            if (window.scrollY > 400) {
                scrollToTopBtn.classList.add("show");
            } else {
                scrollToTopBtn.classList.remove("show");
            }
        });

        // Smooth scroll to top when clicked
        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
});

// Account Dropdown Logic
document.addEventListener("DOMContentLoaded", () => {
    const accountBtn = document.getElementById("accountBtn");
    const accountDropdown = document.getElementById("accountDropdown");

    if (accountBtn && accountDropdown) {
        // Toggle dropdown when the Account button is clicked
        accountBtn.addEventListener("click", (e) => {
            e.stopPropagation(); // Stops the click from immediately closing the menu
            accountDropdown.classList.toggle("show");
        });

        // Close dropdown if user clicks anywhere else on the page
        document.addEventListener("click", (e) => {
            if (!accountDropdown.contains(e.target) && e.target !== accountBtn) {
                accountDropdown.classList.remove("show");
            }
        });
    }
});