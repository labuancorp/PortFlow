const CACHE_NAME = 'portflow-v1';
const ASSETS_TO_CACHE = [
    '/portal',
    '/manifest.json',
    '/images/icon.png' // Placeholder
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

// Activate Event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            }));
        })
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    // For API calls, straight to network
    if (event.request.url.includes('/api/') || event.request.url.includes('/livewire/')) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).catch(() => {
                // If offline and request is for navigation, show offline page (if we had one)
                if (event.request.mode === 'navigate') {
                    // return caches.match('/offline.html');
                }
            });
        })
    );
});
