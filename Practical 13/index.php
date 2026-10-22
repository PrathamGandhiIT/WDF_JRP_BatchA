<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Event Management - Practical 13</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <header class="app-header">
            <h1>Campus Event Management</h1>
            <p class="subtitle">Practical 13: Asynchronous AJAX CRUD using Fetch API | By Raj & Manish</p>
        </header>

        <div id="message" role="status" class="hidden"></div>

        <div class="card form-card">
            <h2 id="formTitle">Add New Event</h2>
            <form id="eventForm">
                <input type="hidden" id="event_id">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="event_name">Event Name *</label>
                        <input type="text" id="event_name" maxlength="150" placeholder="e.g. Hackathon 2026" required>
                    </div>

                    <div class="form-group">
                        <label for="event_date">Event Date *</label>
                        <input type="date" id="event_date" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="venue">Venue *</label>
                        <input type="text" id="venue" maxlength="150" placeholder="e.g. IT Seminar Hall" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Event Description</label>
                        <textarea id="description" rows="3" placeholder="Enter key details or coordinator info..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" id="saveButton" class="btn primary">Add Event</button>
                    <button type="button" id="cancelButton" class="btn secondary" hidden>Cancel Edit</button>
                </div>
            </form>
        </div>

        <div class="card table-card">
            <div class="table-header">
                <h2>All Scheduled Events</h2>
                <button type="button" id="refreshButton" class="btn outline small">&#x21bb; Refresh</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Description</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="eventTable">
                        <tr><td colspan="6" style="text-align: center; padding: 20px;">Loading events...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
