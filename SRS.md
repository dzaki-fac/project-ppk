# SRS — Jara: An Advanced To-Do List

## 1. Pecah Requirement Menjadi SRS

| SRS | Fitur | Scope |
|-----|-------|-------|
| SRS-01 | Create List Management | Pengguna membuat daftar tugas baru dan otomatis menjadi pemilik (owner) |
| SRS-02 | Delete List Management | Owner menghapus daftar beserta seluruh tugas dan keanggotaan di dalamnya |
| SRS-03 | Transaction & Authorization | Proses penghapusan berjalan secara atomik dan permintaan dari pengguna yang tidak berwenang ditolak |
| SRS-04 | Input Validation & SQL Security | Validasi seluruh input dan penggunaan prepared statement untuk mencegah SQL injection |

## Pembagian 3 Programmer

| Programmer | SRS | Fitur | Branch |
|------------|-----|-------|--------|
| P1 | SRS-01 | Create List Management | `feature/create-list` |
| P2 | SRS-02 + SRS-03 | Delete List + Transaction & Authorization | `feature/delete-list` |
| P3 | SRS-04 | Input Validation & SQL Security | `feature/input-security` |

> Catatan: nama branch di atas adalah rencana awal. Eksekusi tim memakai ulang
> branch sebelumnya (lihat Bagian 3) — `feature/auth-user`,
> `feature/project-team`, `feature/task-progress`.

Mapping SRS ke implementasi (kode saat ini di `main`):

* **SRS-01** → `ProjectController@store`: `owner_id` otomatis diisi dari user yang login (`$request->user()->id`), pembuat langsung menjadi owner. Route: `POST /projects` (`projects.store`).
* **SRS-02** → `ProjectController@destroy`: hapus project beserta seluruh task (`cascadeOnDelete` di FK `tasks.project_id`) dan keanggotaan (`cascadeOnDelete` di FK `project_members.project_id`). Route: `DELETE /projects/{project}` (`projects.destroy`).
* **SRS-03** → `Gate::authorize('delete', $project)` + `ProjectPolicy@delete` (hanya owner, non-owner ditolak 403) dan penghapusan dibungkus `DB::transaction` sehingga atomik.
* **SRS-04** → semua input divalidasi via `$request->validate()` (nama wajib, `exists:users,id`, enum `priority`/`status`, dsb.) dan seluruh query lewat Eloquent Query Builder (prepared statement / parameter binding) sehingga aman dari SQL injection.

---

## 2. Database Schema

Jara adalah aplikasi untuk mengelola tugas pribadi maupun tim. User dapat:

* Membuat project/list.
* Mengatur task di dalam project.
* Menentukan priority dan deadline.
* Menandai task sebagai selesai.
* Mengundang user lain ke dalam project untuk berkolaborasi.
* Melihat progress project.

Admin dapat:

* Melihat daftar user.
* Menambahkan user.
* Menghapus user.

### Database schema

Gunakan 4 tabel utama:

#### 1. users

Gunakan migration users bawaan Laravel, lalu tambahkan:

* `id` BIGINT UNSIGNED PRIMARY KEY
* `name` VARCHAR
* `email` VARCHAR UNIQUE
* `password` VARCHAR
* `role` ENUM('admin', 'user'), default 'user'
* `remember_token`
* `created_at`
* `updated_at`

#### 2. projects

* `id` BIGINT UNSIGNED PRIMARY KEY
* `owner_id` BIGINT UNSIGNED, foreign key ke `users.id`
* `name` VARCHAR
* `description` TEXT nullable
* `created_at`
* `updated_at`

Relationship:

* Satu user dapat memiliki banyak project.
* Satu project memiliki satu owner.

#### 3. project_members

Tabel pivot untuk hubungan many-to-many antara users dan projects.

* `id` BIGINT UNSIGNED PRIMARY KEY
* `project_id` BIGINT UNSIGNED, foreign key ke `projects.id`
* `user_id` BIGINT UNSIGNED, foreign key ke `users.id`
* `created_at`
* `updated_at`

Tambahkan unique constraint pada kombinasi:
`project_id + user_id`

Relationship:

* Satu project dapat memiliki banyak member.
* Satu user dapat menjadi member di banyak project.

#### 4. tasks

* `id` BIGINT UNSIGNED PRIMARY KEY
* `project_id` BIGINT UNSIGNED, foreign key ke `projects.id`
* `title` VARCHAR
* `description` TEXT nullable
* `priority` ENUM('low', 'medium', 'high'), default 'medium'
* `deadline` DATETIME nullable
* `status` ENUM('pending', 'completed'), default 'pending'
* `created_at`
* `updated_at`

Relationship:

* Satu project memiliki banyak task.
* Satu task hanya berada di satu project.

### Foreign key behavior

Gunakan foreign key yang sesuai dan cegah orphan records.

* `projects.owner_id` → `users.id`
* `project_members.project_id` → `projects.id`
* `project_members.user_id` → `users.id`
* `tasks.project_id` → `projects.id`

Untuk data yang bergantung pada parent, gunakan `cascadeOnDelete()` jika sesuai.

### Laravel implementation

Buat migration untuk seluruh schema tersebut dan pastikan migration dapat dijalankan menggunakan:

`php artisan migrate`

Pastikan urutan migration benar sehingga foreign key tidak gagal.

Jangan membuat tabel tambahan seperti comments, notifications, categories, atau task assignments karena belum termasuk requirement.

Setelah migration selesai, buat/update model Eloquent dan relationship berikut:

**User**

* `hasMany(Project::class, 'owner_id')`
* `belongsToMany(Project::class, 'project_members')`

**Project**

* `belongsTo(User::class, 'owner_id')`
* `belongsToMany(User::class, 'project_members')`
* `hasMany(Task::class)`

**Task**

* `belongsTo(Project::class)`

Jangan mengubah fitur di luar scope database yang sudah ditentukan.

---

## 3. Tambahan Minggu Ini (FR-12 – FR-14)

Requirement konsolidasi (terbaru):

> Pengguna dapat membuat daftar tugas baru dengan otomatis menjadi pemiliknya,
> serta menghapus daftar yang dimilikinya beserta seluruh tugas dan keanggotaan
> di dalamnya; setiap proses ini harus berjalan secara atomik sehingga jika
> salah satu langkah gagal maka seluruh perubahan dibatalkan, permintaan dari
> pengguna yang tidak berwenang harus ditolak, dan seluruh input pengguna wajib
> divalidasi serta diproses menggunakan query terparameterisasi (prepared
> statement) untuk mencegah SQL injection.

Dipecah menjadi:

| ID | Nama | Scope (dari requirement-mu) | Yang setengah / belum |
|----|------|------------------------------|------------------------|
| FR-12 | Create List Atomic + Secure | Buat list + otomatis `owner_id` = user login, validasi `name`/`description`, tolak unauthorized, binding query, atomik | Kurang: `DB::transaction` eksplisit |
| FR-13 | Delete List Cascade Atomic | Hanya owner bisa hapus, `tasks` + `project_members` ikut hilang tanpa orphan, gagal → rollback | Ada: policy owner-only + `cascadeOnDelete` + transaction. Kurang: test cascade |
| FR-14 | Input Validation & SQL-Injection Prevention | Semua input `name`/`description` tervalidasi, semua query via Eloquent/Builder (prepared statement), no raw SQL dari user, filter `status`/`priority` whitelist | Ada implisit. Kurang: penegasan eksplisit + test |

Status terkini di `main`: FR-13 transaction `destroy` sudah ada (commit
`feat(project)`), tinggal test cascade. FR-12 store belum dibungkus transaksi.
FR-14 baru terpenuhi implisit (belum ada test keamanan).

## Pembagian 3 Programmer (memakai ulang branch sebelumnya)

| Programmer | Branch (dipakai ulang) | FR | Scope kerja |
|------------|------------------------|----|-------------|
| P1 | `feature/auth-user` | FR-14 (domain auth & user) | Penegasan validasi register/login/user-CRUD + hash password + session regenerate + test keamanan auth |
| P2 | `feature/project-team` | FR-12 + FR-13 | `DB::transaction` eksplisit di `store` (create + daftarkan owner sebagai member, atomik) dan `destroy`; test cascade: tasks + members hilang, gagal → rollback, non-owner 403 |
| P3 | `feature/task-progress` | FR-14 (domain task) | Penegasan whitelist `status`/`priority` + validasi title/description/deadline, audit tidak ada raw SQL dari input user + test |

Alasan pembagian: FR-12 dan FR-13 sama-sama menyentuh `ProjectController` +
`ProjectPolicy` (domain P2), sehingga satu orang mengerjakan agar tidak
conflict. FR-14 yang cross-cutting dibelah dua per domain: auth/user ke P1,
task ke P3.
