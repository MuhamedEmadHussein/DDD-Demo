# Laravel DDD Demo 🚀

Welcome to the **Domain-Driven Design (DDD) in Laravel** demonstration. This project showcases how to structure a large-scale Laravel application using DDD principles to separate business logic from technical infrastructure.

## 🏗️ The Architecture: DDD vs Traditional Laravel

### Traditional Laravel (MVC)
In a standard Laravel app, logic is often scattered:
- **Controllers** handle request logic AND business rules.
- **Models** are "Fat Models" containing database logic, validation, and business rules.
- **Services** (if used) are often just wrappers for Eloquent queries.

### DDD Approach (This Demo)
In this project, we prioritize the **Domain**:
- **Domain Layer (`app/Domains`)**: The heart of the app. It contains the business rules and is independent of Laravel.
- **Application Layer (`app/Application`)**: Orchestrates the domain objects to fulfill a use case (e.g., "Place an Order").
- **Infrastructure Layer (`app/Infrastructure`)**: Technical details. Database access (Eloquent), Mailers, API clients.
- **Presentation Layer (`app/Http`)**: Controllers, Routes, and Views. They only act as the entry point.

---

## 📁 Directory Structure

```text
app/
 ├─ Domains/           <-- Core Business Logic (The "Truth")
 │   └─ Order/
 │       ├─ Aggregates/    <-- Entry points for changing state (Order)
 │       ├─ Entities/      <-- Objects with Identity (OrderItem)
 │       ├─ ValueObjects/  <-- Immutable data (Price, Status)
 │       ├─ Repositories/  <-- Interfaces for data access
 │       └─ Events/        <-- Business events (OrderPlaced)
 ├─ Application/       <-- Use Cases / Orchestration
 │   └─ Services/      <-- PlaceOrderService, CancelOrderService
 ├─ Infrastructure/    <-- Technical Implementation
 │   └─ Persistence/   <-- Eloquent implementations of Repositories
 └─ Http/              <-- Presentation Layer (Entry Point)
```

---

## 💎 Core DDD Concepts Explained

### 1. Entities & Value Objects
- **Entity**: Has a unique identity (e.g., an Order with a UUID). Even if two orders have the same products, they are different.
- **Value Object**: No identity, defined by its attributes. `Price` is a value object. $50 USD is $50 USD. If you change the amount, it's a new Value Object.

### 2. Aggregates & Aggregate Roots
An **Aggregate** is a cluster of associated objects. The **Order** is the **Aggregate Root**. Every change to order items MUST go through the Order entity to ensure business rules (invariants) are maintained.

### 3. Repositories
Repositories are not just for "clean queries". They are a contract. The Domain says: *"I need to save an Order, I don't care if it's MySQL, MongoDB, or an API."* The Implementation lives in the Infrastructure layer.

### 4. Application Services
These services handle the "workflow". For "Place Order", the service:
1. Validates inputs.
2. Uses Domain Factories/Entities to create objects.
3. Calls the Repository to save.
4. Dispatches Events.

---

## 🚀 Getting Started

1. **Install Dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

2. **Database Setup**:
   ```bash
   php artisan migrate
   ```

3. **Run the App**:
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` to see the Professional Order Dashboard!

## 🌟 Features of this Demo
- **Glassmorphic UI**: A premium, modern interface.
- **State Management**: Orders can only be cancelled if they are in the `PENDING` state (Domain Rule).
- **UUIDs**: All entities use UUIDs for better distributed system compatibility.
- **Decoupled Logic**: You could swap Eloquent for any other DB without touching the `Domains` folder.

---
*Created with ❤️ for Advanced Laravel Developers.*
