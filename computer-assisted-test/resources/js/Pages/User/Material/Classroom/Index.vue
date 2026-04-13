<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Ruang Kelas</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ruang Kelas</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Ruang Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>
            <div class="card">
                <div class="card-body bg-light p-3 rounded-3">
                    <div class="input-group flex-md-nowrap flex-wrap">
                        <input type="text" v-model="form.search" class="form-control form-control-sm sm-2" placeholder="Cari Berdasarkan Nama / Judul Ruang Kelas ....">

                        <select v-model="form.category_id" class="form-control form-control-sm sm-2" @change="handleSearch">
                            <option value="">[ Kategori ]</option>
                            <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                {{ category.name }}</option>
                        </select>

                        <Link href="/user/classrooms" class="btn btn-danger btn-sm"><i class="bx bx-refresh"></i></Link>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert alert-success border-0">
                        <div v-html="$page.props.session.success"></div>
                    </div>
                </div>
            </div>

            <div class="card mb-0" v-if="classrooms.data.length > 0">
                <div class="card-body p-3">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-action p-2 mb-2 border" v-for="(classroom, index) in classrooms.data" :key="index">
                            <div class="d-flex flex-column flex-md-row">
                                <!-- Judul, Deskripsi, Kategori, dan Sub Kategori -->
                                <div class="flex-grow-1">
                                    <h6><span class="badge bg-danger mt-2">{{ classroom.category.name }}</span></h6>
                                    <h6 class="my-2">Judul : {{ classroom.title }}</h6>
                                    <div class="text-muted">
                                        <i class="bx bx-building"></i> Nama Kelas : {{ classroom.name }}<br>
                                        <i class="bx bx-file"></i> Materi : {{ classroom.material }}<br>
                                        <i class="bx bx-time"></i> Mulai: {{ formatDateWithTimeHourMinute(classroom.start_time) }} {{ timezoneFormat($page.props.setting.timezone) }}<br>
                                        <i class="bx bx-timer"></i> Durasi: {{ classroom.duration }} menit
                                    </div>

                                    <!-- Kategori Member -->
                                    <p class="mb-1">
                                        <template v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                            <template v-if="classroom.member_categories && classroom.member_categories.length">
                                                <span v-for="(memberCategory, subIndex) in classroom.member_categories" :key="subIndex" class="badge bg-success me-1">
                                                    {{ memberCategory.name }}
                                                </span>
                                            </template>
                                            <span v-else class="badge bg-success">
                                                Seluruh Member & Non Member
                                            </span>
                                        </template>
                                    </p>
                                </div>
                                
                                <!-- Action Links -->
                                <div class="d-flex flex-column action-button-classrooms text-center text-md-end">
                                    <!-- Bagian Harga -->
                                    <div v-if="$page.props.auth.user.member_type == 2">
                                        <tempate v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.user_access.length > 0">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                        <tempate v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length > 0 && checkMemberCategories(classroom.member_categories) == true">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                        <tempate v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true)">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                    </div>
                                    <div v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 || resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3) && $page.props.auth.user.member_type == 2">
                                        <div v-if="classroom.price_type == 2">
                                            <div v-if="classroom.price_before_discount == classroom.price_after_discount">
                                                <h6 class="card-price">Rp.{{ formatPrice(classroom.price_after_discount) }}</h6>
                                            </div>
                                            <div v-else>
                                                <h6 class="text-dark">
                                                    <sup>
                                                        <s>Rp.{{ formatPrice(classroom.price_before_discount) }}</s>
                                                        <span class="badge bg-danger mx-1">
                                                            {{ formatPrice((classroom.price_before_discount - classroom.price_after_discount) / classroom.price_before_discount * 100)}}%
                                                        </span>
                                                    </sup>
                                                    <br>
                                                    Rp.{{ formatPrice(classroom.price_after_discount) }}
                                                </h6>
                                            </div>     
                                        </div>                               
                                    </div>
                                    <div v-if="(resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1 && classroom.price_type == 1) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2 && classroom.member_categories.length == 0) || (resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3 && (classroom.member_categories.length == 0 || classroom.price_type == 1)) ">
                                        <span class="badge bg-warning text-dark" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:1;">Gratis</span>
                                    </div> 

                                    <!-- Bagian Tombol -->
                                    <div>
                                        <div v-if="classroom.status == 'active'">
                                            <template v-if="$page.props.auth.user.member_type == 2 && resolvedEnableClassroomSales(classroom.category.enable_classroom_sales) == 1">
                                                <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 1">
                                                    <template v-if="classroom.user_access.length > 0 || classroom.price_type == 1">
                                                        <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-sm btn-primary me-1">Ikuti Kelas</Link>
                                                    </template>
                                                    <template v-else>
                                                        <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/classroom/${classroom.id}/buy`">Beli</Link>
                                                    </template>
                                                </template>

                                                <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 2">
                                                    <template v-if="checkMemberCategories(classroom.member_categories) == true">
                                                        <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-sm btn-primary me-1">Ikuti Kelas</Link>
                                                    </template>
                                                    <template v-else>
                                                        <Link :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-sm btn-success">
                                                            <span v-if="classroom.member_categories.length == 1">Upgrade Ke {{ classroom.member_categories[0] ? classroom.member_categories[0].name : '' }}</span>
                                                            <span v-else>Upgrade Member</span>
                                                        </Link>
                                                    </template>
                                                </template>

                                                <template v-if="resolvedClassroomSalesType(classroom.category.classroom_sales_type) == 3">
                                                    <template v-if="classroom.user_access.length > 0 && checkMemberCategories(classroom.member_categories) == true">
                                                        <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-sm btn-primary me-1">Ikuti Kelas</Link>
                                                    </template>
                                                    <template v-if="(classroom.user_access.length == 0  || checkMemberCategories(classroom.member_categories) == false) && (classroom.user_access.length > 0 || checkMemberCategories(classroom.member_categories) == true || classroom.price_type == 1)">
                                                        <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-sm btn-primary me-1">Ikuti Kelas</Link>
                                                    </template>
                                                    <template v-if="classroom.user_access.length == 0 && classroom.price_type == 2">
                                                        <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/classroom/${classroom.id}/buy`">Beli</Link>
                                                    </template>
                                                    <template v-if="checkMemberCategories(classroom.member_categories) == false">
                                                        <Link :href="`/user/vouchers?category_id=${classroom.category_id}`" class="btn btn-sm btn-success">
                                                            <span v-if="classroom.member_categories.length == 1">Upgrade Ke {{ classroom.member_categories[0] ? classroom.member_categories[0].name : '' }}</span>
                                                            <span v-else>Upgrade Member</span>
                                                        </Link>
                                                    </template>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <Link :href="`/user/classrooms/${classroom.id}`" class="btn btn-sm btn-primary me-1">Ikuti Kelas</Link>
                                            </template>
                                        </div>
                                        <template v-else>
                                            <span class="badge" :class="{ 'bg-danger': classroom.status == 'inactive', 'bg-warning': classroom.status === 'inprogress'}">
                                                {{ classroom.status === 'inactive' ? 'Inactive' : 'In Progress' }}
                                            </span>
                                            <template v-if="classroom.status == 'inprogress' && classroom.release_date"> 
                                                <p><span class="badge bg-light text-dark mt-2">Rilis {{ formatDateWithTimeHourMinute(classroom.release_date) }} {{ timezoneFormat($page.props.setting.timezone) }}</span></p>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!classrooms.data.length">
                <div class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                    <div class="col">
                        <div class="card border-bottom border-3 border-0">
                            <div class="card-body">
                                <h6 class="card-title text-center">Ruang Kelas Belum Tersedia</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3" v-if="classrooms.data.length">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-center" style="min-height: 0vh;">
                        <Pagination :links="classrooms.links"/>
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

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

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

<style>
   .action-button-classrooms {
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* Default alignment for larger screens */
    }

    @media (max-width: 740px) {
        .list-group-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .list-group-item .flex-grow-1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .action-button-classrooms {
            align-items: center;
            text-align: center;
            margin: 0 auto; /* Ensure the container is centered */
        }

        .action-button-classrooms h6,
        .action-button-classrooms .btn,
        .action-button-classrooms .btn-danger {
            margin: 5px 0; /* Ensure spacing around elements */
        }
    }
</style>