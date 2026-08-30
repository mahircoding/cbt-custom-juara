<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Leaderboard</title>
    </Head>

    <div class="page-wrapper leaderboard-page">
        <div class="page-content">
            <div class="leaderboard-header mb-4">
                <div>
                    <h2 class="leaderboard-title mb-1">Leaderboard</h2>
                    <p class="leaderboard-subtitle mb-0">Lihat peringkat kamu dan peserta lain berdasarkan filter yang dipilih.</p>
                </div>
            </div>

            <div class="card filter-card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-bold">Filter</h6>
                        <Link href="/user/leaderboards" class="btn btn-reset btn-sm">
                            <i class="bx bx-refresh me-1"></i> Reset
                        </Link>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label form-label-modern">Program</label>
                            <select class="form-select form-select-modern" v-model="leaderboardForm.test_type">
                                <option value="">Semua Program</option>
                                <option value="exam">Latihan Soal</option>
                                <option value="tryout">Tryout</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label form-label-modern">Kategori</label>
                            <select class="form-select form-select-modern" v-model="leaderboardForm.category_id">
                                <option value="">Semua Kategori</option>
                                <option v-for="category in leaderboardFilters.categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label form-label-modern">Kategori Mapel</label>
                            <select class="form-select form-select-modern" v-model="leaderboardForm.lesson_category_id">
                                <option value="">Semua Kategori Mapel</option>
                                <option v-for="lessonCategory in leaderboardFilters.lessonCategories" :key="lessonCategory.id" :value="lessonCategory.id">
                                    {{ lessonCategory.name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label form-label-modern">Tryout</label>
                            <select class="form-select form-select-modern" v-model="leaderboardForm.exam_group_id" :disabled="leaderboardForm.test_type === 'exam'">
                                <option value="">Semua Tryout</option>
                                <option v-for="examGroup in leaderboardFilters.examGroups" :key="examGroup.id" :value="examGroup.id">
                                    {{ examGroup.title }}
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label form-label-modern">Cari Nama Peserta</label>
                            <div class="search-input-wrap">
                                <i class="bx bx-search"></i>
                                <input
                                    type="text"
                                    v-model="leaderboardForm.search_participant"
                                    class="form-control form-control-modern"
                                    placeholder="Ketik nama/email/username peserta..."
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-rank-card mb-4">
                <div class="my-rank-label">Peringkat Kamu</div>
                <div class="my-rank-value">
                    <span v-if="myLeaderboard">#{{ myLeaderboard.rank }} • {{ gradeFormat(myLeaderboard.score) }}</span>
                    <span v-else>N/A</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm leaderboard-table-card">
                <div class="card-header bg-transparent border-0 p-4 pb-2">
                    <h6 class="mb-0 fw-bold">Peringkat Peserta</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table leaderboard-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="90">Rank</th>
                                    <th>Profil</th>
                                    <th class="text-end" width="180">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!leaderboard.data.length">
                                    <td colspan="3" class="text-center py-4">Data leaderboard belum tersedia</td>
                                </tr>
                                <tr v-for="(participant, index) in leaderboard.data" :key="participant.user_id" :class="rowClass(index)">
                                    <td>
                                        <div class="rank-badge">{{ rowRank(index) }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img :src="avatarUrl(participant.photo)" class="avatar" alt="avatar">
                                            <div>
                                                <div class="fw-bold d-flex align-items-center gap-2">
                                                    {{ participant.name ?? participant.username ?? participant.code ?? '-' }}
                                                    <span v-if="rowRank(index) <= 3" class="medal">{{ medal(rowRank(index)) }}</span>
                                                </div>
                                                <small class="text-muted">{{ participant.email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold score-cell">{{ gradeFormat(participant.score ?? 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none d-grid gap-2">
                        <div v-if="!leaderboard.data.length" class="mobile-item text-center text-muted py-4">
                            Data leaderboard belum tersedia
                        </div>
                        <div v-for="(participant, index) in leaderboard.data" :key="`m-${participant.user_id}`" class="mobile-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rank-badge">{{ rowRank(index) }}</div>
                                    <span v-if="rowRank(index) <= 3" class="medal">{{ medal(rowRank(index)) }}</span>
                                </div>
                                <div class="score-cell">{{ gradeFormat(participant.score ?? 0) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <img :src="avatarUrl(participant.photo)" class="avatar avatar-sm" alt="avatar">
                                <div>
                                    <div class="fw-semibold">{{ participant.name ?? participant.username ?? participant.code ?? '-' }}</div>
                                    <small class="text-muted">{{ participant.email ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Pagination :links="leaderboard.links" align="end" class="mt-3" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { reactive, watch } from 'vue';
import debounce from 'lodash/debounce';
import { Inertia } from '@inertiajs/inertia';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
        Pagination,
    },

    props: {
        leaderboard: Object,
        myLeaderboard: Object,
        leaderboardFilters: Object,
    },

    setup(props) {
        const leaderboardForm = reactive({
            test_type: props.leaderboardFilters?.selected?.test_type ?? '',
            category_id: props.leaderboardFilters?.selected?.category_id ?? '',
            lesson_category_id: props.leaderboardFilters?.selected?.lesson_category_id ?? '',
            exam_group_id: props.leaderboardFilters?.selected?.exam_group_id ?? '',
            search_participant: props.leaderboardFilters?.selected?.search_participant ?? '',
        });

        const buildParams = () => ({
            test_type: leaderboardForm.test_type,
            category_id: leaderboardForm.category_id,
            lesson_category_id: leaderboardForm.lesson_category_id,
            exam_group_id: leaderboardForm.test_type === 'exam' ? '' : leaderboardForm.exam_group_id,
            search_participant: leaderboardForm.search_participant,
        });

        const applyFilters = () => {
            Inertia.get('/user/leaderboards', buildParams(), {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        };

        const debouncedApplyFilters = debounce(applyFilters, 450);

        watch(
            () => [
                leaderboardForm.test_type,
                leaderboardForm.category_id,
                leaderboardForm.lesson_category_id,
                leaderboardForm.exam_group_id,
                leaderboardForm.search_participant,
            ],
            () => {
                if (leaderboardForm.test_type === 'exam') {
                    leaderboardForm.exam_group_id = '';
                }

                debouncedApplyFilters();
            }
        );

        return {
            leaderboardForm,
        };
    },

    methods: {
        rowRank(index) {
            return index + 1 + (this.leaderboard.current_page - 1) * this.leaderboard.per_page;
        },
        medal(rank) {
            if (rank === 1) return '🥇';
            if (rank === 2) return '🥈';
            if (rank === 3) return '🥉';
            return '';
        },
        rowClass(index) {
            const rank = this.rowRank(index);
            if (rank === 1) return 'top-one';
            if (rank === 2) return 'top-two';
            if (rank === 3) return 'top-three';
            return '';
        },
        avatarUrl(photo) {
            if (photo) return `/storage/upload_files/photos/${photo}`;
            return '/assets/images/avatars/user.png';
        },
    },
};
</script>

<style scoped>
.leaderboard-page {
    --brand-primary: #008cff;
    --brand-primary-dark: #037de2;
    --brand-primary-rgb: 0, 140, 255;
    --brand-primary-soft: rgba(var(--brand-primary-rgb), 0.08);
    --text-main: #1f2430;
    --text-sub: #667085;
    --line: #e8e8ef;
    --bg-page: #f8fbff;
    background: radial-gradient(1200px 300px at 20% -15%, rgba(var(--brand-primary-rgb), 0.08) 0%, rgba(var(--brand-primary-rgb), 0) 60%), var(--bg-page);
}

.leaderboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.leaderboard-title {
    color: var(--text-main);
    font-weight: 700;
}

.leaderboard-subtitle {
    color: var(--text-sub);
}

.filter-card {
    border-radius: 16px;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 600;
    color: #454b5a;
    margin-bottom: 0.45rem;
}

.form-select-modern,
.form-control-modern {
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    min-height: 44px;
    font-size: 0.92rem;
}

.form-select-modern:focus,
.form-control-modern:focus {
    border-color: rgba(var(--brand-primary-rgb), 0.45);
    box-shadow: 0 0 0 0.2rem rgba(var(--brand-primary-rgb), 0.15);
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

.btn-reset {
    border-radius: 10px;
    border: 1px solid rgba(var(--brand-primary-rgb), 0.28);
    color: var(--brand-primary);
    background: #fff;
    font-weight: 600;
}

.btn-reset:hover {
    background: var(--brand-primary-soft);
}

.my-rank-card {
    background: linear-gradient(90deg, var(--brand-primary) 0%, var(--brand-primary-dark) 100%);
    border-radius: 14px;
    padding: 14px 18px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.my-rank-label {
    font-size: 1.15rem;
    font-weight: 600;
}

.my-rank-value {
    font-weight: 700;
}

.leaderboard-table-card {
    border-radius: 16px;
}

.leaderboard-table thead th {
    border-bottom: 1px solid var(--line);
    color: #5c6272;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.leaderboard-table tbody td {
    border-color: #f0f1f5;
}

.rank-badge {
    min-width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.92rem;
    background: rgba(var(--brand-primary-rgb), 0.12);
    color: var(--brand-primary);
}

.avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(16, 24, 40, 0.08);
}

.avatar-sm {
    width: 36px;
    height: 36px;
}

.score-cell {
    color: var(--brand-primary);
    font-weight: 700;
}

.medal {
    font-size: 1.05rem;
}

.top-one {
    background: linear-gradient(90deg, rgba(var(--brand-primary-rgb), 0.18), rgba(var(--brand-primary-rgb), 0.04));
}

.top-two {
    background: linear-gradient(90deg, rgba(var(--brand-primary-rgb), 0.12), rgba(var(--brand-primary-rgb), 0.03));
}

.top-three {
    background: linear-gradient(90deg, rgba(var(--brand-primary-rgb), 0.08), rgba(var(--brand-primary-rgb), 0.02));
}

.mobile-item {
    border: 1px solid #eceff5;
    border-radius: 12px;
    background: #fff;
    padding: 12px;
}

@media (max-width: 767px) {
    .leaderboard-title {
        font-size: 1.45rem;
    }

    .leaderboard-subtitle {
        font-size: 0.92rem;
    }

    .my-rank-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>
