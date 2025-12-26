# Hexagonal Notification Service

A production-grade, asynchronous notification service built with **Laravel** and **Hexagonal Architecture (Ports & Adapters)**. This service supports **multi-channel routing** (Email, SMS, Push) and is designed for high scalability and testability.

## 🚀 Features

*   **Hexagonal Architecture**: Strict separation of concerns. Pure Domain Logic (Entities, Value Objects) is completely isolated from Infrastructure (Laravel, Eloquent, Queues).
*   **Multi-Channel Support**: Broadcast a single notification to multiple channels (e.g., `['email', 'sms']`) simultaneously.
*   **Asynchronous Processing**: Heavy lifting is offloaded to **RabbitMQ** via Laravel Queues.
*   **Robust Failure Handling**:
    *   Automatic retries with exponential backoff.
    *   Dead Letter Queue (DLQ) handling.
    *   Detailed status tracking (`pending` -> `processing` -> `sent` | `failed`).
*   **Strategy Pattern**: Dynamically routes notifications to the correct Gateway (Email, SMS, Push) using a Factory.
*   **Production-Ready Docker Environment**: PHP 8.4, PostgreSQL 16, RabbitMQ, Nginx.

## 🛠 Tech Stack

*   **Framework**: Laravel 11.x / PHP 8.4
*   **Architecture**: DDD / Hexagonal (Ports & Adapters)
*   **Database**: PostgreSQL
*   **Queue Broker**: RabbitMQ
*   **Server**: Nginx

## 📂 Project Structure

The project follows a strict DDD directory structure, separating **Core** logic from the **App** framework.

```
src/
├── Domain/                 # PURE BUSINESS LOGIC (No Framework)
│   └── Notification/
│       ├── Entity/         # Rich Models with Invariants
│       ├── Enums/          # NotificationStatus, NotificationType
│       ├── Gateway/        # Interfaces (Ports) for external systems
│       └── Repository/     # Interfaces (Ports) for persistence
│
├── Application/            # ORCHESTRATION
│   ├── DTOs/               # Data Transfer Objects
│   └── UseCases/           # Application Services (Create, Send)
│
└── Infrastructure/         # ADAPTERS (The "Plumbing")
    ├── Gateways/           # implementations of Gateway Interfaces (EmailGateway, etc.)
    ├── Persistence/        # implementations of Repository Interfaces (Eloquent)
    └── Queue/              # Job implementations (RabbitMQ jobs)
```

## ⚡ Quick Start

We use a **Makefile** to simplify common Docker commands.

> **Note**: Custom ports are used to avoid conflicts with other local services:
> *   **App (Nginx)**: `4090` (instead of 80/8080)
> *   **PostgreSQL**: `4054` (instead of 5432)
> *   **RabbitMQ**: `4672`/`4673` (instead of 5672/15672)

### 1. Setup & Installation
Run this single command to build containers, install composer dependencies, generate keys, and run migrations.

```bash
cp .env.example .env
make setup
```

### 2. Access the Service
*   **API**: [http://localhost:4090](http://localhost:4090)
*   **RabbitMQ UI**: [http://localhost:4673](http://localhost:4673) (User: `guest`, Pass: `guest`)

> **Note**: The queue worker runs automatically in a dedicated Docker container (`notification_queue`).

## 🔗 API Usage

### Send a Notification

**Endpoint**: `POST /api/notifications`

**Payload**:
```json
{
    "channels": ["email", "sms"],
    "recipient": "user@example.com",
    "payload": {
        "subject": "Welcome!",
        "message": "This is a test notification sent to multiple channels."
    }
}
```

**Response**:
```json
{
    "message": "Notification accepted",
    "id": "346da688-012f-49af-8796-9d007b32dd7c",
    "status": "pending"
}
```

## ✅ Testing

We have comprehensive Feature tests ensuring the end-to-end flow works as expected.

```bash
make test
```

## 🏗 Key Design Decisions

1.  **Strict Domain Entities**: The `Notification` entity has a `private` constructor. Creation is strictly controlled via `create()` (enforcing business rules) and `reconstitute()` (for loading DB state without validation).
2.  **Gateway Factory**: A `GatewayFactory` decides at runtime which implementation to use based on the channel name.
3.  **Jobs as Adapters**: The Laravel Job (`ProcessNotificationJob`) acts as an infrastructural adapter that triggers the `SendNotificationUseCase`.
