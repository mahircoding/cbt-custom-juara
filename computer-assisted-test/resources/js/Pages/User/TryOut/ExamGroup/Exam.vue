<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Tryout</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Soal Tryout</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>
            <div class="card">
                <div class="card-body bg-light p-3 rounded-3">
                    <div class="input-group flex-md-nowrap flex-wrap">
                        <input type="text" v-model="form.search" class="form-control form-control-sm sm-2" placeholder="Judul Tryout ....">

                        <select v-model="form.sub_category_id" class="form-control form-control-sm sm-2" @change="handleSearch">
                            <option value="">[ Kategori ]</option>
                            <option v-for="(category, index) in subCategories" :key="index" :value="category.id">
                                {{ category.name }}</option>
                        </select>

                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams`" class="btn btn-danger btn-sm"><i class="bx bx-refresh"></i></Link>
                    </div>
                </div>
            </div>

            <div v-if="examGroups.data.length > 0" class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3" v-for="(exam, index) in examGroups.data" :key="index">
                    <div class="exam-card-modern">
                        <div
                            class="exam-card-modern__cover"
                            :style="{
                                backgroundImage: exam.lesson_category && exam.lesson_category.thumbnail
                                    ? `url('/storage/upload_files/lesson_categories/${exam.lesson_category.thumbnail}')`
                                    : 'linear-gradient(135deg, #edf3ff 0%, #dbe8ff 100%)'
                            }"
                        ></div>

                        <div class="exam-card-modern__body">
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge exam-badge-category">{{ exam.category && exam.category.name ? exam.category.name : 'TRYOUT' }}</span>
                                <span class="badge exam-badge-type">{{ exam.price_type == 2 ? 'Premium' : 'Gratis' }}</span>
                            </div>

                            <h5 class="exam-card-modern__title">{{ exam.title }}</h5>
                            <p class="exam-card-modern__desc">{{ truncateText(stripHtml(exam.description || 'Soal-soal sudah disesuaikan dengan kisi-kisi terbaru.'), 92) }}</p>

                            <div v-if="showPrice(exam)" class="exam-card-modern__price">
                                <span
                                    class="exam-price-old"
                                    v-if="Number(exam.price_before_discount || 0) > Number(exam.price_after_discount || 0)"
                                >
                                    {{ formatRupiah(exam.price_before_discount) }}
                                </span>
                                <span class="exam-price-new">{{ formatRupiah(exam.price_after_discount) }}</span>
                            </div>
                            <div v-else class="exam-card-modern__price">
                                <span class="exam-price-new">Gratis</span>
                            </div>

                            <div class="exam-card-modern__meta">
                                <i class="bx bx-purchase-tag-alt"></i>
                                {{ ownershipLabel(exam) }}
                            </div>

                            <div v-if="exam.exam_status == 'active'" class="exam-card-modern__actions">
                                <Link
                                    v-if="examAction(exam).canWork"
                                    :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`"
                                    class="btn exam-btn-main"
                                >
                                    Kerjakan
                                </Link>
                                <Link
                                    v-if="examAction(exam).canBuy"
                                    :href="`/user/transactions/examGroup/${exam.id}/buy`"
                                    class="btn exam-btn-main"
                                >
                                    Beli Tryout
                                </Link>
                                <Link
                                    v-if="examAction(exam).canUpgrade"
                                    :href="`/user/vouchers?category_id=${exam.category_id}`"
                                    class="btn exam-btn-secondary"
                                >
                                    <span v-if="exam.member_categories && exam.member_categories.length == 1">Upgrade Ke {{ exam.member_categories[0].name }}</span>
                                    <span v-else>Upgrade Member</span>
                                </Link>
                            </div>
                            <div v-else class="exam-card-modern__inactive">
                                <span class="badge" :class="{ 'bg-danger': exam.exam_status == 'inactive', 'bg-warning text-dark': exam.exam_status === 'inprogress'}">
                                    {{ exam.exam_status === 'inactive' ? 'Inactive' : 'In Progress' }}
                                </span>
                                <div v-if="exam.exam_status == 'inprogress' && exam.release_date" class="mt-2">
                                    <span class="badge bg-light text-dark">Rilis {{ formatDateWithTimeHourMinute(exam.release_date) }} {{ timezoneFormat($page.props.setting.timezone) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                    <div class="col">
                        <div class="card border-bottom border-3 border-0">
                            <div class="card-body">
                                <h6 class="card-title text-center">Tryout Belum Tersedia</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3" v-if="examGroups.data.length">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-center" style="min-height: 0vh;">
                        <Pagination :links="examGroups.links"/>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import reactive
    import { reactive, watch } from 'vue';

    import debounce from 'lodash/debounce'

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            category: Object,
            examGroups: Object,
            lessonCategory: Object,
            userMemberCategories: Object,
            subCategories: Object,
            session: Object,
        },
        setup(props) {

            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                sub_category_id: ref((new URL(document.location)).searchParams.get('sub_category_id') || ''),
            });

            const handleSearch = () => {
                Inertia.get(
                    `/user/exam-groups/${props.lessonCategory.category_id}/lesson-categories/${props.lessonCategory.id}/exams`,
                    {
                        search: form.search,
                        sub_category_id: form.sub_category_id,
                    },
                    {
                        preserveState: true,
                        preserveScroll: true,
                        replace: true
                    }
                )
            }

            const debouncedSearch = debounce(handleSearch, 1000)

            watch(() => form.search, () => {
                debouncedSearch()
            })

            if (props.session && props.session.success) {
                Swal.fire({
                    title: "Sukses",
                    text: props.session.success,
                    icon: "success",
                })
            }

            if (props.session && props.session.error) {
                Swal.fire({
                    title: "Peringatan",
                    text: props.session.error,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Top Up Saldo',
                    cancelButtonText: 'Tidak'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        Inertia.get('/user/account-balances');
                    }
                })
            }

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
            formatRupiah(value) {
                return `Rp${Number(value || 0).toLocaleString('id-ID')}`;
            },
            stripHtml(value) {
                const raw = String(value || '');
                return raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
            },
            truncateText(value, max = 90) {
                if (!value) return '-';
                return value.length > max ? `${value.slice(0, max)}...` : value;
            },
            hasUserAccess(exam) {
                return !!(exam.user_access && exam.user_access.length);
            },
            hasMemberAccess(exam) {
                return this.checkMemberCategories(exam.member_categories || []);
            },
            showPrice(exam) {
                return this.$page.props.auth.user.member_type == 2
                    && this.resolvedEnableTryoutSales == 1
                    && (this.resolvedTryoutSalesType == 1 || this.resolvedTryoutSalesType == 3)
                    && exam.price_type == 2;
            },
            ownershipLabel(exam) {
                if (exam.price_type == 1) {
                    return 'Gratis';
                }
                return this.examAction(exam).canWork ? 'Sudah Dimiliki' : 'Belum Dimiliki';
            },
            examAction(exam) {
                if (exam.exam_status !== 'active') {
                    return { canWork: false, canBuy: false, canUpgrade: false };
                }

                if (this.$page.props.auth.user.member_type != 2 || this.resolvedEnableTryoutSales != 1) {
                    return { canWork: true, canBuy: false, canUpgrade: false };
                }

                const hasUserAccess = this.hasUserAccess(exam);
                const hasMemberAccess = this.hasMemberAccess(exam);

                if (this.resolvedTryoutSalesType == 1) {
                    return {
                        canWork: hasUserAccess || exam.price_type == 1,
                        canBuy: !hasUserAccess && exam.price_type == 2,
                        canUpgrade: false,
                    };
                }

                if (this.resolvedTryoutSalesType == 2) {
                    return {
                        canWork: hasMemberAccess,
                        canBuy: false,
                        canUpgrade: !hasMemberAccess,
                    };
                }

                return {
                    canWork: hasUserAccess || hasMemberAccess || exam.price_type == 1,
                    canBuy: !hasUserAccess && exam.price_type == 2,
                    canUpgrade: !hasMemberAccess,
                };
            },
        },
        computed: {
            resolvedTryoutSalesType() {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.tryout_sales_type
                    : this.category.tryout_sales_type;
            },
            resolvedEnableTryoutSales() {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.enable_tryout_sales
                    : this.category.enable_tryout_sales;
            }
        }
    }
</script>

<style>
    .exam-card-modern {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(13, 110, 253, 0.3);
        background: linear-gradient(180deg, #0b57b4 0%, #0b4ca0 100%);
        color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .exam-card-modern__cover {
        height: 210px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #eaf1ff;
    }

    .exam-card-modern__body {
        padding: 0.95rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .exam-badge-category {
        background: #1e88ff;
        color: #fff;
        font-weight: 700;
    }

    .exam-badge-type {
        background: rgba(255, 255, 255, 0.96);
        color: var(--bs-primary);
        font-weight: 700;
    }

    .exam-card-modern__title {
        color: #fff;
        line-height: 1.06;
        margin-bottom: 0.4rem;
        font-weight: 800;
    }

    .exam-card-modern__desc {
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 0.45rem;
        min-height: 56px;
    }

    .exam-card-modern__price {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }

    .exam-price-old {
        color: rgba(255, 255, 255, 0.76);
        text-decoration: line-through;
        font-size: 1.3rem;
    }

    .exam-price-new {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .exam-card-modern__meta {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.02rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .exam-card-modern__meta i {
        color: #cae4ff;
        font-size: 1.2rem;
    }

    .exam-card-modern__actions {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .exam-btn-main {
        border: 0;
        border-radius: 999px;
        background: #ffffff;
        color: var(--bs-primary);
        font-weight: 700;
        width: 100%;
    }

    .exam-btn-main:hover {
        background: #f1f6ff;
        color: var(--bs-primary);
    }

    .exam-btn-secondary {
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        font-weight: 600;
        width: 100%;
    }

    .exam-btn-secondary:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.2);
    }

    .exam-card-modern__inactive {
        margin-top: auto;
        text-align: center;
    }

    @media (max-width: 740px) {
        .exam-card-modern__cover {
            height: 190px;
        }

        .exam-card-modern__title {
            font-size: 1.5rem;
        }

        .exam-price-new {
            font-size: 1.8rem;
        }
    }
</style>
