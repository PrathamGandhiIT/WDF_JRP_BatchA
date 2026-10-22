let allEvents = [];
let filteredEvents = [];
let currentPage = 1;
const recordsPerPage = 5;

const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortBy = document.getElementById("sortBy");
const eventsBody = document.getElementById("eventsBody");
const loadingEl = document.getElementById("loading");
const errorEl = document.getElementById("error");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const pageInfo = document.getElementById("pageInfo");

async function fetchEvents() {
  try {
    loadingEl.style.display = "block";
    errorEl.style.display = "none";
    
    // Check localStorage cache first
    const cachedData = localStorage.getItem("cached_events");
    if (cachedData) {
      allEvents = JSON.parse(cachedData);
      applyFilters();
    }

    const response = await fetch("events.json");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    allEvents = data;
    localStorage.setItem("cached_events", JSON.stringify(data));
    loadingEl.style.display = "none";
    applyFilters();
  } catch (err) {
    loadingEl.style.display = "none";
    if (allEvents.length === 0) {
      errorEl.textContent = "Failed to load events data: " + err.message;
      errorEl.style.display = "block";
    }
  }
}

function applyFilters() {
  const searchTerm = searchInput.value.trim().toLowerCase();
  const selectedCategory = categoryFilter.value;
  const sortOption = sortBy.value;

  filteredEvents = allEvents.filter(event => {
    const matchesSearch = event.title.toLowerCase().includes(searchTerm) ||
                          event.venue.toLowerCase().includes(searchTerm);
    const matchesCategory = selectedCategory === "All" || event.category === selectedCategory;
    return matchesSearch && matchesCategory;
  });

  filteredEvents.sort((a, b) => {
    if (sortOption === "date-asc") return new Date(a.date) - new Date(b.date);
    if (sortOption === "date-desc") return new Date(b.date) - new Date(a.date);
    if (sortOption === "title-asc") return a.title.localeCompare(b.title);
    if (sortOption === "seats-desc") return b.seats - a.seats;
    return 0;
  });

  currentPage = 1;
  renderTable();
}

function renderTable() {
  eventsBody.innerHTML = "";
  const totalPages = Math.ceil(filteredEvents.length / recordsPerPage) || 1;

  if (filteredEvents.length === 0) {
    eventsBody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 20px;">No events found matching the criteria.</td></tr>`;
    pageInfo.textContent = `Page 0 of 0`;
    prevBtn.disabled = true;
    nextBtn.disabled = true;
    return;
  }

  const startIndex = (currentPage - 1) * recordsPerPage;
  const pageRecords = filteredEvents.slice(startIndex, startIndex + recordsPerPage);

  pageRecords.forEach(event => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${event.id}</td>
      <td><strong>${event.title}</strong></td>
      <td><span class="badge badge-${event.category}">${event.category}</span></td>
      <td>${event.date}</td>
      <td>${event.venue}</td>
      <td>${event.seats}</td>
    `;
    eventsBody.appendChild(tr);
  });

  pageInfo.textContent = `Page ${currentPage} of ${totalPages} (${filteredEvents.length} events)`;
  prevBtn.disabled = currentPage <= 1;
  nextBtn.disabled = currentPage >= totalPages;
}

prevBtn.addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    renderTable();
  }
});

nextBtn.addEventListener("click", () => {
  const totalPages = Math.ceil(filteredEvents.length / recordsPerPage);
  if (currentPage < totalPages) {
    currentPage++;
    renderTable();
  }
});

searchInput.addEventListener("input", applyFilters);
categoryFilter.addEventListener("change", applyFilters);
sortBy.addEventListener("change", applyFilters);

document.addEventListener("DOMContentLoaded", fetchEvents);
