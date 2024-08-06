function formatTime(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var seconds = date.getSeconds();
  var ampm = hours >= 12 ? "PM" : "AM";

  hours = hours % 12;
  hours = hours ? hours : 12;
  hours = hours.toString().padStart(2, "0");
  minutes = minutes.toString().padStart(2, "0");
  seconds = seconds.toString().padStart(2, "0");

  return hours + ":" + minutes + ":" + seconds + " " + ampm;
}

function formatDate(date) {
  const options = { month: "long", day: "numeric", year: "numeric" };
  return date.toLocaleDateString(undefined, options);
}

function formatDay(date) {
  const options = { weekday: "long" };
  return date.toLocaleDateString(undefined, options);
}

function updateDateTime() {
  var date = new Date();
  var currentTime = formatTime(date);
  var currentDate = formatDate(date);
  var currentDay = formatDay(date);
  document.getElementById("live-time").textContent =
    currentTime + " | " + currentDay + ", " + currentDate;
}

setInterval(updateDateTime, 1000);

// Time In and Out script

function formatTableDate(date) {
  const options = { month: "long", day: "numeric", year: "numeric" };
  return date.toLocaleDateString(undefined, options);
}

function updateTableDate() {
  var date = new Date();
  var currentDate = formatTableDate(date);
  var dateElements = document.getElementsByClassName("date");
  for (var i = 0; i < dateElements.length; i++) {
    dateElements[i].textContent = currentDate;
  }
}

setInterval(updateTableDate, 1000);

//Manual Time in and out scripts
function saveTime(name, filterDate) {
  var timeIn = document.getElementById("time_in_" + name).value;
  var timeOut = document.getElementById("time_out_" + name).value;

  // Convert filterDate to a format accepted by the database (e.g., Y-m-d)
  var formattedDate = new Date(filterDate);
  var formattedDateString = formattedDate.toISOString().slice(0, 10);

  // Create a new form element
  var form = document.createElement("form");
  form.method = "post";
  form.action = "update_inout.php";

  // Create hidden input fields for the data
  var nameInput = document.createElement("input");
  nameInput.type = "hidden";
  nameInput.name = "name";
  nameInput.value = name;

  var timeInInput = document.createElement("input");
  timeInInput.type = "hidden";
  timeInInput.name = "time_in";
  timeInInput.value = timeIn;

  var timeOutInput = document.createElement("input");
  timeOutInput.type = "hidden";
  timeOutInput.name = "time_out";
  timeOutInput.value = timeOut;

  var filterDateInput = document.createElement("input");
  filterDateInput.type = "hidden";
  filterDateInput.name = "filter_date";
  filterDateInput.value = formattedDateString; // Use the formatted date here

  // Append the inputs to the form
  form.appendChild(nameInput);
  form.appendChild(timeInInput);
  form.appendChild(timeOutInput);
  form.appendChild(filterDateInput);

  // Append the form to the document and submit it
  document.body.appendChild(form);
  form.submit();
}

const departmentSelect = document.getElementById('filter-department');

// ...

weekSelect.addEventListener('change', function() {
    // ...

    // Update the form action URL with the selected filters before submitting the form
    const form = document.getElementById('filter-form');
    form.action = `summary_view.php?filter_month=${weekDates[0].slice(0, 7)}&filter_week=${selectedWeek}&filter_department=${departmentSelect.value}`;

    // Submit the form
    form.submit();
});

// After form submission, set the selected option in the department dropdown based on the query parameter
window.addEventListener('DOMContentLoaded', function() {
    // ...

    const filterDepartment = urlParams.get('filter_department');
    if (filterDepartment !== null) {
        departmentSelect.value = filterDepartment;
    }
});

const nameSearchInput = document.getElementById('name-search1');

nameSearchInput.addEventListener('input', function() {
    const filterValue = nameSearchInput.value.toLowerCase();
    const nameCells = document.querySelectorAll('.tg-name');
    
    nameCells.forEach(function(cell) {
        const name = cell.textContent.toLowerCase();
        cell.closest('tr').style.display = name.includes(filterValue) ? '' : 'none';
    });
});