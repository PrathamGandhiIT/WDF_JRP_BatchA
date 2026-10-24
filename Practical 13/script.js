const apiUrl = "api.php";

const form = document.getElementById("eventForm");
const formTitle = document.getElementById("formTitle");
const eventTable = document.getElementById("eventTable");
const message = document.getElementById("message");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");
const refreshButton = document.getElementById("refreshButton");

async function requestApi(url, options = {}) {
    const response = await fetch(url, options);
    const result = await response.json();

    if (!response.ok || !result.success) {
        throw new Error(result.message || "An unexpected request error occurred.");
    }

    return result;
}

function showMessage(text, success = true) {
    message.textContent = text;
    message.className = success ? "alert success" : "alert error";
    message.classList.remove("hidden");

    setTimeout(() => {
        message.classList.add("hidden");
    }, 4500);
}

async function loadEvents() {
    try {
        eventTable.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 20px;">Loading events...</td></tr>`;
        const result = await requestApi(apiUrl);
        eventTable.innerHTML = "";

        if (!result.data || result.data.length === 0) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 6;
            cell.style.textAlign = "center";
            cell.style.padding = "25px";
            cell.style.color = "#718096";
            cell.textContent = "No scheduled events found. Add your first event above!";
            row.appendChild(cell);
            eventTable.appendChild(row);
            return;
        }

        result.data.forEach(event => {
            const row = document.createElement("tr");

            const idCell = document.createElement("td");
            idCell.textContent = event.event_id;
            row.appendChild(idCell);

            const nameCell = document.createElement("td");
            nameCell.innerHTML = `<strong>${escapeHtml(event.event_name)}</strong>`;
            row.appendChild(nameCell);

            const dateCell = document.createElement("td");
            dateCell.textContent = event.event_date;
            row.appendChild(dateCell);

            const venueCell = document.createElement("td");
            venueCell.textContent = event.venue;
            row.appendChild(venueCell);

            const descCell = document.createElement("td");
            descCell.textContent = event.description || "-";
            row.appendChild(descCell);

            const actionsCell = document.createElement("td");
            actionsCell.className = "table-actions";

            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.type = "button";
            editButton.className = "btn small edit-btn";
            editButton.addEventListener("click", () => editEvent(event));

            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.type = "button";
            deleteButton.className = "btn small danger-btn";
            deleteButton.addEventListener("click", () => deleteEvent(event.event_id));

            actionsCell.append(editButton, deleteButton);
            row.appendChild(actionsCell);

            eventTable.appendChild(row);
        });
    } catch (error) {
        showMessage(error.message, false);
        eventTable.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#c53030; padding:20px;">Failed to load events.</td></tr>`;
    }
}

form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const id = document.getElementById("event_id").value;

    const data = {
        action: id ? "update" : "add",
        event_id: id ? parseInt(id, 10) : undefined,
        event_name: document.getElementById("event_name").value.trim(),
        event_date: document.getElementById("event_date").value,
        venue: document.getElementById("venue").value.trim(),
        description: document.getElementById("description").value.trim()
    };

    try {
        saveButton.disabled = true;
        saveButton.textContent = id ? "Updating..." : "Saving...";

        const result = await requestApi(apiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        });

        showMessage(result.message, true);
        resetForm();
        await loadEvents();
    } catch (error) {
        showMessage(error.message, false);
    } finally {
        saveButton.disabled = false;
        if (!document.getElementById("event_id").value) {
            saveButton.textContent = "Add Event";
        } else {
            saveButton.textContent = "Update Event";
        }
    }
});

function editEvent(event) {
    document.getElementById("event_id").value = event.event_id;
    document.getElementById("event_name").value = event.event_name;
    document.getElementById("event_date").value = event.event_date;
    document.getElementById("venue").value = event.venue;
    document.getElementById("description").value = event.description || "";

    formTitle.textContent = `Edit Event #${event.event_id}`;
    saveButton.textContent = "Update Event";
    cancelButton.hidden = false;
    form.scrollIntoView({ behavior: "smooth" });
}

async function deleteEvent(id) {
    if (!confirm("Are you sure you want to delete this event? This action cannot be undone.")) {
        return;
    }

    try {
        const result = await requestApi(apiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                action: "delete",
                event_id: id
            })
        });

        showMessage(result.message, true);
        await loadEvents();
    } catch (error) {
        showMessage(error.message, false);
    }
}

function resetForm() {
    form.reset();
    document.getElementById("event_id").value = "";
    formTitle.textContent = "Add New Event";
    saveButton.textContent = "Add Event";
    cancelButton.hidden = true;
}

function escapeHtml(str) {
    if (!str) return "";
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

cancelButton.addEventListener("click", resetForm);

if (refreshButton) {
    refreshButton.addEventListener("click", () => {
        loadEvents();
        showMessage("Event list refreshed.", true);
    });
}

// Initial load
loadEvents();
