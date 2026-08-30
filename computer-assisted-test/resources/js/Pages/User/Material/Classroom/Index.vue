<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Live Class</title>
    </Head>

    <div class="page-wrapper">
        <div class="page-content classroom-page">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
                <div class="breadcrumb-title pe-3 fw-bold border-0">Live Class</div>
                <div class="ps-3 border-start">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <Link href="/user/dashboard" class="text-primary"><i class="bx bx-home-alt"></i></Link>
                            </li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Live Class Terbaru</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-7">
                            <label class="form-label fw-semibold mb-2">Cari Kelas</label>
                            <div class="position-relative">
                                <input
                                    type="text"
                                    v-model="form.search"
                                    class="form-control form-control-lg ps-5 classroom-control"
                                    placeholder="Cari berdasarkan nama kelas atau instruktur"
                                >
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                                    <i class="bx bx-search fs-5"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-lg-4">
                            <label class="form-label fw-semibold mb-2">Kategori</label>
                            <select v-model="form.category_id" class="form-select form-select-lg classroom-control" @change="handleSearch">
                                <option value="">Semua Kategori</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 col-lg-1 d-grid">
                            <Link href="/user/classrooms" class="btn btn-outline-primary btn-lg">
                                <i class="bx bx-refresh"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="$page.props.session.error || $page.props.session.success" class="mb-4">
                <div v-if="$page.props.session.error" class="alert alert-danger border-0 shadow-sm rounded-3 mb-2">
                    <i class="bx bx-error-circle me-2"></i>
                    <span v-html="$page.props.session.error"></span>
                </div>
                <div v-if="$page.props.session.success" class="alert alert-success border-0 shadow-sm rounded-3 mb-0">
                    <i class="bx bx-check-circle me-2"></i>
                    <span v-html="$page.props.session.success"></span>
                </div>
            </div>

            <div class="schedule-frame mb-4">
                <div class="schedule-frame__head">
                    <h4 class="mb-0 fw-bold text-dark">Jadwal Live Class</h4>
                    <div class="d-flex align-items-center gap-2">
                        <button
                            class="schedule-nav-btn"
                            type="button"
                            @click="scrollSchedule('left')"
                            :disabled="!canScrollLeft || !classrooms.data.length"
                            aria-label="Geser ke kiri"
                        >
                            <i class="bx bx-chevron-left"></i>
                        </button>
                        <button
                            class="schedule-nav-btn"
                            type="button"
                            @click="scrollSchedule('right')"
                            :disabled="!canScrollRight || !classrooms.data.length"
                            aria-label="Geser ke kanan"
                        >
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="schedule-frame__body">
                    <div
                        v-if="classrooms.data.length > 0"
                        ref="scheduleScroller"
                        class="schedule-scroller"
                        @scroll="onScheduleScroll"
                    >
                        <div class="schedule-item" v-for="(classroom, index) in classrooms.data" :key="index">
                            <div class="card h-100 classroom-card">
                                <div class="classroom-card__header">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="badge bg-light text-primary border fw-semibold">{{ classroom.category.name }}</span>
                                        <span v-if="classroom.status == 'active'" class="badge bg-success text-white">
                                            <span class="status-dot me-1"></span>Aktif
                                        </span>
                                        <span v-else-if="classroom.status == 'inactive'" class="badge bg-danger text-white">Selesai</span>
                                        <span v-else class="badge bg-warning text-dark">Coming Soon</span>
                                    </div>
                                    <h5 class="fw-bold text-white mb-0 mt-3 text-truncate">{{ classroom.title }}</h5>
                                </div>

                                <div class="card-body d-flex flex-column p-3 p-md-4">
                                    <div class="d-flex flex-column gap-2 mb-3 text-muted small">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-user-circle text-primary me-2 fs-5"></i>
                                            <span class="fw-semibold text-dark">{{ classroom.user ? classroom.user.name : 'Instruktur Profesional' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-time-five text-primary me-2 fs-5"></i>
                                            <span>{{ formatDateWithTimeHourMinute(classroom.start_time) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-hourglass text-primary me-2 fs-5"></i>
                                            <span>{{ classroom.duration }} Menit</span>
                                        </div>
                                    </div>

                                    <div class="mb-3 min-h-badge">
                                        <template v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <template v-if="classroom.member_categories && classroom.member_categories.length">
                                                    <span
                                                        v-for="(memberCategory, subIndex) in classroom.member_categories"
                                                        :key="subIndex"
                                                        class="badge bg-light text-primary border fw-semibold"
                                                    >
                                                        {{ memberCategory.name }}
                                                    </span>
                                                </template>
                                                <span v-else class="badge bg-light text-primary border fw-semibold">Akses Publik</span>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="d-flex align-items-end justify-content-between gap-2 mt-auto">
                                        <div class="price-container">
                                            <div v-if="$page.props.auth.user.member_type == 2" class="mb-1">
                                                <span v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.user_access.length > 0" class="text-success fw-semibold small"><i class="bx bxs-check-circle"></i> Enrolled</span>
                                                <span v-else-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length > 0 && checkMemberCategories(classroom.member_categories) == true" class="text-success fw-semibold small"><i class="bx bxs-check-circle"></i> Enrolled</span>
                                                <span v-else-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true)" class="text-success fw-semibold small"><i class="bx bxs-check-circle"></i> Enrolled</span>
                                            </div>

                                            <div v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                                <div v-if="classroom.price_type == 2">
                                                    <div v-if="classroom.price_before_discount == classroom.price_after_discount">
                                                        <h5 class="text-primary fw-bold mb-0">Rp{{ formatPrice(classroom.price_after_discount) }}</h5>
                                                    </div>
                                                    <div v-else>
                                                        <div class="small text-muted text-decoration-line-through">Rp{{ formatPrice(classroom.price_before_discount) }}</div>
                                                        <h5 class="text-primary fw-bold mb-0">Rp{{ formatPrice(classroom.price_after_discount) }}</h5>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="((resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.price_type == 1) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length == 0) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.member_categories.length == 0 || classroom.price_type == 1))) && !($page.props.auth.user.member_type == 2 && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true))">
                                                <span class="badge bg-success text-white">Gratis</span>
                                            </div>
                                        </div>

                                        <div class="action-btn text-end">
                                            <div v-if="classroom.status == 'active'">
                                                <template v-if="$page.props.auth.user.member_type == 2 && resolvedEnableClassroomSales(classroom.category.enable_classroom_sales) == 1">
                                                    <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1">
                                                        <Link v-if="classroom.user_access.length > 0 || classroom.price_type == 1" :href="`/user/classrooms/${classroom.id}`" class="btn btn-primary btn-sm px-3">Masuk</Link>
                                                        <Link v-else :href="`/user/transactions/classroom/${classroom.id}/buy`" class="btn btn-danger btn-sm px-3">Beli</Link>
                                                    </template>

                                                    <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2">
                                                        <Link v-if="checkMemberCategories(classroom.member_categories) == true" :href="`/user/classrooms/${classroom.id}`" class="btn btn-primary btn-sm px-3">Masuk</Link>
                                                        <Link v-else :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-success btn-sm px-3">Upgrade</Link>
                                                    </template>

                                                    <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3">
                                                        <Link v-if="(classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true || classroom.price_type == 1)" :href="`/user/classrooms/${classroom.id}`" class="btn btn-primary btn-sm px-3">Masuk</Link>
                                                        <Link v-else-if="classroom.user_access.length == 0 && classroom.price_type == 2" :href="`/user/transactions/classroom/${classroom.id}/buy`" class="btn btn-danger btn-sm px-3">Beli</Link>
                                                        <Link v-if="checkMemberCategories(classroom.member_categories) == false" :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-success btn-sm px-3 mt-1">Upgrade</Link>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-primary btn-sm px-3">Ikuti Kelas</Link>
                                                </template>
                                            </div>
                                            <div v-else-if="classroom.status == 'inprogress' && classroom.release_date">
                                                <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-outline-primary btn-sm px-3">Detail</Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="schedule-empty">
                        Belum ada rekomendasi live class saat ini.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4 mb-3" v-if="classrooms.data.length">
                <Pagination :links="classrooms.links"/>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutUser from '../../../../Layouts/Layout.vue';
    import { Link, Head } from '@inertiajs/inertia-vue3';
    import Pagination from '../../../../Components/Pagination.vue';
    import { Inertia } from '@inertiajs/inertia';
    import { reactive, watch, ref } from 'vue';
    import debounce from 'lodash/debounce';

    export default {
        layout: LayoutUser,
        components: {
            Link,
            Head,
            Pagination
        },
        props: {
            classrooms: Object,
            userMemberCategories: Object,
            categories: Object,
            session: Object,
        },
        data() {
            return {
                canScrollLeft: false,
                canScrollRight: false,
            };
        },
        setup(props) {
            const scheduleScroller = ref(null);
            const form = reactive({
                search: ref('' || (new URL(document.location)).searchParams.get('search')),
                category_id: ref((new URL(document.location)).searchParams.get('category_id') || ''),
            });

            const handleSearch = () => {
                Inertia.get(
                    '/user/classrooms',
                    {
                       search: form.search,
                       category_id: form.category_id,
                    },
                    {
                        preserveState: true,
                        preserveScroll: true,
                        replace: true
                    }
                );
            };

            const debouncedSearch = debounce(handleSearch, 1000);
            watch(() => form.search, () => debouncedSearch());

            const checkMemberCategories = (categories) => {
                if (categories.length > 0) {
                    const categoryIds = categories.map(category => category.id);
                    return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
                }

                return true;
            };

            return {
                form,
                scheduleScroller,
                checkMemberCategories,
                handleSearch
            };
        },
        mounted() {
            this.$nextTick(() => this.updateScheduleNavState());
            window.addEventListener('resize', this.updateScheduleNavState);
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.updateScheduleNavState);
        },
        watch: {
            classrooms: {
                handler() {
                    this.$nextTick(() => this.updateScheduleNavState());
                },
                deep: true,
            }
        },
        methods: {
            formatPrice(value) {
                const val = (value / 1).toFixed().replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            },
            updateScheduleNavState() {
                if (!this.scheduleScroller || !this.classrooms?.data?.length) {
                    this.canScrollLeft = false;
                    this.canScrollRight = false;
                    return;
                }

                const node = this.scheduleScroller;
                const maxLeft = node.scrollWidth - node.clientWidth;
                this.canScrollLeft = node.scrollLeft > 2;
                this.canScrollRight = node.scrollLeft < (maxLeft - 2);
            },
            onScheduleScroll() {
                this.updateScheduleNavState();
            },
            scrollSchedule(direction) {
                if (!this.scheduleScroller) return;

                const amount = Math.max(this.scheduleScroller.clientWidth * 0.85, 280);
                const offset = direction === 'left' ? -amount : amount;

                this.scheduleScroller.scrollBy({
                    left: offset,
                    behavior: 'smooth',
                });

                window.setTimeout(() => {
                    this.updateScheduleNavState();
                }, 250);
            },
            resolvedClassroomSalesType(classroomSalesType) {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.classroom_sales_type
                    : classroomSalesType;
            },
            resolvedEnableClassroomSales(enableClassroomSales) {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.enable_classroom_sales
                    : enableClassroomSales;
            }
        }
    };
</script>

<style scoped>
.classroom-page {
    --core-primary: #008cff;
    --core-primary-dark: #037de2;
    --schedule-border: #c9cdd4;
}

.classroom-control {
    border-color: #d8e0ea;
}

.classroom-control:focus {
    border-color: var(--core-primary);
    box-shadow: 0 0 0 0.25rem rgba(0, 140, 255, 0.16);
}

.classroom-card {
    border: 1px solid #e9f1fa;
    border-radius: 14px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.classroom-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(2, 76, 136, 0.15);
}

.classroom-card__header {
    background: linear-gradient(135deg, var(--core-primary) 0%, #2eb8ff 100%);
    padding: 1rem;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #198754;
    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5);
    animation: pulse-green 1.6s infinite;
}

.min-h-badge {
    min-height: 28px;
}

.schedule-frame {
    border: 1px solid var(--schedule-border);
    border-radius: 16px;
    overflow: hidden;
    background-color: #fff;
}

.schedule-frame__head {
    background-color: #f0f1f3;
    border-bottom: 1px solid #dde1e6;
    padding: 0.85rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.schedule-frame__body {
    min-height: 240px;
    background-color: #fff;
    padding: 1rem;
}

.schedule-scroller {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    padding-bottom: 0.25rem;
}

.schedule-scroller::-webkit-scrollbar {
    height: 6px;
}

.schedule-scroller::-webkit-scrollbar-thumb {
    background: #cfd5dd;
    border-radius: 999px;
}

.schedule-item {
    min-width: 300px;
    width: min(360px, 90vw);
    flex: 0 0 auto;
    scroll-snap-align: start;
}

.schedule-nav-btn {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid #d2d7de;
    color: #8a93a0;
    background: #f5f6f8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.schedule-nav-btn i {
    font-size: 1.15rem;
}

.schedule-nav-btn:hover:not(:disabled) {
    color: #4f5967;
    border-color: #bcc3cd;
    background: #eceef2;
}

.schedule-nav-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.schedule-empty {
    min-height: 208px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #616875;
    font-size: 1.08rem;
}

@keyframes pulse-green {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5); }
    70% { box-shadow: 0 0 0 6px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}

@media (max-width: 575.98px) {
    .classroom-card__header {
        padding: 0.9rem;
    }

    .schedule-frame__head {
        padding: 0.75rem 0.9rem;
    }

    .schedule-frame__body {
        padding: 0.9rem;
        min-height: 220px;
    }

    .schedule-item {
        min-width: 270px;
    }
}
</style>
