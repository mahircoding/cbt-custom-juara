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
                            <li class="breadcrumb-item active" aria-current="page">Detail Ruang Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link :href="`/admin/classrooms/${classroom.id}/edit`" class="btn btn-danger btn-sm mt-2 mx-2 mt-lg-0">Edit Ruang Kelas</Link>
                            <Link href="/admin/classrooms" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th width="350px">Kategori</th>
                                <td width="10px">:</td>
                                <td>{{ classroom.category.name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Guru</th>
                                <td>:</td>
                                <td>{{ classroom.user ? classroom.user.name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Member</th>
                                <td>:</td>
                                <td>
                                    <div v-if="classroom.member_categories.length">
                                        <div v-for="(member_category, index) in classroom.member_categories" :key="index" style="display: inline;">
                                            <span class="badge bg-success ms-1">{{ member_category.name }}</span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <span class="badge bg-success ms-1">Seluruh kategori member</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Ruang Kelas</th>
                                <td>:</td>
                                <td>{{ classroom.name }}</td>
                            </tr>
                            <tr>
                                <th>Judul</th>
                                <td>:</td>
                                <td>{{ classroom.title }}</td>
                            </tr>
                            <tr>
                                <th>Materi</th>
                                <td>:</td>
                                <td>{{ classroom.material }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Pelaksanaan</th>
                                <td>:</td>
                                <td>
                                    {{ formatDateWithTimeHourMinute(classroom.start_time) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Durasi</th>
                                <td>:</td>
                                <td>{{ classroom.duration }} Menit</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>:</td>
                                <td>
                                    <div class="ck-content" v-html="classroom.description"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Link</th>
                                <td>:</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" :value="classroom.link" disabled style="background-color: #fff;">
                                        <button class="btn btn-primary btn-sm" type="button" @click="copyText(classroom.link)">Copy</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Tipe Harga Ruang Kelas</th>
                                <td>:</td>
                                <td>
                                    <span v-if="classroom.price_type == 1" class="badge bg-primary">Gratis</span>
                                    <span v-else class="badge bg-danger">Berbayar</span>
                                </td>
                            </tr>
                            <tr v-if="classroom.price_type == 2">
                                <th>Harga Ruang Kelas Sebelum Diskon</th>
                                <td>:</td>
                                <td>
                                    {{ formatPrice(classroom.price_before_discount) }}
                                </td>
                            </tr>
                            <tr v-if="classroom.price_type == 2">
                                <th>Harga Ruang Kelas Sesudah Diskon</th>
                                <td>:</td>
                                <td>
                                    {{ formatPrice(classroom.price_after_discount) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Status Ruang Kelas</th>
                                <td>:</td>
                                <td>
                                    <span v-if="classroom.status == 'active'" class="badge bg-primary">Active</span>
                                    <span v-if="classroom.status == 'inactive'" class="badge bg-success">Inactive</span>
                                    <span v-if="classroom.status == 'inprogress'" class="badge bg-danger">Inprogress</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            classroom: Object,
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            copyText(text) {
                const el = document.createElement('textarea');
                el.value = text; 
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el); 
            }
        }
    }
</script>
