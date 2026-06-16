# 🌙 E-Kad Raya — Digital Hari Raya Greeting Card Generator

A web application for creating and sharing personalized digital Hari Raya Aidilfitri greeting cards. Users can craft custom cards with personal messages, photos, and color themes, then share them via unique links or WhatsApp.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Railway](https://img.shields.io/badge/Railway-0B0D0E?style=for-the-badge&logo=railway&logoColor=white)

---

## ✨ Features

- 🎨 **3 Color Themes** — Emerald Green (Classic), Royal Gold (Luxurious), Soft Purple (Elegant)
- 📸 **Image Upload** — Supports JPG, PNG, GIF up to 5MB
- 🔗 **Unique Shareable Links** — Each card gets its own unique URL
- 📱 **WhatsApp Sharing** — One-tap share directly to WhatsApp
- 🎵 **Background Music** — Festive Raya music plays while viewing the card
- 📥 **Download as PNG** — Save the card as an image using html2canvas
- 📷 **QR Code** — Auto-generated QR code for every card
- ⭐ **Falling Stars Animation** — Animated star effects on the card display
- 📋 **Copy Link** — One-click URL copy to clipboard
- 🖼️ **Image Preview** — Preview uploaded images before submitting

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | PHP (Vanilla) |
| Database | MySQL (via MySQLi) |
| Frontend | HTML, JavaScript, Tailwind CSS (CDN) |
| Fonts | Playfair Display, Inter, Great Vibes (Google Fonts) |
| Animations | Animate.css |
| Screen Capture | html2canvas |
| QR Code | qrcodejs |
| Hosting | Railway |

---

## 📁 Project Structure

kad_raya/ ├── assets/ │ └── audio/ │ └── Jom_Raya.mp3 # Background music ├── uploads/ # User-uploaded images ├── index.php # Main form to create a card ├── process.php # Form processing & database insertion ├── view.php # Digital card display page ├── db_config.php # MySQL connection configuration ├── db_setup.php # Database table setup script ├── database.sql # SQL schema for the table ├── composer.json # PHP dependencies └── .gitignor

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+
- MySQL / MariaDB
- XAMPP, Laragon, or any PHP web server
- MySQLi extension enabled

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/zackzulfadhli23-creator/kad-raya-public.git
   cd kad-raya-public

      
