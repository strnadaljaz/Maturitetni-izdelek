document.querySelectorAll(".task-checkbox").forEach(function(checkbox) {
    checkbox.addEventListener("change", function() {
        const taskItem = this.closest(".task-item");
        const taskText = taskItem.querySelector(".task-text");
        const task = taskText.textContent.trim();
        const taskDone = this.checked ? 1 : 0;

        // Posodobi slog naloge
        if (this.checked) {
            taskText.style.textDecoration = "line-through";
            taskText.style.color = "#1e1c50";
        } else {
            taskText.style.textDecoration = "none";
            taskText.style.color = "initial";
        }

        // Pošlji zahtevek na strežnik
        fetch('update_task_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ task: task, task_done: taskDone })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  console.log('Stanje naloge posodobljeno.');
              } else {
                  console.error('Napaka pri posodobitvi naloge:', data.error);
              }
          }).catch(error => {
              console.error('Zahteva ni uspela:', error);
          });
    });
});
