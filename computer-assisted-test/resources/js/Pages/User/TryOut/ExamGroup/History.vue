<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Riwayat Tryout</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Riwayat Tryout</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <form>
                        <div class="form-group mb-3 position-relative">
                            <input
                                type="text"
                                v-model="form.search"
                                class="form-control form-control-sm ps-5 radius-20"
                                placeholder="Cari Judul Tryout...."
                                size="40"
                                maxlength="100"
                                id="search"
                            >
                            <span class="position-absolute top-50 product-show translate-middle-y">
                                <i class="bx bx-search"></i>
                            </span>
                            <Link href="/user/exam-groups/histories" class="btn btn-danger btn-sm position-absolute top-50 end-0 translate-middle-y">
                                <i class="bx bx-refresh"></i>reset
                            </Link>
                        </div>
                    </form>
                        
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size: 10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Peminatan</th> -->
                                    <!-- <th>Kategori</th> -->
                                    <th>Judul Tryout</th>
                                    <th>Tanggal Pengerjaan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!histories.data.length">
                                    <td colspan="8" align="center">Tidak ada riwayat Tryout</td>
                                </tr>
                                <tr v-for="(history, index) in histories.data" :key="index">
                                    <td>{{ ++index + (histories.current_page - 1) * histories.per_page }}</td>
                                    <!-- <td><span class="badge bg-primary">{{ history.exam_group.category.name }}</span></td> -->
                                    <!-- <td><span class="badge bg-primary">{{ history.exam_group.lesson_category.name }}</span></td> -->
                                    <td>{{ history.exam_group.title }}</td>
                                    <td>{{ formatDate(history.created_at) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/exam-groups/histories/${history.id}`" class="ms-2"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="histories.links" align="end" />

                </div>
            </div>
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

    import { reactive, watch } from 'vue';

    import debounce from 'lodash/debounce'

    import { Inertia } from '@inertiajs/inertia';

    //import ref from vue
    import {
        ref
    } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

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
            histories: Object
        },
        setup() {
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
            });

            const handleSearch = () => {
                Inertia.get(
                    `/user/exam-groups/histories`,
                    {
                       search: form.search,
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

            return {
                form,
                handleSearch
            }
        }
    }
</script>
