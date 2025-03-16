document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("user-tag-search");
    const tagList = document.getElementById("user-tag-list");
    const tags = tagList.getElementsByTagName("li");

    searchInput.addEventListener("keyup", function() {
        let filter = searchInput.value.toLowerCase();
        for (let i = 0; i < tags.length; i++) {
            let label = tags[i].innerText.toLowerCase();
            if (label.includes(filter)) {
                tags[i].style.display = "";
            } else {
                tags[i].style.display = "none";
            }
        }
    });
});