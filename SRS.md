Buat database schema untuk aplikasi web **Jara: An Advanced To-Do List** menggunakan Laravel dan MySQL.

### Requirement aplikasi

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
