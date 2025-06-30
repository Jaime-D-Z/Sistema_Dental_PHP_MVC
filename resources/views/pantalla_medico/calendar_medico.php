<?php
require_once __DIR__ . '/../../../app/Http/Controllers/CalendarController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que el usuario sea médico
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: /resources/views/auth/login.php");
    exit;
}

$controller = new CalendarController();
$data = $controller->index();
$eventos = $data['eventos'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Calendario - Clínica Dental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .topbar {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }
    .topbar a {
      color: #0d6efd;
      font-weight: 500;
      margin-right: 20px;
      text-decoration: none;
    }
    .topbar a:hover {
      text-decoration: underline;
    }
    #calendar {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
    }
    .calendar-cell {
      border: 1px solid #ddd;
      padding: 5px;
      min-height: 100px;
      position: relative;
    }
    .calendar-cell .day-number {
      font-weight: bold;
    }
    .calendar-cell .event {
      background-color: #0d6efd;
      color: white;
      font-size: 0.8rem;
      margin-top: 5px;
      padding: 2px 4px;
      border-radius: 3px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .today {
      background-color: #cce5ff;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
    <h3 class="text-primary">🗓️ Calendario de Citas</h3>
    <a class="btn btn-outline-secondary" href="/resources/views/layouts/medico_index.php">← Volver al menú</a>
  </div>

  <h4 id="currentMonth" class="text-center mb-3 fw-semibold text-primary"></h4>

  <div class="d-flex justify-content-between mb-3">
    <div>
      <button class="btn btn-outline-primary btn-sm me-1" id="prevBtn">⬅️</button>
      <button class="btn btn-outline-primary btn-sm" id="nextBtn">➡️</button>
    </div>
    <div class="btn-group" role="group">
      <button class="btn btn-outline-secondary btn-sm" id="monthView">Mes</button>
      <button class="btn btn-outline-secondary btn-sm" id="weekView">Semana</button>
      <button class="btn btn-outline-secondary btn-sm" id="dayView">Día</button>
    </div>
  </div>

  <div id="calendar"></div>
</div>

<!-- JSON oculto con eventos -->
<div id="event-data" style="display:none;">
  <?= json_encode($eventos ?? []) ?>
</div>

<script>
  const events = JSON.parse(document.getElementById('event-data').textContent);
  let currentDate = new Date();
  let currentView = 'month';

  function formatDate(date) {
    return date.toISOString().split('T')[0];
  }

  function updateCurrentMonthTitle() {
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const texto = `${meses[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    document.getElementById('currentMonth').innerText = texto;
  }

  function renderCalendar() {
    updateCurrentMonthTitle();
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = '';

    if (currentView === 'month') renderMonthView(calendar);
    else if (currentView === 'week') renderWeekView(calendar);
    else renderDayView(calendar);
  }

  function renderMonthView(container) {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const grid = document.createElement('div');
    grid.className = 'calendar-grid';

    const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    dayNames.forEach(d => {
      const cell = document.createElement('div');
      cell.className = 'calendar-cell bg-light text-center fw-bold';
      cell.innerText = d;
      grid.appendChild(cell);
    });

    for (let i = 0; i < firstDay; i++) {
      const empty = document.createElement('div');
      empty.className = 'calendar-cell';
      grid.appendChild(empty);
    }

    for (let i = 1; i <= daysInMonth; i++) {
      const dayDate = new Date(year, month, i);
      const formatted = formatDate(dayDate);

      const cell = document.createElement('div');
      cell.className = 'calendar-cell';
      if (formatted === formatDate(new Date())) cell.classList.add('today');

      const dayNum = document.createElement('div');
      dayNum.className = 'day-number';
      dayNum.innerText = i;
      cell.appendChild(dayNum);

      const dailyEvents = events.filter(ev => ev.inicio === formatted);
      dailyEvents.forEach(ev => {
        const eventEl = document.createElement('div');
        eventEl.className = 'event';
        eventEl.innerText = `${ev.paciente} - ${ev.hora.slice(0, 5)}`;
        cell.appendChild(eventEl);
      });

      grid.appendChild(cell);
    }

    container.appendChild(grid);
  }

  function renderWeekView(container) {
    const day = currentDate.getDay();
    const startOfWeek = new Date(currentDate);
    startOfWeek.setDate(currentDate.getDate() - day);

    const grid = document.createElement('div');
    grid.className = 'calendar-grid';

    for (let i = 0; i < 7; i++) {
      const thisDay = new Date(startOfWeek);
      thisDay.setDate(startOfWeek.getDate() + i);
      const formatted = formatDate(thisDay);

      const cell = document.createElement('div');
      cell.className = 'calendar-cell';
      if (formatted === formatDate(new Date())) cell.classList.add('today');

      const dayNum = document.createElement('div');
      dayNum.className = 'day-number';
      dayNum.innerText = `${thisDay.getDate()} - ${['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'][thisDay.getDay()]}`;
      cell.appendChild(dayNum);

      const dailyEvents = events.filter(ev => ev.inicio === formatted);
      dailyEvents.forEach(ev => {
        const eventEl = document.createElement('div');
        eventEl.className = 'event';
        eventEl.innerText = `${ev.paciente} - ${ev.hora.slice(0, 5)}`;
        cell.appendChild(eventEl);
      });

      grid.appendChild(cell);
    }

    container.appendChild(grid);
  }

  function renderDayView(container) {
    const formatted = formatDate(currentDate);
    const list = document.createElement('div');
    list.className = 'calendar-day-view';

    const title = document.createElement('h5');
    title.innerText = `Citas para ${formatted}`;
    list.appendChild(title);

    const dailyEvents = events.filter(ev => ev.inicio === formatted);
    if (dailyEvents.length === 0) {
      list.innerHTML += '<p>No hay citas para hoy.</p>';
    } else {
      dailyEvents.forEach(ev => {
        const eventEl = document.createElement('div');
        eventEl.className = 'event';
        eventEl.innerText = `${ev.paciente} - ${ev.hora.slice(0, 5)}`;
        list.appendChild(eventEl);
      });
    }

    container.appendChild(list);
  }

  document.getElementById('monthView').addEventListener('click', () => {
    currentView = 'month';
    renderCalendar();
  });
  document.getElementById('weekView').addEventListener('click', () => {
    currentView = 'week';
    renderCalendar();
  });
  document.getElementById('dayView').addEventListener('click', () => {
    currentView = 'day';
    renderCalendar();
  });

  document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentView === 'month') currentDate.setMonth(currentDate.getMonth() - 1);
    else currentDate.setDate(currentDate.getDate() - 7);
    renderCalendar();
  });

  document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentView === 'month') currentDate.setMonth(currentDate.getMonth() + 1);
    else currentDate.setDate(currentDate.getDate() + 7);
    renderCalendar();
  });

  document.addEventListener('DOMContentLoaded', renderCalendar);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
