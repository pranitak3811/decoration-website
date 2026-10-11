# ✦ Aura Décor — Decoration Website

A complete, production-ready decoration business website with PHP backend, MySQL database, photo gallery, booking system, and admin panel.

---

## 📁 Project Structure

```
decoration-website/
├── index.html          ← Main website (homepage)
├── admin.html          ← Admin panel (manage bookings)
├── database.sql        ← Database setup file
├── css/
│   └── style.css       ← All styles
├── js/
│   └── main.js         ← Frontend JavaScript
└── php/
    ├── config.php      ← DB credentials
    ├── api.php         ← Public API (booking, gallery, contact)
    └── admin_api.php   ← Admin API (manage bookings)
```

---

## 🚀 HOW TO SETUP LOCALLY

### Step 1 — Install XAMPP (Local PHP Server)
1. Download from: https://www.apachefriends.org/
2. Install and open **XAMPP Control Panel**
3. Start **Apache** and **MySQL**

### Step 2 — Place Project Files
1. Copy your project folder to: `C:\xampp\htdocs\decoration-website\`
2. Rename the folder to `decoration-website`

### Step 3 — Setup Database
1. Open browser → go to: `http://localhost/phpmyadmin`
2. Click **"New"** → Create database named: `decoration_db`
3. Click **"Import"** tab → Choose `database.sql` file → Click **Go**
4. Your tables and sample data are now created ✅

### Step 4 — Configure Database
Open `php/config.php` and edit:
```php
define('DB_USER', 'root');   // Usually 'root' for XAMPP
define('DB_PASS', '');       // Usually empty for XAMPP
```

### Step 5 — Open Website
- **Website:** `http://localhost/decoration-website/`
- **Admin Panel:** `http://localhost/decoration-website/admin.html`

---

## 🌐 HOW TO DEPLOY ON GITHUB + LIVE HOSTING

### PART A — Upload to GitHub

#### Step 1 — Install Git
Download from: https://git-scm.com/downloads

#### Step 2 — Create GitHub Account
Go to: https://github.com → Sign Up (free)

#### Step 3 — Create New Repository
1. Click the **"+"** icon → **New repository**
2. Repository name: `aura-decor-website`
3. Set to **Public** → Click **Create repository**

#### Step 4 — Initialize & Push Code
Open terminal/command prompt inside your project folder:

```bash
# Initialize git
git init

# Add all files
git add .

# First commit
git commit -m "Initial commit - Aura Decor Website"

# Connect to your GitHub repo (replace YOUR_USERNAME)
git remote add origin https://github.com/YOUR_USERNAME/aura-decor-website.git

# Push to GitHub
git branch -M main
git push -u origin main
```

✅ Your code is now on GitHub!

---

### PART B — Deploy on Live Hosting (InfinityFree / Hostinger)

> Note: For PHP + MySQL websites, you need real web hosting (not GitHub Pages)

#### Option 1 — InfinityFree (FREE Hosting)
1. Go to: https://infinityfree.net → Create free account
2. Create a new hosting account
3. Go to **Control Panel → MySQL Databases**
4. Create a new database (note the DB name, username, password, host)
5. Open `phpMyAdmin` → Import your `database.sql` file
6. Edit `php/config.php` with your new database details
7. Upload all files via **File Manager** or FTP (FileZilla)

#### Option 2 — Hostinger (Paid, Recommended)
1. Go to: https://www.hostinger.in → Buy hosting (₹69/month)
2. Use **hPanel** → File Manager → Upload files to `public_html/`
3. Create MySQL database in hPanel → Import `database.sql`
4. Update `php/config.php` with credentials

#### FTP Upload (FileZilla)
1. Download FileZilla: https://filezilla-project.org/
2. Connect using your hosting FTP credentials
3. Upload all project files to `public_html/` folder

---

## ⚙️ FEATURES

| Feature | Details |
|---------|---------|
| 🏠 Homepage | Hero, Stats, Services, Gallery, About, Testimonials |
| 🖼️ Photo Gallery | Filter by category, Lightbox viewer |
| 📅 Booking System | Full form with DB storage |
| 📞 Contact Form | Messages saved to database |
| 🔑 Admin Panel | View/confirm/cancel bookings |
| 📱 Responsive | Works on mobile, tablet, desktop |
| 🎨 Animations | Scroll reveal, hover effects, marquee |

---

## 🛠️ CUSTOMIZATION

### Change Business Name
Search & replace `Aura Décor` in all files with your business name.

### Change Contact Info
Edit in `index.html` — look for the **Contact Section**.

### Change Colors
Edit CSS variables in `css/style.css`:
```css
:root {
    --gold: #C9A84C;     ← Primary accent color
    --dark: #0D0B08;     ← Dark background
    --cream: #FAF7F0;    ← Light background
}
```

### Add Real Photos
Replace Unsplash URLs in `index.html` gallery section with your own images, or upload photos to the `images/` folder and reference them locally.

---

## 📞 SUPPORT

If you face any issues:
- Check browser console (F12) for JavaScript errors
- Check `php/config.php` database credentials
- Ensure Apache + MySQL are running in XAMPP
