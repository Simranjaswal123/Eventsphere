# EventSphere

A social events platform built with Laravel 12 and MongoDB. Users can create, discover, and RSVP to events with real-time search, comments, likes, and email notifications.

## Stack

- **Backend:** Laravel 12, PHP 8.2
- **Database:** MongoDB (`mongodb/laravel-mongodb`)
- **Frontend:** Blade templates, Vite
- **Queue/Cache:** Database driver
- **Mail:** Configurable (log by default)

## Features

- **Events** — Create, edit, delete events with images, tags, location, pricing, and capacity limits
- **Auth** — Register, login, forgot/reset password, email welcome on signup
- **RSVP** — Toggle RSVP with confirmation email, capacity enforcement, personal RSVP list
- **Social** — Like events, post/delete comments
- **Search** — Full-text search + live AJAX search
- **Profiles** — Public user profiles, dashboard, password change
- **REST API** — Public JSON API at `/api/v1/` (events, categories, featured, stats)
- **Localization** — English, Hindi, French (`/lang/{locale}`)
- **Admin** — Role-based middleware for admin actions

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/events` | List events |
| GET | `/api/v1/events/featured` | Featured events |
| GET | `/api/v1/events/{slug}` | Single event |
| GET | `/api/v1/categories` | All categories |
| GET | `/api/v1/stats` | Platform stats |
