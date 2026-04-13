<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Ruang Kelas</title>
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

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Filter Data</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body"> <!-- Change 'text-center' to 'text-end' -->
                    <form>
                        <div class="row">
                            <div class="col-lg-5 col-md-6 mb-1">
                                <label for="search">Nama / Judul Ruang Kelas</label>
                                <input type="text" v-model="form.search" class="form-control form-control-sm" id="search" placeholder="Nama / Judul Ruang Kelas....">
                            </div>

                            <div class="col-lg-3 col-md-6 mb-1">
                                <label for="category">Kategori Peminatan</label>
                                <select @change="lessonCategoryData" v-model="form.category_id" class="form-select form-select-sm">
                                    <option value="">[ Pilih]</option>
                                    <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                        {{ category.name }}</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="category">Waktu</label>
                                <select v-model="form.order" class="form-select form-select-sm">
                                    <option value="">[ Pilih]</option>
                                    <option value="DESC">Terbaru</option>
                                    <option value="ASC">Terlama</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="end_date">Action</label><br>
                                    <div class="input-group">
                                        <button @click.prevent="handleSearch" class="btn btn-primary btn-sm w-50">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                    <Link href="/admin/classrooms" class="btn btn-danger btn-sm w-50">
                                        <i class="bx bx-refresh"></i> Reset
                                    </Link>
                                </div>        
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Ruang Kelas</h6>
                        </div>
                        <div class="ms-auto">
                            <Link href="/admin/classrooms/create" class="btn btn-primary btn-sm me-2">
                                <i class="bx bxs-plus-square"></i>Tambah Ruang Kelas
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-0" style="font-size:10pt;">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Nama Kelas</th>
                                <th>Judul</th>
                                <th>Materi</th>
                                <th>Waktu Pelaksanaan</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(classroom, index) in classrooms.data" :key="index">
                                <td>
                                    {{ ++index + (classrooms.current_page - 1) * classrooms.per_page }}
                                </td>
                                <td><span class="badge bg-primary">{{ classroom.category.name }}</span></td>
                                <td>{{ classroom.name }}</td>
                                <td>{{ classroom.title }}</td>
                                <td>{{ classroom.material }}</td>
                                <td>{{ formatDateWithTimeHourMinute(classroom.start_time) }}</td>
                                <td align="right">
                                    <div class="btn-group">
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': classroom.status === 'inprogress', 'btn-danger': classroom.status === 'inactive', 'btn-primary': classroom.status === 'active'}">{{ capitalizeWords(classroom.status) }}</button>
                                        <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-warning': classroom.status === 'inprogress', 'btn-danger': classroom.status === 'inactive', 'btn-primary': classroom.status === 'active', 'split-bg-warning': classroom.status === 'inprogress', 'split-bg-danger': classroom.status === 'inactive', 'split-bg-primary': classroom.status === 'active'}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                        <ul class="dropdown-menu">
                                            <li v-if="classroom.status !== 'active'"><Link class="dropdown-item" :href="`/admin/classrooms/${classroom.id}/change-classroom-status?status=active`">Active</Link></li>
                                            <li v-if="classroom.status !== 'inactive'"><Link class="dropdown-item" :href="`/admin/classrooms/${classroom.id}/change-classroom-status?status=inactive`">Inactive</Link></li>
                                            <li v-if="classroom.status !== 'inprogress'"><Link class="dropdown-item" :href="`/admin/classrooms/${classroom.id}/change-classroom-status?status=inprogress`">In Progress</Link></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
                                        <ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
                                            <li><Link class="dropdown-item" :href="`/admin/classrooms/${classroom.id}`">Detail</Link></li>
                                            <li><Link class="dropdown-item" :href="`/admin/classrooms/${classroom.id}/edit`">Edit</Link></li>
                                            <li><a class="dropdown-item" href="#" @click.prevent="destroy(classroom.id)">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!classrooms.data.length">
                                <td align="center" colspan="9">Data Tidak Tersedia</td>
                            </tr>
                        </tbody>
                    </table>

                    <Pagination :links="classrooms.links" align="end" />

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import reactive
    import { reactive } from 'vue';

    //import ref from vue
    import {
        ref
    } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            classrooms: Object,
            categories: Object,
        },

        // initialization composition API
        setup() {
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                category_id: ref((new URL(document.location)).searchParams.get('category_id') || ''),
                order: ref((new URL(document.location)).searchParams.get('order') || ''),
            });


            // define state search
            const handleSearch = () => {
                Inertia.get('/admin/classrooms', {
                    search: form.search,
                    category_id: form.category_id,
                    order: form.order,
                })
            }

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/classrooms/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Ruang Kelas Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                form,
                handleSearch,
                destroy
            }
        }
    }
</script>
