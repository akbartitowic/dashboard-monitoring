-- Database: homelab_dashboard
CREATE DATABASE IF NOT EXISTS homelab_dashboard;
USE homelab_dashboard;

-- Projects Table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    local_path VARCHAR(255) NOT NULL,
    subdomain VARCHAR(255),
    github_repo VARCHAR(255),
    status ENUM('active', 'inactive', 'deploying', 'error') DEFAULT 'active',
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- AI Agents Table
CREATE TABLE IF NOT EXISTS agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    model_name VARCHAR(100) NOT NULL,
    api_key_ref VARCHAR(255), -- Reference to an env variable or partial key
    system_prompt TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Monitoring Logs (Optional for charts)
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_usage DECIMAL(5,2),
    ram_usage DECIMAL(5,2),
    cpu_temp DECIMAL(5,2),
    disk_usage DECIMAL(5,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data
INSERT INTO projects (name, description, local_path, subdomain, github_repo) 
VALUES ('Dashboard Project', 'The main server management system', '/Users/tito/dashboard', 'dashboard.local', 'github.com/tito/dashboard');

INSERT INTO agents (name, model_name, system_prompt) 
VALUES ('Gemini Pro', 'gemini-1.5-pro', 'You are a helpful coding assistant for the Homelab server.');
