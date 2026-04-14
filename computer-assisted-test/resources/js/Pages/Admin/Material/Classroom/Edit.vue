<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Edit Live Class</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Live Class</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Live Class</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/classrooms" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kategori Peminatan</label>
                            <select v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                    {{ category.name }}</option>
                            </select>
                            <div v-if="errors.category_id" class="invalid-feedback">
                                {{ errors.category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Guru</label>
                            <select v-model="form.user_id" :class="{ 'is-invalid': errors.user_id }" class="form-select" :disabled="$page.props.auth.user.level == 3">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(user, index) in users" :key="index" :value="user.id">
                                    {{ user.name }}</option>
                            </select>
                            <div v-if="errors.user_id" class="invalid-feedback">
                                {{ errors.user_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Member (Khusus Untuk Membership Bulanan, Kosongkan Jika Tidak Ada)</label>
                            <multiselect
                                v-model="form.member_category_ids"
                                :options="memberCategories"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="name"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" placeholder="Nama Kelas">
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" placeholder="Judul">
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Materi</label>
                            <input type="text" class="form-control" v-model="form.material" :class="{ 'is-invalid': errors.material }" placeholder="Materi">
                            <div v-if="errors.material" class="invalid-feedback">
                                {{ errors.material }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Waktu Pelaksanaan</label>
                            <input type="datetime-local" class="form-control" v-model="form.start_time" :class="{ 'is-invalid': errors.start_time }" placeholder="Waktu Pelaksanaan">
                            <div v-if="errors.start_time" class="invalid-feedback">
                                {{ errors.start_time }}
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Durasi (Menit)</label>
                            <input type="number" class="form-control" v-model="form.duration" :class="{ 'is-invalid': errors.duration }" placeholder="Durasi" min="1">
                            <div v-if="errors.duration" class="invalid-feedback">
                                {{ errors.duration }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig" lass="form-control" :class="{ 'is-invalid': errors.title }"></ckeditor>
                            <div v-if="errors.description">
                                {{ errors.description }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Link Kelas</label>
                            <input type="text" class="form-control" v-model="form.link" :class="{ 'is-invalid': errors.link }" placeholder="Link Kelas">
                            <div v-if="errors.link" class="invalid-feedback">
                                {{ errors.link }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tipe Harga</label>
                            <select v-model="form.price_type" :class="{ 'is-invalid': errors.price_type }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Gratis</option>
                                <option value="2">Berbayar</option>
                            </select>
                            <div v-if="errors.price_type" class="invalid-feedback">
                                {{ errors.price_type }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Sebelum Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_before_discount" :class="{ 'is-invalid': errors.price_before_discount }" placeholder="Harga Sebelum Diskon" min="0">
                            <div v-if="errors.price_before_discount" class="invalid-feedback">
                                {{ errors.price_before_discount }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Sesudah Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_after_discount" :class="{ 'is-invalid': errors.price_after_discount }" placeholder="Harga Sesudah Diskon" min="0">
                            <div v-if="errors.price_after_discount" class="invalid-feedback">
                                {{ errors.price_after_discount }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select v-model="form.status" :class="{ 'is-invalid': errors.status }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="active">Aktive</option>
                                <option value="inactive">Inactive</option>
                                <option value="inprogress">Inprogress</option>
                            </select>
                            <div v-if="errors.status" class="invalid-feedback">
                                {{ errors.status }}
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                        </div>
                    </form>
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

    //import reactive
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    import Multiselect from '@suadelabs/vue3-multiselect';

    import { 
        ClassicEditor,
        Essentials, 
        Alignment,
        Bold, 
        Italic, 
        Font, 
        Paragraph, 
        BlockQuote, 
        List, ListProperties,
        Heading ,
        Strikethrough, Subscript, Superscript, Underline,
        AutoLink,
        TodoList,
        Indent,
        Image, ImageCaption, ImageStyle, ImageToolbar, LinkImage, ImageInsert, SimpleUploadAdapter, ImageResizeEditing, ImageResizeHandles, ImageResize,
        CodeBlock,
        Table, TableToolbar, TableCellProperties, TableProperties,
    } from 'ckeditor5';

    import MathType from '@wiris/mathtype-ckeditor5/dist/index.js';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },

        //props
        props: {
            errors: Object,
            categories: Object,
            classroom: Object,
            memberCategories: Object,
            memberCategorySelected: Object,
            users: Object,
        },
        data() {
            return {
                editor: ClassicEditor, 
                editorData: '<p>Initial content</p>',
                editorConfig: {
                    plugins: [
                        Essentials, 
                        Alignment,
                        Bold, 
                        Italic, 
                        Font, 
                        Paragraph, 
                        BlockQuote, 
                        List, ListProperties,
                        Heading ,
                        Strikethrough, Subscript, Superscript, Underline,
                        AutoLink,
                        TodoList,
                        Indent,
                        Image, ImageCaption, ImageStyle, ImageToolbar, LinkImage, ImageInsert, SimpleUploadAdapter, ImageResizeEditing, ImageResizeHandles, ImageResize,
                        CodeBlock,
                        Table, TableToolbar, TableCellProperties, TableProperties,
                        MathType
                    ],
                    toolbar: {
                        items: [ 
                            'undo', 'redo','|',
                            'heading','|',
                            'alignment', 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor','|',
                            'link', 'insertTable', 'insertImage', 'blockQuote', 'codeBlock','|',
                            'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent', '|',
                            'bold', 'italic', 'strikethrough', 'subscript', 'superscript','|',
                            'MathType', 'ChemType'
                        ],
                        shouldNotGroupWhenFull: false
                    },
                    list: {
                        properties: {
                            styles: true,
                            startIndex: true,
                            reversed: true
                        }
                    },
                    image: {
                        insert: {
                            integrations: [ 'upload', 'url' ],
                            type: 'auto'
                        },
                        toolbar: [
                            'imageStyle:inline','imageStyle:alignLeft','imageStyle:alignRight','|',
                            'imageStyle:alignBlockLeft','imageStyle:block','imageStyle:alignBlockRight','|',
                            'toggleImageCaption','imageTextAlternative','|',
                            'linkImage', 'resizeImage',
                        ],
                        resizeOptions: [
                            {name: 'resizeImage:original',value: null,icon: 'original'},
                            {name: 'resizeImage:custom',value: 'custom',icon: 'custom'},
                            {name: 'resizeImage:25',value: '25',icon: 'medium'},
                            {name: 'resizeImage:50',value: '50',icon: 'medium'},
                            {name: 'resizeImage:75',value: '75',icon: 'large'},
                            {name: 'resizeImage:100',value: '100',icon: 'large'}
                        ],
                        shouldInsertWidthHeightAttributes: false,
                    },
                    simpleUpload: {
                        uploadUrl: '/upload',
                        withCredentials: true,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Authorization': 'Bearer <JSON Web Token>'
                        },
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells',
                            'tableProperties', 'tableCellProperties'
                        ],
                        tableProperties: {},
                        tableCellProperties: {}
                    },
                    alignment: {
                        options: [ 'left', 'right', 'center', 'justify']
                    },
                    fontSize: {
                        options: [11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72
                    ],
                    supportAllValues: true
                    },
                    language: 'en'
                }
            }
        },
        // initialization composition API
        setup(props) {
            const form = reactive({
                category_id: props.classroom.category_id,
                user_id: props.classroom.user_id ?? '',
                name: props.classroom.name,
                title: props.classroom.title,
                material: props.classroom.material,
                start_time: props.classroom.start_time,
                duration: props.classroom.duration,
                description: props.classroom.description,
                link: props.classroom.link,
                price_type: props.classroom.price_type,
                price_before_discount: props.classroom.price_before_discount,
                price_after_discount: props.classroom.price_after_discount,
                status: props.classroom.status,
                member_category_ids: props.memberCategorySelected,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.put(`/admin/classrooms/${props.classroom.id}`, {
                    category_id: form.category_id,
                    user_id: form.user_id,
                    name: form.name,
                    title: form.title,
                    material: form.material,
                    start_time: form.start_time,
                    duration: form.duration,
                    description: form.description,
                    link: form.link,
                    price_type: form.price_type,
                    price_before_discount: form.price_before_discount,
                    price_after_discount: form.price_after_discount,
                    status: form.status,
                    member_category_ids: form.member_category_ids
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Live ClassBerhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        }
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>

<style>
/* Add your styles here */
.ck-editor__editable_inline {
    min-height: 350px;
}

.ck.ck-dropdown__panel {
	max-height: 240px; /* or anything else, more likely ~300px or so */
	overflow-y: auto;
}
</style>