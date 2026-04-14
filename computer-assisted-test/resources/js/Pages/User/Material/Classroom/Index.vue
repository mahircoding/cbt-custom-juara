<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Live Class</title>
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
                            <li class="breadcrumb-item active text-slate-500" aria-current="page">Live Class Terbaru</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Filters -->
            <div class="card radius-10 border-0 shadow-sm mb-4">
                <div class="card-body p-4 bg-white rounded-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="position-relative">
                                <input type="text" v-model="form.search" class="form-control form-control-lg ps-5 radius-15 border-slate-200 focus:border-indigo-500 focus:ring-0" placeholder="Cari Berdasarkan Nama / Judul Live Class ....">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-400">
                                    <i class="bx bx-search fs-5"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select v-model="form.category_id" class="form-select form-select-lg radius-15 border-slate-200 focus:border-indigo-500" @change="handleSearch">
                                <option value="">Semua Kategori</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <Link href="/user/classrooms" class="btn btn-lg btn-light w-100 radius-15 border-slate-200 text-slate-600">
                                <i class="bx bx-refresh fs-5"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div v-if="$page.props.session.error || $page.props.session.success" class="mb-4">
                <div v-if="$page.props.session.error" class="alert alert-danger border-0 shadow-sm rounded-4 p-3 d-flex align-items-center">
                    <i class='bx bx-error-circle fs-4 me-2'></i>
                    <div v-html="$page.props.session.error"></div>
                </div>
                <div v-if="$page.props.session.success" class="alert alert-success border-0 shadow-sm rounded-4 p-3 d-flex align-items-center">
                    <i class='bx bx-check-circle fs-4 me-2'></i>
                    <div v-html="$page.props.session.success"></div>
                </div>
            </div>

            <!-- Grid Content -->
            <div class="row g-4" v-if="classrooms.data.length > 0">
                <div class="col-md-6 col-xl-4" v-for="(classroom, index) in classrooms.data" :key="index">
                    <div class="card h-100 radius-15 border-0 shadow-sm card-hover-modern overflow-hidden">
                        <!-- Card Header / Banner -->
                        <div class="card-img-top position-relative" style="height: 140px; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                            <div class="position-absolute top-0 end-0 p-3">
                                <span v-if="classroom.status == 'active'" class="badge bg-white text-indigo-700 font-bold px-3 py-2 rounded-pill shadow-sm small">
                                    <span class="pulse-emerald me-1"></span> Aktif
                                </span>
                                <span v-else class="badge" :class="{ 'bg-rose-500 text-white': classroom.status == 'inactive', 'bg-amber-500 text-white': classroom.status === 'inprogress'}">
                                    {{ classroom.status === 'inactive' ? 'Selesai' : 'Coming Soon' }}
                                </span>
                            </div>
                            <div class="position-absolute bottom-0 start-0 p-3 w-100">
                                <span class="badge bg-indigo-900 bg-opacity-50 text-white font-semibold backdrop-blur-sm px-2 py-1 radius-5 small mb-1">{{ classroom.category.name }}</span>
                                <h5 class="text-white font-bold mb-0 text-truncate">{{ classroom.title }}</h5>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Class Meta Info -->
                            <div class="d-flex flex-column gap-3 mb-4">
                                <div class="d-flex align-items-center text-slate-600">
                                    <i class='bx bx-user-circle fs-5 me-2 text-indigo-500'></i>
                                    <span class="text-sm font-semibold">{{ classroom.user ? classroom.user.name : 'Instruktur Profesional' }}</span>
                                </div>
                                <div class="d-flex align-items-center text-slate-600">
                                    <i class='bx bx-time-five fs-5 me-2 text-indigo-500'></i>
                                    <span class="text-sm">{{ formatDateWithTimeHourMinute(classroom.start_time) }}</span>
                                </div>
                                <div class="d-flex align-items-center text-slate-600">
                                    <i class='bx bx-hourglass fs-5 me-2 text-indigo-500'></i>
                                    <span class="text-sm">{{ classroom.duration }} Menit Sesi Belajar</span>
                                </div>
                            </div>

                            <!-- Enrollment Badges -->
                            <div class="mb-4">
                                <template v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                    <div class="d-flex flex-wrap gap-1">
                                        <template v-if="classroom.member_categories && classroom.member_categories.length">
                                            <span v-for="(memberCategory, subIndex) in classroom.member_categories" :key="subIndex" class="badge bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-1 rounded-sm small">
                                                {{ memberCategory.name }}
                                            </span>
                                        </template>
                                        <span v-else class="badge bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-1 rounded-sm small">
                                            Akses Publik
                                        </span>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Actions & Pricing -->
                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div class="price-container">
                                    <!-- Price / Enrollment Status -->
                                    <div v-if="$page.props.auth.user.member_type == 2" class="mb-1">
                                        <span v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.user_access.length > 0" class="text-emerald-600 font-bold text-sm"><i class='bx bxs-check-circle'></i> Enrolled</span>
                                        <span v-else-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length > 0 && checkMemberCategories(classroom.member_categories) == true" class="text-emerald-600 font-bold text-sm"><i class='bx bxs-check-circle'></i> Enrolled</span>
                                        <span v-else-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true)" class="text-emerald-600 font-bold text-sm"><i class='bx bxs-check-circle'></i> Enrolled</span>
                                    </div>
                                    
                                    <div v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                        <div v-if="classroom.price_type == 2">
                                            <div v-if="classroom.price_before_discount == classroom.price_after_discount">
                                                <h5 class="text-indigo-600 font-bold mb-0">Rp{{ formatPrice(classroom.price_after_discount) }}</h5>
                                            </div>
                                            <div v-else>
                                                <div class="text-xs text-slate-400 text-decoration-line-through">Rp{{ formatPrice(classroom.price_before_discount) }}</div>
                                                <h5 class="text-indigo-600 font-bold mb-0">Rp{{ formatPrice(classroom.price_after_discount) }}</h5>
                                            </div>     
                                        </div>                               
                                    </div>
                                    <div v-if="((resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.price_type == 1) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length == 0) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.member_categories.length == 0 || classroom.price_type == 1))) && !($page.props.auth.user.member_type == 2 && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true))">
                                        <span class="badge bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 radius-5 font-bold">Gratis</span>
                                    </div> 
                                </div>

                                <div class="action-btn">
                                    <div v-if="classroom.status == 'active'">
                                        <template v-if="$page.props.auth.user.member_type == 2 && resolvedEnableClassroomSales(classroom.category.enable_classroom_sales) == 1">
                                            <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1">
                                                <Link v-if="classroom.user_access.length > 0 || classroom.price_type == 1" :href="`/user/classrooms/${classroom.id}`" class="btn btn-indigo radius-30 px-4">Masuk</Link>
                                                <Link v-else :href="`/user/transactions/classroom/${classroom.id}/buy`" class="btn btn-rose radius-30 px-4 shadow-sm">Beli</Link>
                                            </template>

                                            <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2">
                                                <Link v-if="checkMemberCategories(classroom.member_categories) == true" :href="`/user/classrooms/${classroom.id}`" class="btn btn-indigo radius-30 px-4">Masuk</Link>
                                                <Link v-else :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-emerald radius-30 px-3 shadow-sm">Upgrade</Link>
                                            </template>

                                            <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3">
                                                <Link v-if="(classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true || classroom.price_type == 1)" :href="`/user/classrooms/${classroom.id}`" class="btn btn-indigo radius-30 px-4">Masuk</Link>
                                                <Link v-else-if="classroom.user_access.length == 0 && classroom.price_type == 2" :href="`/user/transactions/classroom/${classroom.id}/buy`" class="btn btn-rose radius-30 px-4 shadow-sm">Beli</Link>
                                                <Link v-if="checkMemberCategories(classroom.member_categories) == false" :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-emerald radius-30 px-3 shadow-sm mt-1">Upgrade</Link>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-indigo radius-30 px-4">Ikuti Kelas</Link>
                                        </template>
                                    </div>
                                    <div v-else-if="classroom.status == 'inprogress' && classroom.release_date" class="text-end">
                                        <span class="text-xs text-amber-600 font-semibold block mb-1">Coming Soon</span>
                                        <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-light radius-30 px-3 border-slate-200">Detail</Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-5">
                <div class="mb-3">
                    <i class='bx bx-calendar-x text-slate-200' style="font-size: 80px;"></i>
                </div>
                <h5 class="text-slate-400 font-semibold">Live Class Belum Tersedia</h5>
                <p class="text-slate-400">Silakan kembali lagi nanti untuk jadwal kelas terbaru.</p>
                <Link href="/user/dashboard" class="btn btn-indigo radius-30 px-4 mt-2">Kembali ke Dashboard</Link>
            </div>

            <div class="d-flex justify-content-center mt-5 mb-4" v-if="classrooms.data.length">
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
    import debounce from 'lodash/debounce'

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
        setup(props) {
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                category_id: ref((new URL(document.location)).searchParams.get('category_id') || ''),
            });

            const handleSearch = () => {
                Inertia.get(
                    `/user/classrooms`,
                    {
                       search: form.search,
                       category_id: form.category_id,
                    },
                    {
                        preserveState: true,     
                        preserveScroll: true,     
                        replace: true
                    }
                )
            }

            const debouncedSearch = debounce(handleSearch, 1000)
            watch(() => form.search, () => debouncedSearch())

            const checkMemberCategories = (categories) => {
                if (categories.length > 0) {
                    const categoryIds = categories.map(category => category.id);
                    return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
                } else {
                    return true;
                }
            };

            return {
                form,
                checkMemberCategories,
                handleSearch
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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
    }
</script>

<style scoped>
    .rounded-sm {
        border-radius: 4px;
    }
    .background-blur-sm {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .card-hover-modern {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    .btn-indigo { background-color: #6366f1; color: white; }
    .btn-indigo:hover { background-color: #4f46e5; color: white; transform: translateY(-1px); }
    .btn-rose { background-color: #f43f5e; color: white; }
    .btn-rose:hover { background-color: #e11d48; color: white; transform: translateY(-1px); }
    .btn-emerald { background-color: #10b981; color: white; }
    .btn-emerald:hover { background-color: #059669; color: white; transform: translateY(-1px); }

    .pulse-emerald {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>