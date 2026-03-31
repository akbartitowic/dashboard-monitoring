// sw.js - Service Worker for Homelab Agent
const CACHE_NAME = 'homelab-v1';
const ASSETS = [
  '/',
  '/index.php',
  '/assets/css/style.css',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS))
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
