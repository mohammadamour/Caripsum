// Toggle description read more/less functionality
function toggleDescription() {
    const content = document.getElementById("descriptionContent");
    // const btn = document.getElementById("readMoreBtn");

    if (content.style.maxHeight === "190px" || content.style.maxHeight === "") {
        content.style.maxHeight = "none";
        btn.textContent = "Read less";
    } else {
        content.style.maxHeight = "190px";
        btn.textContent = "Read more";
    }
}

// Hide button if description is short
window.addEventListener("DOMContentLoaded", function () {
    const content = document.getElementById("descriptionContent");
    // const btn = document.getElementById("readMoreBtn");

    // if (content && btn && content.scrollHeight <= 200) {
    //     btn.style.display = "none";
    // }
});


