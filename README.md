# Jara: An Advanced To-Do List

## Software Requirements Specification

Dokumen ini berisi spesifikasi kebutuhan perangkat lunak untuk aplikasi **Jara: An Advanced To-Do List**.

Jara merupakan aplikasi web untuk mengelola tugas pribadi maupun tim. User dapat membuat project, mengelola task, menentukan prioritas dan deadline, serta berkolaborasi dengan user lain dalam sebuah project.

---

## 1. Tujuan

Sistem dikembangkan untuk membantu user:

* Mengorganisasi tugas berdasarkan project.
* Menentukan prioritas dan deadline tugas.
* Memantau status penyelesaian tugas.
* Berkolaborasi dengan user lain dalam project.
* Memantau progress project.

Admin dapat mengelola akun user dalam sistem.

---

## 2. Aktor

### Admin

Admin dapat:

* Login.
* Melihat daftar user.
* Menambahkan user.
* Menghapus user.

### User

User dapat:

* Register.
* Login dan logout.
* Membuat project.
* Mengubah project.
* Menghapus project.
* Menambahkan member ke project.
* Melihat member project.
* Menghapus member project.
* Membuat task.
* Mengubah task.
* Menghapus task.
* Menentukan priority task.
* Menentukan deadline task.
* Menandai task sebagai selesai.
* Melihat progress project.

---

## 3. Functional Requirements

| ID    | Requirement               | Aktor       |
| ----- | ------------------------- | ----------- |
| FR-01 | Register                  | User        |
| FR-02 | Login                     | User, Admin |
| FR-03 | Logout                    | User, Admin |
| FR-04 | User Management           | Admin       |
| FR-05 | Project CRUD              | User        |
| FR-06 | Project Member Management | Owner       |
| FR-07 | Task CRUD                 | User        |
| FR-08 | Task Priority             | User        |
| FR-09 | Task Deadline             | User        |
| FR-10 | Complete Task             | User        |
| FR-11 | Project Progress          | User        |

---

## 4. Project & Team

Setiap project memiliki seorang **owner**.

Owner dapat menambahkan user lain sebagai member project.

Struktur hubungan:

```text
User
 ├── Owns ───────> Project
 │                   │
 │                   ├── Members
 │                   │
 │                   └── Tasks
 │
 └── Member ─────> Project
```

---

## 5. Task

Setiap task berada di dalam satu project.

Task memiliki:

* Title
* Description
* Priority
* Deadline
* Status

### Priority

```text
low
medium
high
```

### Status

```text
pending
completed
```

---

## 6. Progress Monitoring

Progress project dihitung berdasarkan jumlah task yang telah selesai.

```text
Progress = (Completed Tasks / Total Tasks) × 100%
```

Jika project belum memiliki task, progress bernilai `0%`.

---

## 7. Database

Database menggunakan MySQL dengan tabel utama:

```text
users
projects
project_members
tasks
```

### Relationship

```text
users 1 ───── N projects
users N ───── N projects
              │
       project_members

projects 1 ───── N tasks
```

Foreign key:

```text
projects.owner_id
    -> users.id

project_members.project_id
    -> projects.id

project_members.user_id
    -> users.id

tasks.project_id
    -> projects.id
```

---

## 8. Non-Functional Requirements

### Security

* Password harus disimpan dalam bentuk hash.
* Setiap user hanya dapat mengakses fitur sesuai role dan permission.
* Hanya owner yang dapat mengelola member project.

### Usability

Interface harus sederhana dan mudah digunakan.

### Maintainability

Implementasi mengikuti struktur dan standar Laravel yang digunakan dalam project.

### Data Integrity

Database harus menggunakan foreign key dan constraint untuk menjaga konsistensi data.

---

## 9. Scope

### Included

* Authentication
* User management
* Project management
* Project member management
* Task management
* Task priority
* Task deadline
* Task completion
* Progress monitoring

### Not Included

* Chat
* Notification
* File upload
* Comments
* Categories
* Email notification
* Real-time collaboration

---

## 10. Programmer Assignment

| Programmer | Responsibility                      | Branch                  |
| ---------- | ----------------------------------- | ----------------------- |
| P1         | Authentication & User Management    | `feature/auth-user`     |
| P2         | Project & Team Management           | `feature/project-team`  |
| P3         | Task & Progress Management          | `feature/task-progress` |
| PM         | Integration, testing & coordination | `main`                  |

---

## 11. Acceptance Criteria

Project dinyatakan memenuhi SRS apabila:

* [ ] User dapat register.
* [ ] User dapat login dan logout.
* [ ] Admin dapat mengelola user.
* [ ] User dapat membuat project.
* [ ] User dapat mengubah project.
* [ ] User dapat menghapus project.
* [ ] Owner dapat menambahkan member.
* [ ] Owner dapat menghapus member.
* [ ] User dapat membuat task.
* [ ] User dapat mengubah task.
* [ ] User dapat menghapus task.
* [ ] User dapat menentukan priority.
* [ ] User dapat menentukan deadline.
* [ ] User dapat menyelesaikan task.
* [ ] Sistem dapat menampilkan progress project.
* [ ] Seluruh fitur utama dapat berjalan pada branch `main`.

---

## 12. Technology Stack

* **Backend:** Laravel
* **Database:** MySQL
* **Frontend:** Mengikuti frontend stack yang digunakan pada project
* **Version Control:** Git & GitHub
