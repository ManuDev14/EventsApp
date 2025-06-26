# 🏢 Event Reservation System

This project is a room reservation system for managing events in a convention center. It ensures that no two events overlap in the same room, while allowing multiple events at the same time in different rooms.

---

## ✅ Problem Overview

> Each room can host only one event at a time, but events may overlap in different rooms.  
> The system must support:

- Registering new events (without overlaps in the same room).
- Querying events active in a given time range.
- Canceling events by name.

---

## ✅ Extra Feature

> 🎁 In addition to the original requirements, we added **event dates**, allowing events with the same time ranges to exist on different days.

---

## 🧠 Data Modeling

To ensure data normalization, scalability and clear responsibility separation, we used the following structure:

- `events` – Contains unique event definitions (name, etc.)
- `rooms` – Contains room names.
- `reservations` – Links an event to a room, including the **event date**, **start time**, and **end time**.

This structure allows us to support:

- Multiple rooms and events.
- Repeated events across different dates.
- Clean filtering and aggregation.

---

## 🛠️ Technologies Used

- **Laravel 12** with:
    - Form Requests (for validation)
    - Actions (encapsulated business logic)
    - Enum for status control (`active`, `cancelled`)
    - Exception handling centralized via `bootstrap/app.php`
- **Inertia.js + Vue 3 + TypeScript**
    - UI components built with **ShadCN + Tailwind**
    - Toasts for validation errors (via `sonner`)
    - Pagination and form modals

---

## 🧱 Input/Output Format

We chose to expose a **RESTful API** (in JSON) with a SPA frontend using Inertia.

- Input: JSON payloads sent via POST requests from Vue forms.
- Output: Paginated JSON data rendered via Vue components.
- Example endpoint:
    - `POST /reservations` – Creates a new reservation
    - `GET /reservations?date=2025-06-25` – Lists active reservations for a specific day

---

## 🔐 Conflict Detection Logic

In the backend, we detect overlaps with SQL logic that checks:

- `start_time` overlaps existing `start_time` to `end_time`
- `end_time` overlaps existing range
- or the new reservation _contains_ an existing one or is _contained_ by it

This covers edge cases such as:

- Events starting or ending at the same time
- Nested overlaps

> ❌ If overlap is detected, the reservation is rejected with a clear message.

---

## 📈 Scalability Considerations

- **Pagination**: Implemented via Laravel’s `paginate()` and Vue UI integration
- **Indexed DB structure**: `room_id`, `event_date`, and `status` fields are used in conflict detection queries and benefit from proper indexing.

---

## ✅ Unit Testing

We used [Pest PHP](https://pestphp.com) for expressive, clean tests. We cover:

- ✅ Creating a valid reservation
- ✅ Rejecting overlapping reservations
- ✅ Same room, different day (allowed)
- ✅ Same time, different room (allowed)
- ✅ Exact boundary overlaps (e.g. 09:00–10:00 and 10:00–11:00 – allowed)

Factories are used to generate sample data with reusable test conditions.

---

## 🖼️ UI Highlights

- Event list for a given day
- Modal to register new reservations
- Automatic formatting for dates and times
- Toast notifications for validation errors or success
- Pagination only shown when needed

---

## 📋 Example Scenario (from spec)

1. Register:

    - ✅ Event A: Room 1, 09:00–11:00
    - ❌ Event B: Room 1, 10:30–12:00 (rejected: overlap)
    - ✅ Event C: Room 2, 10:00–11:30

2. Query between 10:00 and 10:45:

    - 🟢 Shows Event A and Event C

3. Cancel Event A:

    - Updates `status` to `cancelled`, no hard delete

4. Generate occupancy report (done via frontend)

---

## 📂 Project Structure

```plaintext
app/
├── Actions/
│   └── Reservations/
│       └── CreateReservationAction.php
├── Enums/
│   └── ReservationStatus.php
├── Http/
│   ├── Controllers/
│   │   └── ReservationController.php
│   ├── Requests/
│   │   └── ReservationEventController/StoreReservationRequest.php
├── Models/
│   ├── Reservation.php
│   ├── Room.php
│   └── Event.php

resources/js/
├── Pages/
│   └── reservations/
│       └── Index.vue
```

## 🚀 Getting Started

Follow these steps to set up the project locally:

### 1️⃣ Clone the repository

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```

### 2️⃣ Install Laravel dependencies

```
composer install
```

### 3️⃣ Install frontend dependencies

```
npm install

```

### 4️⃣ Copy the `.env` file and generate the app key

```
cp .env.example .env
php artisan key:generate
```

### 5️⃣ Configure your database

Open the `.env` file and update your database credentials:

```
DB_DATABASE=event-db
DB_USERNAME=root
DB_PASSWORD=
```

# 6️⃣ Run migrations and seed the database

```
php artisan migrate:fresh --seed
```

### 7️⃣ Start the development server

```
php artisan serve
npm run dev
```

## 🧪 Running Tests

This project uses [Pest PHP](https://pestphp.com) for clean and expressive testing.

```
php artisan test
```
