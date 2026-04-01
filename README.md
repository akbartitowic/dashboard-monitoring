# Dashboard Monitoring

A centralized dashboard for managing homelab environments, monitoring system health, and managing projects.

## Features
- **Project Tracking**: Manage local and GitHub-hosted projects.
- **AI Agent Chat**: Integrated chat interface (e.g., Gemini Pro).
- **Vibe Coding**: Rapidly prototype within the dashboard.
- **System Monitoring**: Track RAM, CPU usage, and disk space.

---

## 🚀 Quick Install (Terminal)

To install this dashboard on your Mac, simply run this one-liner in your terminal:

```bash
git clone https://github.com/akbartitowic/dashboard-monitoring.git dashboard && cd dashboard && chmod +x install.sh && ./install.sh
```

### Manual Installation
If you prefer manual steps:
1.  **Clone the repository**:
    ```bash
    git clone https://github.com/akbartitowic/dashboard-monitoring.git
    cd dashboard-monitoring
    ```
2.  **Make installer executable**:
    ```bash
    chmod +x install.sh
    chmod +x start.sh
    ```
3.  **Run the installer**:
    ```bash
    ./install.sh
    ```
4.  **Start the server**:
    ```bash
    ./start.sh
    ```
    Visit `http://localhost:8000` to access the dashboard.

---

## Requirements
- **PHP 7.4+** (Recommended: 8.x)
- **MySQL** (For database storage)
- **Web Browser** (Chrome/Safari/Firefox)

## License
MIT License. Created by [akbartitowic](https://github.com/akbartitowic).
