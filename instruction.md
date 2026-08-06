# AI Instruction Guide - Check In-Out

This file defines the coding direction and guardrails for AI assistants working in this repository.

## 1) Project Stack

- Backend: Laravel 13, PHP 8.3, Eloquent ORM.
- Frontend: Vue 3 + Inertia + TypeScript + Vite.
- Styling: Tailwind CSS v4.
- Routing helper: Ziggy.
- State management: Pinia (only for complex cross-component state).
- Modal/bottom sheet: @douxcode/vue-spring-bottom-sheet.
- Icons: @iconify/vue, prefer Material Design icon sets.

## 2) Core Coding Style

- Prefer strict typing in both TypeScript and PHP.
- Use TypeScript for Vue scripts. Avoid plain JavaScript in app logic.
- Naming:
  - snake_case for primitive variables and request/response payload keys.
  - camelCase for function names in Vue, TypeScript, and PHP.
  - PascalCase for classes, enums, interfaces, and types.
- Braces:
  - Allman style for functions (typescript, vue).
  - Linux/K&R style for PHP functions and classes.

## 3) Vue + Inertia Rules

- Use Vue 3 Composition API with <script setup lang="ts">.
- Prefer named functions over arrow functions when writing handlers and reusable logic.
- Use Inertia router navigation for page changes and data refreshes.
- Use Ziggy route() for URLs.
- For template refs on elements, avoid ref(null) patterns; use useTemplateRef().
- Keep page data hydration predictable:
  - Inertia partial reload keys must match the prop names expected by the page.
  - Example: checks pages must receive a checks prop (not check).
- Keep dashboard page structure consistent:
  - SearchCard for query/filter actions.
  - Data cards for list rendering.
  - PaginationCard for paging.

## 4) Laravel Rules

- Follow Laravel 13 conventions and latest framework practices.
- In routes/web.php:
  - Prefer Route::resource() with only() where possible.
  - Minimize ad-hoc Route::get/post/etc. unless required.
- Validate request payloads in controllers before persistence.
- Use eager loading for relationship-heavy dashboard pages.
- Keep redirect responses user-friendly with flash messages:
  - success: title + content
  - error: title + content
- Group model imports using braces and keep framework imports grouped where possible.
- Preferred import style in PHP files:
  - `use DateTimeInterface;`
  - `use Illuminate\Database\Eloquent\Collection as EloquentCollection;`
  - `use Illuminate\Http\{Request, UploadedFile};`
  - `use Illuminate\Support\Facades\Cache;`
  - `use Illuminate\Support\Str;`
  - blank line
  - `use App\Models\{BiometricDevice, Office, ReportType, CheckStatus, EmploymentType};`
- Prefer to add types on functions

## 5) Domain-Specific Data Rules

- Employee primary identifier is employee_no represented by employees.id (string).
- Employee display fields commonly used across pages:
  - id (employee_no), full_name, office, college, email, checks_count.
- Dashboard checks rely on employee relation and attachments/verified_user hydration.
- Check model employee relation must remain:
  - belongsTo(Employee::class, 'employee_id', 'id')

## 6) Existing Route and Page Conventions

- Dashboard routes are grouped under:
  - prefix: /dashboard
  - name: dashboard.*
- Current dashboard resources include checks, employees, offices, colleges, users, profile.
- When adding a CRUD area, follow existing resource route and page naming patterns:
  - resources/js/Pages/dashboard/<resource>/(index|create|edit|show).vue

## 7) Pinia Usage Guidance

- Use Pinia only when state is shared across multiple components/pages or requires persistent workflow state.
- Do not introduce Pinia for trivial local form state or one-off page toggles.

## 8) UI and CSS Direction

- Use Tailwind v4 utility patterns already present in the codebase.
- Prefer reusable existing components before adding new global primitives.
- Keep empty-state and action UX clear (reset search, create action, etc.).

## 9) Quality Checklist for AI Changes

Before finishing any change, verify:

- PHP and TS types are valid.
- Inertia props and partial reload keys match exactly.
- Route names used in Vue match backend resource route names.
- Relationship keys and eager loads are consistent with page expectations.
- New pages/components follow current folder and naming conventions.

## 10) Do Not

- Do not rename existing route names or Inertia prop keys without updating all callers.
- Do not replace resource routing style with many ad-hoc routes unless explicitly required.
- Do not add unnecessary global state stores.
- Do not introduce non-TypeScript Vue logic for core app features.

## 11) Priority Order for Conflicts

When rules conflict, use this priority:

1. Explicit user instruction in the current task.
2. This instruction.md file.
3. Existing codebase patterns in nearby files.
4. Framework defaults.
