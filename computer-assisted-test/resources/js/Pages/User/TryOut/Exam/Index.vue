<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Soal</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Latihan Soal</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Soal</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/categories/${category.id}/lesson-categories/${lessonCategoryId}/lessons`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>

            <div class="card">
                <div class="card-body bg-light p-3 rounded-3">
                    <div class="input-group flex-md-nowrap flex-wrap">
                        <input type="text" v-model="form.search" class="form-control form-control-sm sm-2" placeholder="Judul Latihan ....">

                        <select v-model="form.sub_category_id" class="form-control form-control-sm sm-2" @change="handleSearch">
                            <option value="">[ Kategori ]</option>
                            <option v-for="(category, index) in subCategories" :key="index" :value="category.id">
                                {{ category.name }}</option>
                        </select>

                        <Link :href="`/user/categories/${category.id}/lesson-categories/${lessonCategoryId}/lessons/${lessonId}/exams`" class="btn btn-danger btn-sm"><i class="bx bx-refresh"></i></Link>
                    </div>
                </div>
            </div>

            <div v-if="exams.data.length > 0" class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3" v-for="(exam, index) in exams.data" :key="index">
                    <div class="practice-card-modern">
                        <div
                            class="practice-card-modern__cover"
                            :style="{
                                backgroundImage: exam.lesson && exam.lesson.thumbnail
                                    ? `url('/storage/upload_files/lessons/${exam.lesson.thumbnail}')`
                                    : 'linear-gradient(135deg, #edf3ff 0%, #dbe8ff 100%)'
                            }"
                        ></div>

                        <div class="practice-card-modern__body">
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge practice-badge-category">{{ exam.category && exam.category.name ? exam.category.name : 'LATIHAN' }}</span>
                                <span class="badge practice-badge-type">{{ exam.price_type == 2 ? 'Premium' : 'Gratis' }}</span>
                            </div>

                            <h5 class="practice-card-modern__title">{{ exam.title }}</h5>
                            <p class="practice-card-modern__desc">{{ truncateText(stripHtml(exam.description || 'Soal-soal sudah disesuaikan dengan kisi-kisi terbaru.'), 92) }}</p>

                            <div v-if="showPrice(exam)" class="practice-card-modern__price">
                                <span class="practice-price-old" v-if="Number(exam.price_before_discount || 0) > Number(exam.price_after_discount || 0)">
                                    {{ formatRupiah(exam.price_before_discount) }}
                                </span>
                                <span class="practice-price-new">{{ formatRupiah(exam.price_after_discount) }}</span>
                            </div>
                            <div v-else class="practice-card-modern__price">
                                <span class="practice-price-new">Gratis</span>
                            </div>

                            <div class="practice-card-modern__meta">
                                <i class="bx bx-purchase-tag-alt"></i>
                                {{ ownershipLabel(exam) }}
                            </div>

                            <div v-if="exam.exam_status == 'active'" class="practice-card-modern__actions">
                                <Link
                                    v-if="examAction(exam).canWork"
                                    :href="`/user/categories/${exam.category_id}/lesson-categories/${exam.lesson_category_id}/lessons/${exam.lesson_id}/exams/${exam.id}`"
                                    class="btn practice-btn-main"
                                >
                                    Kerjakan
                                </Link>
                                <Link
                                    v-if="examAction(exam).canBuy"
                                    :href="`/user/transactions/exam/${exam.id}/buy`"
                                    class="btn practice-btn-main"
                                >
                                    Beli
                                </Link>
                                <Link
                                    v-if="examAction(exam).canUpgrade"
                                    :href="`/user/vouchers?category_id=${exam.category_id}`"
                                    class="btn practice-btn-secondary"
                                >
                                    <span v-if="exam.member_categories && exam.member_categories.length == 1">Upgrade Ke {{ exam.member_categories[0].name }}</span>
                                    <span v-else>Upgrade Member</span>
                                </Link>
                            </div>
                            <div v-else class="practice-card-modern__inactive">
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
                                <h6 class="card-title text-center">Latihan Soal Belum Tersedia</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3" v-if="exams.data.length">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-center" style="min-height: 0vh;">
                        <Pagination :links="exams.links"/>
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

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import reactive
    import { reactive, watch } from 'vue';

    //import ref from vue
    import { ref } from 'vue';

    import debounce from 'lodash/debounce'

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
            exams: Object,
            category: Object,
            lessonCategoryId: Object,
            lessonId: Object,
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
                    `/user/categories/${props.category.id}/lesson-categories/${props.lessonCategoryId}/lessons/${props.lessonId}/exams`,
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

            const debouncedSearch = debounce(handleSearch, 1000)

            watch(() => form.search, () => {
                debouncedSearch()
            })

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
                handleSearch,
                checkMemberCategories
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
                    && this.resolvedEnablePracticeQuestionSales == 1
                    && (this.resolvedPracticeQuestionSalesType == 1 || this.resolvedPracticeQuestionSalesType == 3)
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

                if (this.$page.props.auth.user.member_type != 2 || this.resolvedEnablePracticeQuestionSales != 1) {
                    return { canWork: true, canBuy: false, canUpgrade: false };
                }

                const hasUserAccess = this.hasUserAccess(exam);
                const hasMemberAccess = this.hasMemberAccess(exam);

                if (this.resolvedPracticeQuestionSalesType == 1) {
                    return {
                        canWork: hasUserAccess || exam.price_type == 1,
                        canBuy: !hasUserAccess && exam.price_type == 2,
                        canUpgrade: false,
                    };
                }

                if (this.resolvedPracticeQuestionSalesType == 2) {
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
            resolvedPracticeQuestionSalesType() {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.practice_question_sales_type
                    : this.category.practice_question_sales_type;
            },
            resolvedEnablePracticeQuestionSales() {
                return this.$page.props.setting.transaction_sale_type == 1
                    ? this.$page.props.setting.enable_practice_question_sales
                    : this.category.enable_practice_question_sales;
            }
        }
    }
</script>

<style>
    .practice-card-modern {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(13, 110, 253, 0.3);
        background: linear-gradient(180deg, #0b57b4 0%, #0b4ca0 100%);
        color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .practice-card-modern__cover {
        height: 210px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #eaf1ff;
    }

    .practice-card-modern__body {
        padding: 0.95rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .practice-badge-category {
        background: #1e88ff;
        color: #fff;
        font-weight: 700;
    }

    .practice-badge-type {
        background: rgba(255, 255, 255, 0.96);
        color: var(--bs-primary);
        font-weight: 700;
    }

    .practice-card-modern__title {
        color: #fff;
        line-height: 1.06;
        margin-bottom: 0.4rem;
        font-weight: 800;
    }

    .practice-card-modern__desc {
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 0.45rem;
        min-height: 56px;
    }

    .practice-card-modern__price {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }

    .practice-price-old {
        color: rgba(255, 255, 255, 0.76);
        text-decoration: line-through;
        font-size: 1.3rem;
    }

    .practice-price-new {
        color: #fff;
        font-size: 1.3rem;
        font-weight: 800;
    }

    .practice-card-modern__meta {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.02rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .practice-card-modern__meta i {
        color: #cae4ff;
        font-size: 1.2rem;
    }

    .practice-card-modern__actions {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .practice-btn-main {
        border: 0;
        border-radius: 999px;
        background: #ffffff;
        color: var(--bs-primary);
        font-weight: 700;
        width: 100%;
    }

    .practice-btn-main:hover {
        background: #f1f6ff;
        color: var(--bs-primary);
    }

    .practice-btn-secondary {
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        font-weight: 600;
        width: 100%;
    }

    .practice-btn-secondary:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.2);
    }

    .practice-card-modern__inactive {
        margin-top: auto;
        text-align: center;
    }

    @media (max-width: 740px) {
        .practice-card-modern__cover {
            height: 190px;
        }

        .practice-card-modern__title {
            font-size: 1.5rem;
        }

        .practice-price-new {
            font-size: 1.8rem;
        }
    }
</style>
