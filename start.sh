#!/bin/bash

# start.sh for Dashboard Monitoring
# Author: Antigravity

# Start PHP built-in server
echo "🚀 Starting Dashboard Monitoring local server..."
echo "📍 Access it at: http://localhost:8000"
echo "Press Ctrl+C to stop."

# Open browser (macOS)
open "http://localhost:8000"

php -S localhost:8000
