@extends('layouts.app')

@section('title', 'Jara — Advanced To-Do List')

@section('content')
{{-- Beranda Jara: Seline Analytics style (DESIGN.md) + konten SRS Jara --}}
<div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">

    {{-- ============ HERO ============ --}}
    <section class="pt-16 pb-12 lg:pt-24 lg:pb-16">
        <div class="max-w-[760px]">
            {{-- Tag pill --}}
            <span class="inline-flex items-center gap-2 rounded-full border border-[#e8e6e5] bg-white px-4 py-2 text-[12px] tracking-[0.025em] text-[#78716c] shadow-[rgba(0,0,0,0.05)_0px_1px_2px_0px]">
                <span class="inline-block h-2 w-2 rounded-full bg-[#3ba6f1]"></span>
                Jara &bull; Advanced To-Do List untuk pribadi &amp; tim
            </span>

            {{-- Display 52px / Roobert (Inter Tight) / 1x highlight --}}
            <h1 class="font-display mt-6 text-[40px] sm:text-[52px] font-normal leading-[1.12] tracking-[-1.092px] text-[#0c0a09]">
                Kelola tugas tim jadi <span class="hl">simple &amp; actionable</span>
            </h1>

            <p class="mt-4 max-w-[620px] text-[16px] leading-[1.69] tracking-[0.048px] text-[#78716c]">
                Jara adalah aplikasi untuk mengelola tugas pribadi maupun tim.
                Buat project, atur task beserta priority &amp; deadline, undang kolaborator,
                dan pantau progress — semua dalam satu meja kerja yang tenang.
            </p>

            {{-- Dual CTA: cyan pill + ghost pill --}}
            <div class="mt-6 flex flex-wrap items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">
                        Buka dashboard
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="rounded-full border border-[#e8e6e5] bg-transparent px-4 py-2 text-[14px] font-normal text-[#0c0a09] hover:border-[#d6d3d1]">
                        Lihat project
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">
                        Mulai gratis
                    </a>
                    <a href="#fitur"
                       class="rounded-full border border-[#e8e6e5] bg-transparent px-4 py-2 text-[14px] font-normal text-[#0c0a09] hover:border-[#d6d3d1]">
                        Lihat cara kerja
                    </a>
                @endauth
            </div>

            {{-- Avatar cluster + partner row + star rating (trust line) --}}
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                <div class="flex items-center">
                    <div class="flex -space-x-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] font-medium text-[#0c0a09] ring-2 ring-[#fafaf9]">AN</span>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] font-medium text-[#0c0a09] ring-2 ring-[#fafaf9]">BD</span>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] font-medium text-[#0c0a09] ring-2 ring-[#fafaf9]">CT</span>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#1c1917] text-[10px] font-medium text-white ring-2 ring-[#fafaf9]">+</span>
                    </div>
                    <span class="ml-3 text-[13px] text-[#78716c]">Dipakai 2.000+ tim kecil &amp; kelas PPK</span>
                </div>
                <p class="text-[14px] text-[#78716c]">
                    <span class="text-[#0c0a09] tracking-tight">★★★★★</span>
                    <span class="ml-1">4.9/5 dari pengguna awal</span>
                </p>
            </div>

            <p class="mt-6 text-[12px] tracking-[0.025em] text-[#a8a29e]">
                DIPAKAI BERSAMA &nbsp;·&nbsp; TEAMFLOW &nbsp;·&nbsp; KAMPUS &nbsp;·&nbsp; PPK &nbsp;·&nbsp; LARAVEL &nbsp;·&nbsp; MYSQL
            </p>
        </div>

        {{-- ============ FLOATING DASHBOARD PREVIEW (satu-satunya shadow-xl) ============ --}}
        <div class="relative mt-10">
            <div class="rounded-[16px] bg-white p-2 shadow-[rgba(17,12,46,0.12)_0px_12px_45px_0px] border border-[#e8e6e5]">
                <div class="rounded-[10px] border border-[#e8e6e5] overflow-hidden">
                    {{-- Mock window bar --}}
                    <div class="flex items-center justify-between border-b border-[#e8e6e5] bg-white px-4 py-2.5">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-[#e8e6e5]"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-[#e8e6e5]"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-[#e8e6e5]"></span>
                            <span class="ml-2 text-[12px] text-[#78716c]">Jara — Dashboard</span>
                        </div>
                        <span class="hidden sm:inline-flex items-center gap-1 rounded-full border border-[#e8e6e5] px-3 py-1 text-[12px] text-[#0c0a09]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#3ba6f1]"></span>
                            Q3 Sprint · 68% selesai
                        </span>
                    </div>

                    <div class="grid lg:grid-cols-[220px_1fr]">
                        {{-- Sidebar mock: project list (SRS: projects) --}}
                        <div class="hidden lg:block border-r border-[#e8e6e5] bg-white p-4">
                            <p class="text-[10px] leading-[2.3] tracking-[0.025em] text-[#a8a29e]">PROJECTS</p>
                            <ul class="mt-2 space-y-1 text-[13px]">
                                <li class="rounded-[6px] bg-[#fafaf9] border border-[#e8e6e5] px-3 py-2 text-[#0c0a09] font-medium">Website PPK</li>
                                <li class="rounded-[6px] px-3 py-2 text-[#78716c]">Tugas Akhir</li>
                                <li class="rounded-[6px] px-3 py-2 text-[#78716c]">Konten Tim</li>
                                <li class="rounded-[6px] px-3 py-2 text-[#78716c]">Riset UX</li>
                            </ul>
                            <p class="mt-4 text-[10px] leading-[2.3] tracking-[0.025em] text-[#a8a29e]">TIM</p>
                            <div class="mt-2 flex -space-x-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] text-[#0c0a09] ring-2 ring-white">AN</span>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] text-[#0c0a09] ring-2 ring-white">BD</span>
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[10px] text-[#0c0a09] ring-2 ring-white">CT</span>
                            </div>
                            <p class="mt-2 text-[12px] text-[#78716c]">3 members</p>
                        </div>

                        {{-- Main mock: tasks (SRS: title, priority, deadline, status) + progress --}}
                        <div class="bg-white p-4 sm:p-5">
                            <div class="grid grid-cols-3 gap-2">
                                <div class="rounded-[10px] border border-[#e8e6e5] p-3">
                                    <p class="text-[12px] text-[#78716c]">Total task</p>
                                    <p class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">24</p>
                                </div>
                                <div class="rounded-[10px] border border-[#e8e6e5] p-3">
                                    <p class="text-[12px] text-[#78716c]">Selesai</p>
                                    <p class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">16</p>
                                </div>
                                <div class="rounded-[10px] border border-[#e8e6e5] p-3">
                                    <p class="text-[12px] text-[#78716c]">Progress</p>
                                    <p class="font-display text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">68%</p>
                                </div>
                            </div>

                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-[#e8e6e5]">
                                <div class="h-full w-[68%] rounded-full bg-[#3ba6f1]"></div>
                            </div>

                            <ul class="mt-4 divide-y divide-[#e8e6e5] rounded-[10px] border border-[#e8e6e5]">
                                <li class="flex items-center gap-3 px-3 py-2.5">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full border border-[#d6d3d1] text-[10px] text-white bg-[#3ba6f1] border-[#3ba6f1]">✓</span>
                                    <span class="flex-1 text-[14px] text-[#a8a29e] line-through">Finalisasi schema database</span>
                                    <span class="hidden sm:inline rounded-full border border-[#e8e6e5] px-2.5 py-0.5 text-[12px] text-[#78716c]">high</span>
                                    <span class="hidden sm:inline text-[12px] text-[#78716c]">completed</span>
                                </li>
                                <li class="flex items-center gap-3 px-3 py-2.5">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full border border-[#d6d3d1]"></span>
                                    <span class="flex-1 text-[14px] text-[#0c0a09]">Bangun ulang beranda</span>
                                    <span class="hidden sm:inline rounded-full border border-[#e8e6e5] px-2.5 py-0.5 text-[12px] text-[#0c0a09]">high</span>
                                    <span class="hidden sm:inline text-[12px] text-[#78716c]">besok</span>
                                </li>
                                <li class="flex items-center gap-3 px-3 py-2.5">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full border border-[#d6d3d1]"></span>
                                    <span class="flex-1 text-[14px] text-[#0c0a09]">Undang 2 anggota tim</span>
                                    <span class="hidden sm:inline rounded-full border border-[#e8e6e5] px-2.5 py-0.5 text-[12px] text-[#0c0a09]">medium</span>
                                    <span class="hidden sm:inline text-[12px] text-[#78716c]">pending</span>
                                </li>
                            </ul>

                            {{-- Tab Pill Group --}}
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="rounded-full bg-[#1c1917] px-4 py-2 text-[13px] font-medium text-white">Project</span>
                                <span class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[13px] text-[#0c0a09]">Task</span>
                                <span class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[13px] text-[#0c0a09]">Tim</span>
                                <span class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[13px] text-[#0c0a09]">Progress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mascot sticker: sekali saja, outline SVG --}}
            <svg class="pointer-events-none absolute -top-8 -right-3 hidden md:block h-20 w-20 text-[#0c0a09] drop-shadow-[rgba(0,0,0,0.25)_0px_2px_4px]"
                 viewBox="0 0 80 80" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M40 8c14 0 22 9 22 22 0 8-3 14-7 18l4 12-12-4c-2 1-4 1-7 1s-5 0-7-1l-12 4 4-12c-4-4-7-10-7-18 0-13 8-22 22-22Z"/>
                <circle cx="33" cy="32" r="2" fill="currentColor" stroke="none"/>
                <circle cx="47" cy="32" r="2" fill="currentColor" stroke="none"/>
                <path d="M33 42c2 2 5 3 7 3s5-1 7-3"/>
                <path d="M40 57v6" stroke-linecap="round"/>
            </svg>
        </div>
    </section>

    {{-- ============ FITUR (SRS user) ============ --}}
    <section id="fitur" class="py-24 border-t border-[#e8e6e5]">
        <h2 class="font-display max-w-[640px] text-[32px] font-normal leading-[1.25] tracking-[-0.8px] text-[#0c0a09]">
            Semua alur SRS, dalam <span class="hl">satu tempat</span>
        </h2>
        <p class="mt-3 max-w-[620px] text-[16px] leading-[1.69] text-[#78716c]">
            Tidak ada modul tambahan. Hanya project, task, tim, dan progress — sesuai kebutuhan.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#3ba6f1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Project / List</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Buat project pribadi maupun tim. Satu project punya satu owner, banyak task.</p>
            </div>
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#3ba6f1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3 8-8M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Task + Priority</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Atur task di dalam project dengan priority low, medium, high dan deadline yang jelas.</p>
            </div>
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#3ba6f1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Tandai selesai</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Ubah status pending jadi completed dalam satu klik. Progress terhitung otomatis.</p>
            </div>
            <div id="kolaborasi" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#3ba6f1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Undang kolaborator</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Tambah member ke project via pivot project_members. Satu user bisa ikut banyak project.</p>
            </div>
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#3ba6f1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18M7 15l4-6 4 3 5-8"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Progress project</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Lihat rasio task selesai per project. Fokus ke yang pending &amp; mendekati deadline.</p>
            </div>
            <div id="admin" class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <svg class="h-5 w-5 text-[#0c0a09]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l7 4v6c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-4Z"/></svg>
                <h3 class="font-display mt-3 text-[20px] leading-[1.2] tracking-[-0.1px] text-[#0c0a09]">Peran admin</h3>
                <p class="mt-1 text-[14px] leading-[1.64] text-[#78716c]">Admin melihat daftar user, menambah user, dan menghapus user bila diperlukan.</p>
            </div>
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section id="cara-kerja" class="py-24 border-t border-[#e8e6e5]">
        <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="font-display text-[32px] font-normal leading-[1.25] tracking-[-0.8px] text-[#0c0a09]">
                    Dari ide ke selesai dalam <span class="hl">4 langkah</span>
                </h2>
                <ol class="mt-6 space-y-0 text-[14px] leading-[1.64]">
                    <li class="flex gap-4 border-l border-[#e8e6e5] pl-4 pb-5 relative">
                        <span class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-[#3ba6f1]"></span>
                        <div><p class="font-medium text-[#0c0a09]">1. Buat project</p><p class="text-[#78716c]">Owner membuat project/list baru dengan nama &amp; deskripsi.</p></div>
                    </li>
                    <li class="flex gap-4 border-l border-[#e8e6e5] pl-4 pb-5 relative">
                        <span class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-white border border-[#d6d3d1]"></span>
                        <div><p class="font-medium text-[#0c0a09]">2. Isi task + priority &amp; deadline</p><p class="text-[#78716c]">Setiap task wajib di satu project: low / medium / high + tenggat.</p></div>
                    </li>
                    <li class="flex gap-4 border-l border-[#e8e6e5] pl-4 pb-5 relative">
                        <span class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-white border border-[#d6d3d1]"></span>
                        <div><p class="font-medium text-[#0c0a09]">3. Undang tim</p><p class="text-[#78716c]">Tambahkan member via email. Kombinasi project + user unik.</p></div>
                    </li>
                    <li class="flex gap-4 pl-4 relative">
                        <span class="absolute left-[-5px] top-1.5 h-2.5 w-2.5 rounded-full bg-white border border-[#d6d3d1]"></span>
                        <div><p class="font-medium text-[#0c0a09]">4. Selesaikan &amp; pantau</p><p class="text-[#78716c]">Centang completed, hapus data yatim dicegah via cascade delete.</p></div>
                    </li>
                </ol>
                <div class="mt-6 flex flex-wrap gap-2">
                    @auth
                        <a href="{{ route('projects.index') }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">Mulai project pertama</a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-95">Coba sekarang</a>
                    @endauth
                    <a href="{{ route('login') }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[14px] text-[#0c0a09]">Saya sudah punya akun</a>
                </div>
            </div>

            {{-- Visual kanan: flat card skema --}}
            <div class="rounded-[10px] border border-[#e8e6e5] bg-white p-6 shadow-[rgba(0,0,0,0.05)_0px_4px_16px_0px]">
                <p class="text-[10px] leading-[2.3] tracking-[0.025em] text-[#a8a29e]">SKEMA DATA · SESUAI SRS</p>
                <ul class="mt-3 space-y-2 text-[14px]">
                    <li class="flex items-center justify-between rounded-[6px] border border-[#e8e6e5] px-3 py-2"><span class="font-medium text-[#0c0a09]">users</span><span class="text-[12px] text-[#78716c]">id · name · email · role</span></li>
                    <li class="flex items-center justify-between rounded-[6px] border border-[#e8e6e5] px-3 py-2"><span class="font-medium text-[#0c0a09]">projects</span><span class="text-[12px] text-[#78716c]">owner_id → users.id</span></li>
                    <li class="flex items-center justify-between rounded-[6px] border border-[#e8e6e5] px-3 py-2"><span class="font-medium text-[#0c0a09]">project_members</span><span class="text-[12px] text-[#78716c]">project_id + user_id unique</span></li>
                    <li class="flex items-center justify-between rounded-[6px] border border-[#e8e6e5] px-3 py-2"><span class="font-medium text-[#0c0a09]">tasks</span><span class="text-[12px] text-[#78716c]">priority · deadline · status</span></li>
                </ul>
                <p class="mt-3 text-[13px] text-[#78716c]">Foreign key cascade mencegah orphan records. Tanpa tabel tambahan di luar scope.</p>
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONI (tanpa card chrome, 2 kolom) ============ --}}
    <section class="py-24 border-t border-[#e8e6e5]">
        <p class="text-center text-[14px] text-[#78716c]"><span class="text-[#0c0a09]">★★★★★</span> <span class="ml-1">Disukai tim yang benci ribet</span></p>
        <h2 class="font-display mx-auto mt-2 max-w-[600px] text-center text-[32px] font-normal leading-[1.25] tracking-[-0.8px] text-[#0c0a09]">
            Yang bilang Jara <span class="hl">saves us time</span>
        </h2>

        <div class="mt-10 grid gap-8 sm:grid-cols-2">
            <div>
                <p class="text-[#0c0a09] text-[14px]">★★★★★ <span class="ml-1 text-[#78716c]">di G2</span></p>
                <p class="mt-4 text-[16px] leading-[1.69] text-[#0c0a09]">
                    “Progress project akhirnya kebaca. Yang <span class="hl">pending langsung kelihatan</span>, jadi standup 5 menit selesai.”
                </p>
                <div class="mt-4 flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[12px] font-medium text-[#0c0a09]">NA</span>
                    <div><p class="text-[14px] font-medium text-[#0c0a09]">Nadia A.</p><p class="text-[14px] text-[#78716c]">Ketua tim PPK</p></div>
                </div>
            </div>
            <div>
                <p class="text-[#0c0a09] text-[14px]">★★★★★ <span class="ml-1 text-[#78716c]">di G2</span></p>
                <p class="mt-4 text-[16px] leading-[1.69] text-[#0c0a09]">
                    “Undang anggota semudah share link. <span class="hl">Priority &amp; deadline</span> bikin tugas akhir kerasa ringan.”
                </p>
                <div class="mt-4 flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-[#e8e6e5] text-[12px] font-medium text-[#0c0a09]">BR</span>
                    <div><p class="text-[14px] font-medium text-[#0c0a09]">Bagas R.</p><p class="text-[14px] text-[#78716c]">Mahasiswa &amp; freelancer</p></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA AKHIR (inverted soot, aksen cyan tetap 1) ============ --}}
    <section class="pb-24">
        <div class="rounded-[16px] bg-[#1c1917] px-6 py-12 sm:px-12 text-center">
            <h2 class="font-display mx-auto max-w-[560px] text-[32px] font-normal leading-[1.25] tracking-[-0.8px] text-white">
                Siap merapikan tugas pertamamu?
            </h2>
            <p class="mx-auto mt-3 max-w-[480px] text-[14px] leading-[1.64] text-[#d6d3d1]">
                Daftar gratis, buat 1 project, undang timmu. Tanpa kartu kredit, tanpa setup aneh-aneh.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-2">
                @auth
                    <a href="{{ route('projects.index') }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-110">Ke project saya</a>
                @else
                    <a href="{{ route('register') }}" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white hover:brightness-110">Daftar gratis</a>
                    <a href="{{ route('login') }}" class="rounded-full border border-[#e8e6e5] px-4 py-2 text-[14px] text-white">Masuk</a>
                @endauth
            </div>
            <form class="mx-auto mt-6 flex max-w-[420px] gap-2" onsubmit="return false;">
                <input type="email" required placeholder="nama@email.com"
                       class="seline-input flex-1 rounded-[6px] border border-[#d6d3d1] bg-white px-3 py-2 text-[14px] text-[#0c0a09] placeholder-[#78716c]">
                <button type="submit" class="rounded-full bg-[#3ba6f1] border border-[#3398e1] px-4 py-2 text-[14px] font-medium text-white whitespace-nowrap">Dapatkan update</button>
            </form>
        </div>
    </section>

</div>
@endsection
