<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Live Class</title>
    </Head>

    <div class="page-wrapper">
        <div class="page-content classroom-show-page">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
                <div class="breadcrumb-title pe-3 fw-bold border-0">Live Class</div>
                <div class="ps-3 border-start">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><Link href="/user/dashboard" class="text-primary"><i class="bx bx-home-alt"></i></Link></li>
                            <li class="breadcrumb-item"><Link href="/user/classrooms" class="text-primary">Live Class</Link></li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Detail Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm overflow-hidden classroom-detail-card">
                        <div class="classroom-detail-header p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <span class="badge bg-white text-primary fw-semibold border mb-2">{{ classroom.category.name }}</span>
                                    <h3 class="text-white fw-bold mb-0">{{ classroom.title }}</h3>
                                </div>
                                <Link href="/user/classrooms" class="btn btn-light btn-sm border-0">
                                    <i class="bx bx-arrow-back"></i> Kembali
                                </Link>
                            </div>
                        </div>

                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="meta-box text-center h-100">
                                        <div class="meta-icon"><i class="bx bx-user-circle"></i></div>
                                        <p class="meta-label mb-1">Instruktur</p>
                                        <p class="meta-value mb-0">{{ classroom.user ? classroom.user.name : 'Instruktur' }}</p>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="meta-box text-center h-100">
                                        <div class="meta-icon"><i class="bx bx-calendar"></i></div>
                                        <p class="meta-label mb-1">Tanggal</p>
                                        <p class="meta-value mb-0">{{ dateFormat(classroom.start_time) }}</p>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="meta-box text-center h-100">
                                        <div class="meta-icon"><i class="bx bx-time"></i></div>
                                        <p class="meta-label mb-1">Waktu</p>
                                        <p class="meta-value mb-0">{{ formatTimeOnly(classroom.start_time) }} {{ timezoneFormat($page.props.setting.timezone) }}</p>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <div class="meta-box text-center h-100">
                                        <div class="meta-icon"><i class="bx bx-timer"></i></div>
                                        <p class="meta-label mb-1">Durasi</p>
                                        <p class="meta-value mb-0">{{ classroom.duration }} Menit</p>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title mb-2">Informasi Kelas</div>
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="info-box h-100">
                                        <p class="meta-label mb-1">Nama Internal</p>
                                        <p class="mb-0 fw-semibold text-dark">{{ classroom.name }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="info-box h-100">
                                        <p class="meta-label mb-1">Materi Utama</p>
                                        <p class="mb-0 fw-semibold text-dark">{{ classroom.material }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title mb-2">Deskripsi & Agenda</div>
                            <div class="ck-content text-muted" v-html="classroom.description"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top classroom-side-card" style="top: 95px;">
                        <div class="card-body p-3 p-md-4">
                            <div class="text-center mb-3">
                                <div class="status-icon-wrap mb-3">
                                    <i class="bx bx-video fs-1 text-primary" v-if="classroom.status === 'active'"></i>
                                    <i class="bx bx-video-off fs-1 text-primary" v-else></i>
                                </div>
                                <h5 class="fw-bold mb-1">Status Link Kelas</h5>
                                <p class="text-muted small mb-0">Gunakan link berikut untuk bergabung ke sesi live.</p>
                            </div>

                            <div v-if="classroom.status === 'active'" class="d-grid gap-3">
                                <a :href="classroom.link" target="_blank" class="btn btn-primary btn-lg fw-semibold">
                                    <i class="bx bx-link-external me-1"></i> Gabung Sesi Live
                                </a>

                                <div class="info-box">
                                    <p class="meta-label mb-2">Atau Copy Link</p>
                                    <div class="input-group input-group-sm">
                                        <input type="text" :value="classroom.link" class="form-control" readonly>
                                        <button class="btn btn-outline-primary" @click="copyLink(classroom.link)">Copy</button>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="info-box text-center">
                                <span v-if="classroom.status === 'inprogress'" class="badge bg-warning text-dark mb-2">COMING SOON</span>
                                <span v-else class="badge bg-danger text-white mb-2">SELESAI</span>
                                <p class="text-muted small mb-0">Link kelas akan aktif saat jadwal mulai dan sesi diaktifkan admin.</p>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold small text-uppercase text-muted">Tips Belajar</span>
                                    <i class="bx bx-bulb text-primary"></i>
                                </div>
                                <ul class="list-unstyled mb-0 small text-muted">
                                    <li class="mb-2"><i class="bx bx-check-circle text-success me-1"></i> Pastikan koneksi internet stabil.</li>
                                    <li class="mb-2"><i class="bx bx-check-circle text-success me-1"></i> Gunakan headset untuk audio lebih jelas.</li>
                                    <li><i class="bx bx-check-circle text-success me-1"></i> Bergabung 5 menit sebelum kelas dimulai.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutUser from '../../../../Layouts/Layout.vue';
    import { Link, Head } from '@inertiajs/inertia-vue3';
    import Swal from 'sweetalert2';

    export default {
      layout: LayoutUser,
      components: {
        Link,
        Head,
      },
      props: {
        classroom: Object,
      },
      methods: {
        formatTimeOnly(date) {
            return new Date(date).toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit' }).replace('.', ':');
        },
        copyLink(link) {
            navigator.clipboard.writeText(link);
            Swal.fire({
                icon: 'success',
                title: 'Link Berhasil Disalin',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        }
      }
    };
</script>

<style scoped>
.classroom-show-page {
    --core-primary: #008cff;
}

.classroom-detail-card,
.classroom-side-card {
    border-radius: 14px;
}

.classroom-detail-header {
    background: linear-gradient(135deg, var(--core-primary) 0%, #2eb8ff 100%);
}

.meta-box {
    border: 1px solid #e8f1fb;
    border-radius: 12px;
    padding: 0.85rem 0.7rem;
    background: #ffffff;
}

.meta-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(0, 140, 255, 0.12);
    color: var(--core-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 0.45rem;
}

.meta-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #7c8b9b;
    letter-spacing: 0.04em;
    font-weight: 700;
}

.meta-value {
    font-size: 0.8rem;
    color: #1e2b3a;
    font-weight: 600;
}

.section-title {
    font-weight: 700;
    color: #1e2b3a;
}

.info-box {
    border: 1px solid #e8f1fb;
    border-radius: 12px;
    background: #f9fcff;
    padding: 0.9rem;
}

.status-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(0, 140, 255, 0.2);
    background: rgba(0, 140, 255, 0.08);
}

@media (max-width: 991.98px) {
    .classroom-side-card {
        position: static !important;
        top: auto !important;
    }
}
</style>
