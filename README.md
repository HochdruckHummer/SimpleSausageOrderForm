# SimpleSausageOrderForm
This is a lightweight order form for small groups to make ordering sausages for club members more easy. Security is completely neglected. Should not be openly published. Saves orders in a JSON file. Can be reset via secret link.

<img width="642" height="621" alt="Bildschirmfoto 2026-02-25 um 20 14 24" src="https://github.com/user-attachments/assets/6a3e2c8e-4287-40d9-a58c-7827f9349bf0" />

## Designed for simplicity:
No database required
No authentication system
No external dependencies
Works on any standard PHP web hosting
Perfect for small clubs, amateur radio groups, sport clubs or community events.

## Features
Participants enter:
* Name (required)
* Callsign (optional) - can also be repurposed for some other identification like player number, member number and so on.
* Number of bratwursts (sausages) to be ordered

* Orders are stored in a local JSON file
* Automatic total calculation
* Mobile-friendly layout
* Admin reset functionality (via secret link)
* Search engines are prevented from indexing the page

## Requirements
PHP 7.0 or higher
Write permissions in the project directory
No database required.

## Installation
Upload the PHP file (e.g. index.php) to your web server.
Make sure the directory is writable by PHP.
Open the page in your browser:
https://your-server.com/bratwurst.php
The file bestellungen.json will be created automatically.

## Admin Reset
The admin panel is hidden by default.
To access it, append the secret key to the URL:
https://your-server.com/index.php?admin=YOUR_SECRET_KEY
Inside the code, you can configure:
$adminPassword = 'YOUR_ADMIN_PASSWORD';
$adminSecretKey = 'YOUR_SECRET_KEY';
To reset all orders:
Open the admin URL
Enter the admin password
Click "Reset orders"

## Preventing Search Engine Indexing
The page includes:
<meta name="robots" content="noindex, nofollow">
This prevents search engines from indexing the page.
For additional protection, you may add a robots.txt file:
User-agent: *
Disallow: /
📁 Project Structure
/project-folder
│
├── bratwurst.php
└── bestellungen.json (auto-created)

## Security Notes
This project is intentionally simple.
There is no authentication for order submission.
Anyone with the link can submit orders.
The admin URL should not be shared publicly.
For production use on a public server, consider:
Using HTTPS
Using reverse proxy
Moving credentials outside the web root
Adding basic authentication
