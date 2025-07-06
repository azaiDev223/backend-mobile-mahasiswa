<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Penting untuk keamanan form --}}
    <title>Sistem Akademik UNIMAL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --color-primary: #00712D;
            --color-secondary-bg: #FFFBE6;
            --color-accent: #FF9100;
            --text-on-dark: #FFFFFF;
        }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .navbar-brand img { height: 40px; }
        .hero { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://via.placeholder.com/1500x800/00712D/ffffff?text=Gedung+Rektorat+UNIMAL') no-repeat center center/cover; color: var(--text-on-dark); padding: 100px 0; text-align: center; }
        .section-title { text-align: center; font-size: 2.5em; color: var(--color-primary); margin-bottom: 50px; position: relative; }
        .section-title::after { content: ''; width: 80px; height: 4px; background-color: var(--color-accent); position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); }
        .feature-item, .testimonial-item, .stat-item { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-item:hover, .testimonial-item:hover, .stat-item:hover { transform: translateY(-10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .stat-item i { font-size: 3em; }
        .bg-primary { background-color: var(--color-primary) !important; }
        .btn-warning { background-color: var(--color-accent) !important; border-color: var(--color-accent) !important; color: var(--text-on-dark) !important; }
        .btn-warning:hover { background-color: #E68200 !important; border-color: #E68200 !important; }
        .form-response { display: none; margin-top: 15px; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://via.placeholder.com/150x50/FFFFFF/00712D?text=UNIMAL" alt="Logo UNIMAL">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#stats">Statistik</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section id="home" class="hero">
        <div class="container">
            <h1>Sistem Informasi Akademik Modern UNIMAL</h1>
            <p>Platform terintegrasi untuk mengelola aktivitas akademik dengan lebih efisien.</p>
            <a href="#contact" class="btn btn-warning btn-lg cta-button fw-bold">Hubungi Kami</a>
        </div>
    </section>

    {{-- Bagian Statistik Dinamis --}}
    <section id="stats" class="py-5">
        <div class="container">
            <h2 class="section-title">Statistik</h2>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm stat-item p-4">
                        <i class="fas fa-users text-primary mb-3"></i>
                        <h3>{{ $stats['mahasiswa'] }}+</h3>
                        <p class="text-muted">Mahasiswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm stat-item p-4">
                        <i class="fas fa-user-tie text-primary mb-3"></i>
                        <h3>{{ $stats['dosen'] }}+</h3>
                        <p class="text-muted">Dosen Pengajar</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm stat-item p-4">
                        <i class="fas fa-graduation-cap text-primary mb-3"></i>
                        <h3>{{ $stats['prodi'] }}+</h3>
                        <p class="text-muted">Program Studi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Fitur Dinamis --}}
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <div class="row g-4">
                @forelse ($features as $feature)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 text-center shadow-sm feature-item p-4">
                            <i class="{{ $feature->icon }} fa-2x text-primary mb-3"></i>
                            <h3 class="fs-5">{{ $feature->title }}</h3>
                            <p class="text-muted">{{ $feature->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Fitur akan segera ditambahkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Bagian Testimoni Dinamis --}}
    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="section-title">Testimoni</h2>
            <div class="row g-4">
                @forelse ($testimonials as $testimonial)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm testimonial-item p-4">
                            <p class="fst-italic">"{{ $testimonial->content }}"</p>
                            <div class="fw-bold mt-auto text-primary">- {{ $testimonial->name }}</div>
                            <small class="text-muted">{{ $testimonial->role }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Jadilah yang pertama memberikan testimoni!</p>
                @endforelse
            </div>
            <hr class="my-5">
            {{-- Form untuk menambah testimoni --}}
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="text-center mb-4">Bagikan Pengalaman Anda</h3>
                    <form id="testimonial-form">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="testimonial-name" placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="testimonial-role" placeholder="Peran Anda (cth: Mahasiswa Sistem Informasi)" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="testimonial-content" rows="4" placeholder="Tulis testimoni Anda di sini..." required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Kirim Testimoni</button>
                        </div>
                    </form>
                    <div id="testimonial-response" class="form-response"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Kontak Dinamis --}}
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form id="contact-form">
                        <div class="row">
                            <div class="col-md-6 mb-3"><input type="text" class="form-control" id="contact-name" placeholder="Nama" required></div>
                            <div class="col-md-6 mb-3"><input type="email" class="form-control" id="contact-email" placeholder="Email" required></div>
                        </div>
                        <div class="mb-3"><input type="text" class="form-control" id="contact-subject" placeholder="Subjek"></div>
                        <div class="mb-3"><textarea class="form-control" id="contact-message" rows="5" placeholder="Pesan Anda" required></textarea></div>
                        <div class="text-center"><button type="submit" class="btn btn-primary">Kirim Pesan</button></div>
                    </form>
                    <div id="contact-response" class="form-response"></div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Sistem Akademik UNIMAL. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- JavaScript untuk Form Dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const testimonialForm = document.getElementById('testimonial-form');
            const contactForm = document.getElementById('contact-form');
            const testimonialResponseEl = document.getElementById('testimonial-response');
            const contactResponseEl = document.getElementById('contact-response');

            const handleFormSubmit = async (event, url, formData, responseEl) => {
                event.preventDefault();
                const submitButton = event.target.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        responseEl.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        event.target.reset();
                    } else {
                        // Menangani error validasi dari Laravel
                        let errorMessage = result.message;
                        if (result.errors) {
                            errorMessage = Object.values(result.errors).flat().join('<br>');
                        }
                        responseEl.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                    }
                } catch (error) {
                    responseEl.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>`;
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = event.target.id === 'testimonial-form' ? 'Kirim Testimoni' : 'Kirim Pesan';
                    responseEl.style.display = 'block';
                }
            };

            testimonialForm.addEventListener('submit', (e) => {
                const formData = {
                    name: document.getElementById('testimonial-name').value,
                    role: document.getElementById('testimonial-role').value,
                    content: document.getElementById('testimonial-content').value,
                };
                handleFormSubmit(e, '/api/testimonials', formData, testimonialResponseEl);
            });

            contactForm.addEventListener('submit', (e) => {
                const formData = {
                    name: document.getElementById('contact-name').value,
                    email: document.getElementById('contact-email').value,
                    subject: document.getElementById('contact-subject').value,
                    message: document.getElementById('contact-message').value,
                };
                handleFormSubmit(e, '/api/contact', formData, contactResponseEl);
            });
        });
    </script>
</body>
</html>
