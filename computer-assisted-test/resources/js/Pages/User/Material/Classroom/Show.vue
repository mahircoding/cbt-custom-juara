<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Ruang Kelas</title>
    </Head>
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ruang Kelas</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Ruang Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Detail Card -->
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <h5 class="mb-0">Detail Kelas</h5>
                        <button onclick="history.back()" class="btn btn-primary btn-sm">Kembali</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <strong>Kategori Peminatan:</strong>
                            <div>{{ classroom.category.name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Guru:</strong>
                            <div>{{ classroom.user ? classroom.user.name : '' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Judul Kelas:</strong>
                            <div>{{ classroom.title }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Nama Kelas:</strong>
                            <div>{{ classroom.name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Materi:</strong>
                            <div>{{ classroom.material }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu Mulai:</strong>
                            <div>{{ dateFormat(classroom.start_time) }} {{ timezoneFormat($page.props.setting.timezone) }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Durasi:</strong>
                            <div>{{ classroom.duration }} menit</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <div>
                                <span
                                    class="badge"
                                    :class="{
                                    'bg-danger': classroom.status === 'inactive',
                                    'bg-warning': classroom.status === 'inprogress',
                                    'bg-success': classroom.status === 'active'
                                    }"
                                    >
                                {{
                                classroom.status === 'inactive'
                                ? 'Inactive'
                                : classroom.status === 'inprogress'
                                ? 'In Progress'
                                : classroom.status === 'active'
                                ? 'Active'
                                : 'Unknown'
                                }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <strong>Link Kelas:</strong>
                            <div><a :href="classroom.link" target="_blank">{{ classroom.link }}</a></div>
                        </div>
                        <div class="col-md-12">
                            <strong>Deskripsi:</strong>
                            <div class="ck-content" v-html="classroom.description"></div>
                        </div>
                        <div class="col-md-12">
                            <strong>Dibuat Pada:</strong>
                            <div>{{ dateFormat(classroom.created_at) }} {{ timezoneFormat($page.props.setting.timezone) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import LayoutUser from '../../../../Layouts/Layout.vue';
    import { Link, Head } from '@inertiajs/inertia-vue3';
    
    export default {
      layout: LayoutUser,
      components: {
        Link,
        Head,
      },
      props: {
        classroom: Object,
      },
    }
</script>