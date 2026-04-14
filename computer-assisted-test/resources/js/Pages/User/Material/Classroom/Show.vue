<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Live Class</title>
    </Head>
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
                <div class="breadcrumb-title pe-3 font-bold text-slate-800 border-0">Live Class</div>
                <div class="ps-3 border-start border-slate-200">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0 text-sm">
                            <li class="breadcrumb-item"><Link href="/user/dashboard" class="text-indigo-600"><i class="bx bx-home-alt"></i></Link></li>
                            <li class="breadcrumb-item"><Link href="/user/classrooms" class="text-indigo-600">Live Class</Link></li>
                            <li class="breadcrumb-item active text-slate-500" aria-current="page">Detail Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Detail Card -->
                    <div class="card radius-15 border-0 shadow-sm overflow-hidden mb-4">
                        <div class="card-header bg-gradient-indigo-modern p-4 border-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill mb-2 small uppercase tracking-wider">{{ classroom.category.name }}</span>
                                    <h3 class="text-white font-bold mb-0 leading-tight">{{ classroom.title }}</h3>
                                </div>
                                <Link href="/user/classrooms" class="btn btn-white-glass btn-sm radius-10">
                                    <i class='bx bx-arrow-back'></i> Kembali
                                </Link>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4 mb-5">
                                <div class="col-md-6 col-lg-3 text-center border-end border-slate-100">
                                    <div class="icon-box-soft-indigo mx-auto mb-2">
                                        <i class='bx bx-user-circle fs-4'></i>
                                    </div>
                                    <p class="text-xs text-slate-400 uppercase font-bold mb-1">Guru / Instruktur</p>
                                    <p class="text-sm font-bold text-slate-700 mb-0">{{ classroom.user ? classroom.user.name : 'Instruktur' }}</p>
                                </div>
                                <div class="col-md-6 col-lg-3 text-center border-end border-slate-100">
                                    <div class="icon-box-soft-indigo mx-auto mb-2">
                                        <i class='bx bx-calendar fs-4'></i>
                                    </div>
                                    <p class="text-xs text-slate-400 uppercase font-bold mb-1">Tanggal</p>
                                    <p class="text-sm font-bold text-slate-700 mb-0">{{ dateFormat(classroom.start_time) }}</p>
                                </div>
                                <div class="col-md-6 col-lg-3 text-center border-end border-slate-100">
                                    <div class="icon-box-soft-indigo mx-auto mb-2">
                                        <i class='bx bx-time fs-4'></i>
                                    </div>
                                    <p class="text-xs text-slate-400 uppercase font-bold mb-1">Waktu Mulai</p>
                                    <p class="text-sm font-bold text-slate-700 mb-0">{{ formatTimeOnly(classroom.start_time) }} {{ timezoneFormat($page.props.setting.timezone) }}</p>
                                </div>
                                <div class="col-md-6 col-lg-3 text-center">
                                    <div class="icon-box-soft-indigo mx-auto mb-2">
                                        <i class='bx bx-timer fs-4'></i>
                                    </div>
                                    <p class="text-xs text-slate-400 uppercase font-bold mb-1">Durasi</p>
                                    <p class="text-sm font-bold text-slate-700 mb-0">{{ classroom.duration }} Menit</p>
                                </div>
                            </div>

                            <div class="classroom-content">
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="font-bold text-slate-800 mb-0">Informasi Kelas</h5>
                                    <div class="flex-grow-1 ms-3 border-bottom border-slate-100"></div>
                                </div>
                                <div class="row gy-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-slate-50 rounded-4">
                                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Nama Internal</p>
                                            <p class="text-sm font-semibold text-slate-700 mb-0">{{ classroom.name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-slate-50 rounded-4">
                                            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Materi Utama</p>
                                            <p class="text-sm font-semibold text-slate-700 mb-0">{{ classroom.material }}</p>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="font-bold text-slate-800 mb-3">Deskripsi & Agenda</h5>
                                <div class="ck-content text-slate-600 leading-relaxed" v-html="classroom.description"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card radius-15 border-0 shadow-sm overflow-hidden sticky-top" style="top: 100px; z-index: 10;">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="mb-3 d-inline-block p-4 rounded-circle bg-indigo-50 border border-indigo-100">
                                    <i class='bx bx-video-off fs-1 text-indigo-500' v-if="classroom.status !== 'active'"></i>
                                    <i class='bx bx-video fs-1 text-indigo-500' v-else></i>
                                </div>
                                <h5 class="font-bold text-slate-800 mb-1">Status Link Kelas</h5>
                                <p class="text-sm text-slate-500">Gunakan link di bawah ini untuk bergabung ke sesi live.</p>
                            </div>

                            <div v-if="classroom.status === 'active'" class="d-grid gap-3">
                                <a :href="classroom.link" target="_blank" class="btn btn-indigo-modern py-3 shadow-lg radius-15 font-bold transition-all transform hover-scale">
                                    <i class='bx bx-link-external me-2 fs-5'></i> Gabung Sesi Live
                                </a>
                                <div class="p-3 rounded-4 bg-slate-50 border border-slate-100">
                                    <p class="text-xs text-slate-400 uppercase font-bold mb-2">Atau Copy Link</p>
                                    <div class="input-group input-group-sm">
                                        <input type="text" :value="classroom.link" class="form-control bg-white border-slate-200" readonly>
                                        <button class="btn btn-outline-indigo" @click="copyLink(classroom.link)">Copy</button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center p-4 bg-slate-50 rounded-4 border border-slate-100">
                                <span v-if="classroom.status === 'inprogress'" class="badge bg-amber-500 px-3 py-2 rounded-pill font-bold mb-2">COMING SOON</span>
                                <span v-else class="badge bg-rose-500 px-3 py-2 rounded-pill font-bold mb-2">SELESAI</span>
                                <p class="text-sm text-slate-600 mb-0">Link kelas akan aktif secara otomatis saat waktu mulai tercapai dan admin mengaktifkan sesi.</p>
                            </div>

                            <div class="mt-4 pt-4 border-top border-slate-100">
                                <div class="d-flex justify-content-between text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                                    <span>Tips Belajar</span>
                                    <i class='bx bx-bulb'></i>
                                </div>
                                <ul class="list-unstyled text-sm text-slate-600 mb-0 ps-0">
                                    <li class="mb-2 d-flex align-items-start"><i class='bx bx-check-circle text-emerald-500 me-2 mt-1'></i> Pastikan koneksi internet stabil</li>
                                    <li class="mb-2 d-flex align-items-start"><i class='bx bx-check-circle text-emerald-500 me-2 mt-1'></i> Gunakan Headset untuk audio lebih jernih</li>
                                    <li class="d-flex align-items-start"><i class='bx bx-check-circle text-emerald-500 me-2 mt-1'></i> Bergabunglah 5 menit sebelum dimulai</li>
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
    }
</script>

<style scoped>
    .bg-gradient-indigo-modern {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }
    .btn-white-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .btn-white-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }
    .icon-box-soft-indigo {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        border-radius: 12px;
    }
    .btn-indigo-modern {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
    }
    .btn-indigo-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        color: white;
    }
    .btn-outline-indigo {
        border: 1px solid #6366f1;
        color: #6366f1;
    }
    .btn-outline-indigo:hover {
        background-color: #6366f1;
        color: white;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }
    .radius-10 { border-radius: 10px; }
    .radius-15 { border-radius: 15px; }
</style>