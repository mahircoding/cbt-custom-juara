<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Sertifikat</title>
    </Head>

    <div class="page-wrapper certificate-page">
        <div class="page-content">
            <div class="certificate-hero mb-4">
                <h2 class="hero-title mb-1">Sertifikat</h2>
                <p class="hero-subtitle mb-0">Unduh sertifikat hasil ujian yang sudah kamu selesaikan.</p>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-10">
                            <label class="form-label form-label-modern">Cari Tryout</label>
                            <div class="search-input-wrap">
                                <i class="bx bx-search"></i>
                                <input
                                    type="text"
                                    v-model="form.search"
                                    class="form-control form-control-modern"
                                    placeholder="Cari judul tryout..."
                                >
                            </div>
                        </div>
                        <div class="col-12 col-lg-2 d-grid">
                            <Link href="/user/certificates" class="btn btn-outline-primary">
                                <i class="bx bx-refresh me-1"></i> Reset
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-transparent p-4 pb-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Daftar Sertifikat</h6>
                    <span class="small text-muted">{{ certificates.total ?? 0 }} data</span>
                </div>

                <div class="card-body p-4 pt-2">
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="70">No</th>
                                    <th>Judul Tryout</th>
                                    <th width="200">Kategori</th>
                                    <th width="180">Tanggal Selesai</th>
                                    <th width="120">Status</th>
                                    <th width="160" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!certificates.data.length">
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada sertifikat yang bisa diunduh.</td>
                                </tr>
                                <tr v-for="(certificate, index) in certificates.data" :key="certificate.id">
                                    <td><span class="number-badge">{{ rowNumber(index) }}</span></td>
                                    <td>
                                        <div class="fw-semibold">{{ certificate.exam_group ? certificate.exam_group.title : '-' }}</div>
                                        <small class="text-muted">{{ certificate.exam_group && certificate.exam_group.lesson_category ? certificate.exam_group.lesson_category.name : '-' }}</small>
                                    </td>
                                    <td>{{ certificate.exam_group && certificate.exam_group.category ? certificate.exam_group.category.name : '-' }}</td>
                                    <td>{{ formatDate(certificate.updated_at) }}</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                    <td class="text-center">
                                        <a :href="`/user/exam-groups/${certificate.id}/export-pdf`" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                            <i class="bx bx-download me-1"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-3 d-lg-none">
                        <div v-if="!certificates.data.length" class="mobile-item text-center text-muted py-4">
                            Belum ada sertifikat yang bisa diunduh.
                        </div>

                        <div v-for="(certificate, index) in certificates.data" :key="`mobile-${certificate.id}`" class="mobile-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="number-badge">{{ rowNumber(index) }}</span>
                                    <span class="fw-semibold">{{ certificate.exam_group ? certificate.exam_group.title : '-' }}</span>
                                </div>
                                <span class="badge bg-success">Selesai</span>
                            </div>

                            <div class="small text-muted mb-1">{{ certificate.exam_group && certificate.exam_group.category ? certificate.exam_group.category.name : '-' }}</div>
                            <div class="small text-muted mb-3">{{ formatDate(certificate.updated_at) }}</div>

                            <a :href="`/user/exam-groups/${certificate.id}/export-pdf`" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="bx bx-download me-1"></i> Download Sertifikat
                            </a>
                        </div>
                    </div>

                    <Pagination :links="certificates.links" align="end" class="mt-3" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { reactive, watch, ref } from 'vue';
import debounce from 'lodash/debounce';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
        Pagination,
    },

    props: {
        certificates: Object,
        filters: Object,
    },

    setup() {
        const form = reactive({
            search: ref((new URL(document.location)).searchParams.get('search') || ''),
        });

        const handleSearch = () => {
            Inertia.get(
                '/user/certificates',
                {
                    search: form.search,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            );
        };

        const debouncedSearch = debounce(handleSearch, 500);
        watch(() => form.search, () => debouncedSearch());

        return {
            form,
        };
    },

    methods: {
        rowNumber(index) {
            return index + 1 + (this.certificates.current_page - 1) * this.certificates.per_page;
        },
    },
};
</script>

<style scoped>
.certificate-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 600;
    color: #454b5a;
    margin-bottom: 0.45rem;
}

.form-control-modern {
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    min-height: 44px;
    font-size: 0.92rem;
}

.form-control-modern:focus {
    border-color: rgba(0, 140, 255, 0.45);
    box-shadow: 0 0 0 0.2rem rgba(0, 140, 255, 0.15);
}

.search-input-wrap {
    position: relative;
}

.search-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8a92a6;
    font-size: 1.15rem;
}

.search-input-wrap input {
    padding-left: 2.5rem;
}

.table-modern thead th {
    border-bottom: 1px solid #e8e8ef;
    color: #5c6272;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.table-modern tbody td {
    border-color: #f0f1f5;
}

.number-badge {
    min-width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    background: rgba(0, 140, 255, 0.12);
    color: #008cff;
}

.mobile-item {
    border: 1px solid #eceff5;
    border-radius: 12px;
    background: #fff;
    padding: 12px;
}
</style>
