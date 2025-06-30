<!-- theme.php -->
<style>
    :root {
        --bg-light: #f8f9fa;
        --text-dark: #212529;
        --card-bg-light: white;
        --card-hover-light: #e9f1ff;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-dark);
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

    .dashboard-card {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
        background-color: var(--card-bg-light);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card:hover {
        background-color: var(--card-hover-light);
        transform: translateY(-5px);
    }

    .dashboard-card i {
        font-size: 40px;
        margin-bottom: 10px;
        color: #0d6efd;
    }

    .dashboard-card span {
        display: block;
        margin-top: 10px;
        font-weight: 600;
    }

    body.dark-mode {
        --bg-light: #121212;
        --text-dark: #f1f1f1;
        --card-bg-light: #1e1e1e;
        --card-hover-light: #2c2c2c;
    }

    .dark-mode .topbar {
        background-color: #1f1f1f;
        border-bottom: 1px solid #444;
    }

    .dark-mode .topbar a {
        color: #66b2ff;
    }

    .dark-mode .dashboard-card i {
        color: #66b2ff;
    }

    .dark-mode a.text-dark {
        color: #f1f1f1 !important;
    }

    .theme-toggle {
        cursor: pointer;
        font-size: 1.25rem;
    }
</style>

<script>
  function toggleTheme() {
    const body = document.body;
    body.classList.toggle("dark-mode");

    const icon = document.getElementById("themeIcon");
    if (body.classList.contains("dark-mode")) {
      icon.classList.remove("bi-moon");
      icon.classList.add("bi-sun");
      localStorage.setItem("theme", "dark");
    } else {
      icon.classList.remove("bi-sun");
      icon.classList.add("bi-moon");
      localStorage.setItem("theme", "light");
    }
  }

  window.onload = () => {
    const theme = localStorage.getItem("theme");
    const icon = document.getElementById("themeIcon");
    if (theme === "dark") {
      document.body.classList.add("dark-mode");
      icon?.classList.remove("bi-moon");
      icon?.classList.add("bi-sun");
    }
  };
</script>
