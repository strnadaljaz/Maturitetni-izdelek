document.querySelectorAll(".task-checkbox").forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
        const taskText = this.closest(".task-item").querySelector(".task-text");
        if (this.checked) {
            taskText.style.textDecoration = "line-through";
            taskText.style.color = "green";
        } else {
            taskText.style.textDecoration = "none";
            taskText.style.color = "initial";
        }
    });
});
