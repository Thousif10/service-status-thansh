-- Create DB & table
CREATE DATABASE IF NOT EXISTS service_manager
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE service_manager;

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service_name  VARCHAR(100) NOT NULL,   -- short name for UI
  display_name  VARCHAR(150) NOT NULL,   -- pretty label
  description   TEXT,
  service       VARCHAR(100) NOT NULL,   -- exact systemd unit name
  UNIQUE KEY uniq_service (service)
);

-- Seed examples (adjust to your host)
INSERT INTO services (service_name, display_name, description, service) VALUES
('httpd',    'Apache Web Server', 'Handles web requests',           'httpd'),
('nginx',    'Nginx Web Server',  'Handles web requests via Nginx', 'nginx'),
('sshd',     'SSH Server',         'Secure shell access',            'sshd'),
('firewalld','FirewallD',          'Manages firewall rules',         'firewalld'),
('mariadb',  'MariaDB Database',   'Relational database',            'mariadb')
ON DUPLICATE KEY UPDATE service_name=VALUES(service_name),
                        display_name=VALUES(display_name),
                        description=VALUES(description);
