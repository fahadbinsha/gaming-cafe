# 🎮 GameZone Elite – Gaming Café Management System

GameZone Elite is a modern **Gaming Café Management System** designed to manage customers, gaming stations, and bookings efficiently. It provides an intuitive dashboard with real-time statistics and a premium gaming-themed UI.

---

## 🚀 Features

* 📊 **Dashboard Overview**

  * Total Gamers
  * Gaming Stations
  * Available Stations
  * Total Bookings

* 👤 **Customer Management**

  * Add new customers
  * View customer list

* 🖥️ **Gaming Stations**

  * View all gaming stations
  * Check availability status

* 📅 **Booking System**

  * Book gaming stations
  * View all bookings

* 🎨 **Modern Gaming UI**

  * Neon-themed design
  * Responsive and clean layout

---

## 🛠️ Tech Stack

* **Frontend:**

  * HTML5
  * CSS3

* **Backend:**

  * PHP (Core PHP)

* **Database:**

  * MySQL

* **Server:**

  * XAMPP / Apache

---

## 📂 Project Structure

```
gaming_cafe/
│
├── bookings/
│   ├── add_booking.php
│   └── view_bookings.php
│
├── customers/
│   ├── add_customer.php
│   └── view_customers.php
│
├── stations/
│   └── view_stations.php
│
├── css/
│   └── style.css
│
├── includes/
│   └── db_connect.php
│
├── sql/
│   └── database.sql
│
├── index.php
└── README.md
```

---

## ⚙️ Installation & Setup

### 1️⃣ Clone the repository

```bash
git clone https://github.com/fahadbinsha/gaming-cafe.git
```

### 2️⃣ Move project to XAMPP

Place the folder inside:

```
C:\xampp\htdocs\
```

### 3️⃣ Import Database

1. Open **phpMyAdmin**
2. Create a database (e.g. `gaming_cafe`)
3. Import:

```
sql/database.sql
```

### 4️⃣ Configure Database Connection

Edit:

```
includes/db_connect.php
```

Set your database credentials:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "gaming_cafe";
```

### 5️⃣ Run the Project

Open browser and go to:

```
http://localhost/gaming_cafe/index.php
```


## 🔒 Future Enhancements

* 🔐 Admin authentication (login system)
* ⏱️ Time-based billing
* 📈 Analytics & reports
* 📱 Mobile responsiveness
* 💳 Payment integration

---

## 👨‍💻 Author

**Fahad Binsha**
GitHub: [https://github.com/fahadbinsha](https://github.com/fahadbinsha)

---

## 📜 License

This project is for **educational and personal use**.
You are free to modify and enhance it.

