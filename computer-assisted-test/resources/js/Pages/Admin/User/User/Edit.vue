<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Edit User</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <form @submit.prevent="submit">
                <input type="hidden" class="form-control" v-model="form.id" :class="{ 'is-invalid': errors.id }">
                <div class="card border-top border-0 border-3 border-primary">
                    <div class="card-header">
                        <div class="d-lg-flex align-items-center">
                            <h6 class="card-title mb-0">Informasi Akun</h6>
                            <div class="ms-auto">
                                <Link href="/admin/users" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                            <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                            <ul class="mt-3">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                            <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label class="form-label">Level</label>
                                <select v-model="form.level" :class="{ 'is-invalid': errors.level }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Admin</option>
                                    <option value="3">Guru</option>
                                    <option value="2">Peserta</option>
                                </select>
                                <div v-if="errors.level" class="invalid-feedback">
                                    {{ errors.level }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="form.level == 2">
                                <label class="form-label">Tipe Member</label>
                                <select v-model="form.member_type" :class="{ 'is-invalid': errors.member_type }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Member Gratis Tryout</option>
                                    <option value="2">Member Tryout Berbayar </option>
                                </select>
                                <div v-if="errors.member_type" class="invalid-feedback">
                                    {{ errors.member_type }}
                                </div>
                            </div>
                            
                            <div class="col-lg-12" v-if="form.level == 2 && $page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</label>
                                <input type="text" class="form-control" v-model="form.code" :class="{ 'is-invalid': errors.code }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'code')?.translate">
                                <div v-if="errors.code" class="invalid-feedback">
                                    {{ errors.code }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</label>
                                <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'name')?.translate">
                                <div v-if="errors.name" class="invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</label>
                                <input type="email" class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'email')?.translate">
                                <div v-if="errors.email" class="invalid-feedback">
                                    {{ errors.email }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'username')?.translate }}</label>
                                <input type="text" class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'username')?.translate">
                                <div v-if="errors.username" class="invalid-feedback">
                                    {{ errors.username }}
                                </div>
                            </div>
                            
                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                <input type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                <div v-if="errors.password" class="invalid-feedback">
                                    {{ errors.password }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                <label class="form-label">Konfirmasi {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                <input type="password" class="form-control" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                <div v-if="errors.password_confirmation" class="invalid-feedback">
                                    {{ errors.password_confirmation }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'photo' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'photo')?.translate }}</label>
                                <input type="file" @change="handlePhotoChange" @input="form.photo = $event.target.files[0]" class="form-control" :class="{ 'is-invalid': errors.photo }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'photo')?.translate">
                                <div v-if="errors.photo" class="invalid-feedback">
                                    {{ errors.photo }}
                                </div>
                                <div v-if="photoPreview" class="mt-3">
                                    <img :src="photoPreview" alt="Preview" style="max-width:100%; width: 150px; border-radius: 8px;" />
                                </div>
                                <div v-else-if="user.photo" class="mt-3">
                                    <img  v-bind:src="'/storage/upload_files/photos/' + user.photo" style="max-width:100%; width: 150px; border-radius: 8px;"/>
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="form.level == 2 && $page.props.setting.authentication_field.some(field => field.name == 'class_name' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'class_name')?.translate }}</label>
                                <input type="text" class="form-control" v-model="form.class_name" :class="{ 'is-invalid': errors.class_name }" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'class_name')?.translate">
                                <div v-if="errors.class_name" class="invalid-feedback">
                                    {{ errors.class_name }}
                                </div>
                            </div>
                            
                            <div class="col-12" v-if="$page.props.setting.category_access == 2 && form.level == 2">
                                <label class="form-label">Pilih Kategori Peminatan</label>
                                <multiselect
                                    :class="{ 'is-invalid': errors.category_ids }"
                                    v-model="form.category_ids"
                                    :options="categories"
                                    :multiple="true"
                                    label="name"
                                    :close-on-select="true"
                                    :allow-empty="true"
                                    track-by="id"
                                    placeholder="[ Pilih ]"
                                ></multiselect>
                                <div v-if="errors.category_ids" class="invalid-feedback">
                                    {{ errors.category_ids }}
                                </div>
                                <small><i>Peserta dapat memilih lebih dari 1 kategori peminatan.</i></small>
                            </div>

                            <div class="col-lg-12">
                                <label class="form-label">Status Akun</label>
                                <select v-model="form.is_active" :class="{ 'is-invalid': errors.is_active }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Active</option>
                                    <option value="0">Non Active</option>
                                </select>
                                <div v-if="errors.is_active" class="invalid-feedback">
                                    {{ errors.is_active }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-top border-0 border-3 border-primary" v-if="$page.props.setting.enable_affiliate_feature == 1 && user.is_referrer == 1">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Pengaturan Referrer</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label class="form-label">Tipe Komisi</label>
                                <select v-model="form.commission_type" :class="{ 'is-invalid': errors.commission_type }" placeholder="Page Name" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Persentase</option>
                                    <option value="2">Nominal</option>
                                </select>
                                <div v-if="errors.commission_type" class="invalid-feedback">
                                    {{ errors.commission_type }}
                                </div>
                                <small><i>Jika dikosongkan maka akan mengambil data dari pengaturan sistem Afiliasi.</i></small>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Komisi</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2">{{ form.commission_type == 1 ? '%' : form.commission_type == 2 ? 'Rp.' : 'Pilih Type' }}</span>
                                    <input type="number" class="form-control" v-model="form.commission" :class="{ 'is-invalid': errors.commission }" placeholder="Komisi">
                                    <div v-if="errors.commission" class="invalid-feedback">
                                        {{ errors.commission }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-top border-0 border-3 border-primary">
                    <div class="card-header" v-if="['province_id', 'city_id', 'district_id', 'village_id', 'address', 'gender', 'phone_number']
                        .every(fieldName => {
                            const field = $page.props.setting.authentication_field.find(f => f.name === fieldName);
                            return field && field.is_active == '1';
                        })">
                        <h6 class="card-title mb-0">Informasi Lainnya</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</label>
                                <select  @change="cityData" v-model="form.province_id" :class="{ 'is-invalid': errors.province_id }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="province in provinces" :value="province.id">
                                        {{ province.name }}
                                    </option>
                                </select>
                                <div v-if="errors.province_id" class="invalid-feedback">
                                    {{ errors.province_id }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</label>
                                <select @change="districtData" v-model="form.city_id" :class="{ 'is-invalid': errors.city_id }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="city in form.cities" :value="city.id">
                                    {{ city.name }}
                                    </option>
                                </select>
                                <div v-if="errors.city_id" class="invalid-feedback">
                                    {{ errors.city_id }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'district_id' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'district_id')?.translate }}</label>
                                <select @change="villageData" v-model="form.district_id" :class="{ 'is-invalid': errors.district_id }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="district in form.districts" :value="district.id">
                                    {{ district.name }}
                                    </option>
                                </select>
                                <div v-if="errors.district_id" class="invalid-feedback">
                                    {{ errors.district_id }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'village_id' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'village_id')?.translate }}</label>
                                <select v-model="form.village_id" :class="{ 'is-invalid': errors.village_id }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="village in form.villages" :value="village.id">
                                    {{ village.name }}
                                    </option>
                                </select>
                                <div v-if="errors.village_id" class="invalid-feedback">
                                    {{ errors.village_id }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'address' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'address')?.translate }}</label>
                                <textarea cols="" rows="5" v-model="form.address" :class="{ 'is-invalid': errors.address }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'address')?.translate"></textarea>
                                <div v-if="errors.address" class="invalid-feedback">
                                    {{ errors.address }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'gender' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'gender')?.translate }}</label>
                                <select v-model="form.gender" :class="{ 'is-invalid': errors.gender }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option value="M">Laki-laki</option>
                                    <option value="F">Perempuan</option>
                                </select>
                                <div v-if="errors.gender" class="invalid-feedback">
                                    {{ errors.gender }}
                                </div>
                            </div>

                            <div class="col-lg-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'phone_number' && field.is_active == '1')">
                                <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'phone_number')?.translate }}</label>
                                <input type="text" v-model="form.phone_number" :class="{ 'is-invalid': errors.phone_number }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'phone_number')?.translate">
                                <div v-if="errors.phone_number" class="invalid-feedback">
                                    {{ errors.phone_number }}
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    // Import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // Import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // Import Vue Composition API
    import { reactive, watch } from 'vue';

    // Import Swal
    import Swal from 'sweetalert2';

    // Import Head dari Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';
    import axios from 'axios';

    import Multiselect from '@suadelabs/vue3-multiselect';
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';

    import { ref } from 'vue';

    export default {
        // Layout
        layout: LayoutAdmin,

        // Register components
        components: {
            Link,
            Head,
            Multiselect,
        },

        data() {
            return {
                provinces: [],
            }
        },

        // Props
        props: {
            errors: Object,
            user: Object,
            categories: Object,
            categorySelected: Object,
        },

        mounted() {
            // Get all provinces data
            axios.get('/region/province').then(response => {
                this.provinces = response.data;
            }).catch(error => console.error(error));
        },

        // Initialization Composition API
        setup(props) {         
            const form = reactive({
                id: props.user.id,
                code: props.user.code,
                name: props.user.name,
                email: props.user.email,
                username: props.user.username,
                photo: '',
                class_name: props.user.class_name,
                password: '',
                password_confirmation: '',
                member_type: props.user.member_type ?? '',
                level: props.user.level,
                commission_type: props.user.commission_type ?? '',
                commission: props.user.commission,
                is_active: props.user.is_active,

                province_id: props.user.student?.province_id || '',
                city_id: props.user.student?.city_id || '',
                district_id: props.user.student?.district_id || '',
                village_id: props.user.student?.village_id || '',
                address: props.user.student?.address || '',
                phone_number: props.user.student?.phone_number || '',
                gender: props.user.student?.gender || '',

                // Get from API
                category_ids: props.categorySelected,
                cities: [],
                districts: [],
                villages: [],
            });

            const photoPreview = ref(null);

            watch(() => form.commission_type, (newVal) => {
                if (newVal === '') {
                    form.commission = '';
                }
            });

            const cityData = () => {
                console.log('ada ke load');
                axios.get(`/region/city/${form.province_id}`).then(response => {
                    form.city_id = props.user.student?.province_id == form.province_id ? props.user.student.city_id : '';
                    form.district_id = props.user.student?.city_id == form.city_id ? props.user.student.district_id : '';
                    form.village_id = props.user.student?.district_id == form.district_id ? props.user.student.village_id : '';

                    form.cities = response.data;
                }).catch(error => console.error(error));
            }

            const districtData = () => {
                form.district_id = props.user.student?.city_id === form.city_id ? props.user.student.district_id : '';
                form.village_id = props.user.student?.district_id === form.district_id ? props.user.student.village_id : '';

                axios.get(`/region/district/${form.city_id}`).then(response => {
                    form.districts = response.data;
                }).catch(error => console.error(error));
            }

            const villageData = () => {
                form.village_id = props.user.student?.district_id === form.district_id ? props.user.student.village_id : '';

                axios.get(`/region/village/${form.district_id}`).then(response => {
                    form.villages = response.data;
                }).catch(error => console.error(error));
            }

            // Submit method
            const submit = () => {
                Inertia.post(`/admin/users/${props.user.id}`, {
                    forceFormData: true,
                    _method: 'PUT',
                    id: form.id,
                    code: form.code,
                    name: form.name,
                    email: form.email,
                    username: form.username,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                    photo: form.photo,
                    class_name: form.class_name,
                    member_type: form.member_type,
                    level: form.level,
                    category_ids: form.category_ids,
                    commission_type: form.commission_type,
                    commission: form.commission,
                    is_active: form.is_active,

                    province_id: form.province_id,
                    city_id: form.city_id,
                    district_id: form.district_id,
                    village_id: form.village_id,
                    address: form.address,
                    phone_number: form.phone_number,
                    gender: form.gender,
                }, {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'User Berhasil Disimpan!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            if (props.user.student) {
                cityData();
                districtData();
                villageData();
            }
            
            return {
                form,
                submit,
                cityData,
                districtData,
                villageData,
                photoPreview
            }
        },
        methods: {
            handlePhotoChange(event) {
                const file = event.target.files[0];
                this.form.photo = file;

                if (file) {
                this.photoPreview = URL.createObjectURL(file);
                } else {
                this.photoPreview = null;
                }
            }
        }
    }
</script>