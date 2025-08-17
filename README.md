# Service Management Web App (RHEL / CentOS)

A PHP-based web interface to **manage Linux services** (Apache, Nginx, SSHD, FirewallD, MariaDB, etc.).

---

## 🚀 Setup Instructions

### 1. Install Dependencies
Install Apache, PHP, MariaDB, and Git:
```bash
sudo yum install -y httpd php mariadb-server git
sudo systemctl enable --now httpd
```

---

### 2. Deploy Project
Remove default Apache files:
```bash
sudo rm -rf /var/www/html/*
```

Clone the repository:
```bash
git clone https://github.com/yourusername/service-status-thansh.git
```

Move project into Apache web root:
```bash
sudo mv service-status-thansh/* /var/www/html/
```

---

### 3. Configure `sudo` Permissions
Allow Apache to run `systemctl` without password prompt.

Open sudoers:
```bash
sudo visudo
```

Add this line at the end:
```
apache ALL=(ALL) NOPASSWD: /bin/systemctl
```

*(Change `apache` to `www-data` on Debian/Ubuntu.)*

---

### 4. Restart Apache
```bash
sudo systemctl restart httpd
```

---

### 5. Access the Dashboard
Open your browser and visit:

```
http://<your-server-ip>/
```

---

## 📋 Features
- Start, stop, and restart services.
- Displays current service status.
- PHP-based with simple web UI.
- Secure backend integration with `systemctl`.

---

## ⚠️ Security Notes
- Intended for **internal/lab environments** only.
- Don’t expose it publicly without firewall/VPN restrictions.
- Anyone with access can control critical system services.
