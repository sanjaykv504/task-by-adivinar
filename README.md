# AI-Assisted Task Management System

A production-ready Task Management System built with Laravel 12, adhering strictly to the **Repository Pattern** and **Clean Architecture**. This system features dynamic UI rendering (Tailwind CSS), role-based access control (RBAC), and AI-generated task summaries and priorities.

## 🚀 Setup & Installation

1. **Clone & Install Dependencies**
   ```bash
   composer install
   npm install
   ```
2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Ensure your database (MySQL/SQLite) is configured properly in `.env`.*
3. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```
   *This seeds a default Admin and User for testing.*
4. **Compile Assets (Important for UI)**
   If you are on Windows PowerShell and experience script execution errors, use `cmd.exe`:
   ```bash
   cmd.exe /c "npm run build"
   ```
5. **Serve the Application**
   ```bash
   php artisan serve
   ```

---

## 🏛️ Architecture Overview (Clean Architecture)

The application strictly separates business logic from the controller layer, ensuring high maintainability and testability.

### 1. Repository Layer (`app/Repositories`)
- **MANDATORY**: Controllers **never** call Eloquent Models directly.
- `TaskRepositoryInterface` defines the contract for task data access.
- `TaskRepository` implements Eloquent queries, hiding database implementation details from the rest of the application.

### 2. Service Layer (`app/Services`)
- `TaskService`: Handles all business logic, manages `DB::transaction()`, and orchestrates calls between the `TaskRepository` and the `AIService`.
- `AIService`: Dedicated strictly to AI processing and parsing.

### 3. Roles & Policies (`app/Policies`)
- **Admin**: Full access to all tasks and user analytics.
- **User**: Scoped access (can only view, edit, and see analytics for tasks assigned to them).
- Enforced via Laravel Gates and `TaskPolicy`.

### 4. Enums (`app/Enums`)
- Used strict enumeration constants for `TaskPriority`, `TaskStatus`, and `UserRole` to enforce data integrity across the application.

---

## 🤖 AI Integration & Prompts

The AI integration is modularized within `AIService`. While currently mocked as requested by the fallback requirements, it is fully architected to swap in OpenAI or Gemini clients without touching controllers or repositories.

### Simulated AI Prompt
When a task is created or updated, the following prompt structure is conceptually sent to the AI service:
> *"Analyze the following task details:*
> *Title: {task_title}*
> *Description: {task_description}*
> *Due Date: {task_due_date}*
> 
> *Provide a 1-2 sentence concise summary of the task objectives. Then, objectively evaluate the priority level based on the deadline and scope, and return the priority strictly as 'low', 'medium', or 'high'."*

---

## 🎁 Bonus Features Implemented

1. **Query Scopes**: Implemented `scopeFilter()` in the `Task` model to handle Search, Status, and Priority filtering, keeping the Repository clean and modular.
2. **Dynamic UI Charts**: Built a pixel-perfect, CSS-only circular donut dashboard paired with dynamic `Chart.js` rendering for monthly analytics. 
3. **Database Transactions**: Wrapped all multi-step creation and update processes in `DB::transaction()` to prevent partial data writes if the AI Service fails.

---

## 🔌 REST API Endpoints

The API is fully documented and respects standard HTTP status codes.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/tasks` | Fetch paginated & filtered tasks |
| POST | `/api/tasks` | Create a new task |
| GET | `/api/tasks/{id}` | Fetch a specific task |
| PUT/PATCH | `/api/tasks/{id}` | Update task details |
| DELETE | `/api/tasks/{id}` | Delete a task |
| PATCH | `/api/tasks/{id}/status` | Quickly update only the status |
| GET | `/api/tasks/{id}/ai-summary` | Refresh/fetch AI insights |
