<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Registrasi</title>
    </Head>

    <div class="register-wrapper bg-auth-layout min-vh-100 d-flex align-items-center justify-content-center">
        <div class="register-container shadow-premium overflow-hidden d-flex">
            <!-- Left Side: Branding/Image (Consistent with Login) -->
            <div class="register-branding-side d-none d-lg-flex position-relative">
                <div class="branding-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                <div class="branding-content position-relative z-1 text-white p-5 d-flex flex-column justify-content-between h-100">
                    <div class="brand-top">
                        <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="brand-logo-img mb-3" alt="Logo" />
                    </div>

                    <div class="brand-hero text-center my-4 animate-float">
                        <img src="/assets/images/cover-juaraacademy.png" class="hero-img-register" alt="Hero" />
                    </div>

                    <div class="brand-bottom px-4">
                        <h2 class="fw-bold mb-3 display-6 text-white">Mulai Perjalanan Anda Sekarang</h2>
                        <p class="opacity-75 lead fs-6">Bergabunglah dengan ribuan siswa lainnya dan raih nilai impian Anda dengan platform belajar terbaik.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Register Form -->
            <div class="register-form-side bg-white d-flex">
                <div class="form-scroll-outer w-100">
                    <div class="form-wrapper px-4 px-lg-5 py-5 animate-fade-in">
                        <div class="mobile-brand d-lg-none text-center mb-4">
                            <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="mobile-logo-img" alt="Logo" />
                        </div>

                        <div class="text-center text-lg-start mb-5">
                            <h1 class="fw-black text-dark mb-2">Buat Akun Baru</h1>
                            <p class="text-secondary small-text">Silakan lengkapi data profil Anda untuk memulai.</p>
                        </div>

                        <!-- Error Alerts -->
                        <div v-if="Object.keys(errors).length > 0" class="alert-modern alert-danger mb-4">
                            <i class="bx bx-error-circle fs-5"></i>
                            <div>
                                <span class="fw-bold">Input tidak valid:</span>
                                <ul class="mb-0 mt-1 ps-3 small">
                                    <li v-for="error in errors" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>

                        <div v-if="$page.props.session.warning" class="alert-modern alert-warning animate-fade-in mb-4">
                            <i class="bx bx-info-circle fs-5"></i>
                            <div v-html="$page.props.session.warning"></div>
                        </div>

                        <div v-if="$page.props.session.error" class="alert-modern alert-danger animate-fade-in mb-4">
                            <i class="bx bx-error-circle fs-5"></i>
                            <div v-html="$page.props.session.error"></div>
                        </div>

                        <div v-if="$page.props.session.success" class="alert-modern alert-success animate-fade-in mb-4">
                            <i class="bx bx-check-circle fs-5"></i>
                            <div v-html="$page.props.session.success"></div>
                        </div>

                        <form @submit.prevent="submit" class="auth-form overflow-visible">
                            <!-- Section: Afiliasi -->
                            <div v-if="$page.props.setting.enable_affiliate_feature == 1 && referrer" class="auth-section mb-5">
                                <h6 class="section-label">Informasi Afiliasi</h6>
                                <div class="row g-3 p-3 bg-light rounded-4 border-dashed">
                                    <div class="col-md-6" v-if="referrer.referral_link">
                                        <label class="form-label-auth">Kode Referral</label>
                                        <input type="text" class="form-control input-auth-sm bg-white" :value="referrer.referral_link.referral_code" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-auth">Pengundang</label>
                                        <input type="text" class="form-control input-auth-sm bg-white" :value="referrer.name" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Data Akun -->
                            <div class="auth-section mb-5">
                                <h6 class="section-label">Detail Akun Belajar</h6>
                                <div class="row g-3">
                                    <div v-if="hasField('code')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('code') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-qr-scan input-icon"></i>
                                            <input type="text" v-model="form.code" :class="{ 'is-invalid': errors.code }" class="form-control input-auth" :placeholder="getFieldLabel('code')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('name')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('name') }} <small v-if="isOptional('name')" class="text-muted opacity-50">(Opsional)</small></label>
                                        <div class="input-inner">
                                            <i class="bx bx-user input-icon"></i>
                                            <input type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control input-auth" :placeholder="getFieldLabel('name')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('email')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('email') }} <small v-if="isOptional('email')" class="text-muted opacity-50">(Opsional)</small></label>
                                        <div class="input-inner">
                                            <i class="bx bx-envelope input-icon"></i>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control input-auth" :placeholder="getFieldLabel('email')">
                                        </div>
                                        <div v-if="$page.props.setting?.notification_type == 2" class="helper-text text-info small mt-2">
                                            <i class="bx bx-info-circle me-1"></i> Link aktivasi akan dikirim melalui email.
                                        </div>
                                    </div>

                                    <div v-if="hasField('username')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('username') }} <small v-if="isOptional('username')" class="text-muted opacity-50">(Opsional)</small></label>
                                        <div class="input-inner">
                                            <i class="bx bx-id-card input-icon"></i>
                                            <input type="text" v-model="form.username" :class="{ 'is-invalid': errors.username }" class="form-control input-auth" :placeholder="getFieldLabel('username')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('phone_number')" class="col-md-12">
                                        <label class="form-label-auth">{{ getFieldLabel('phone_number') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-phone input-icon"></i>
                                            <input type="text" v-model="form.phone_number" :class="{ 'is-invalid': errors.phone_number }" class="form-control input-auth" :placeholder="getFieldLabel('phone_number')">
                                        </div>
                                        <!-- <div v-if="$page.props.setting?.notification_type == 1" class="helper-text text-success small mt-2">
                                            <i class="bx bxl-whatsapp me-1"></i> Link aktivasi dikirim via WhatsApp.
                                        </div> -->
                                    </div>

                                    <div v-if="hasField('password')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('password') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-lock-alt input-icon"></i>
                                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control input-auth" :placeholder="getFieldLabel('password')">
                                            <button type="button" class="btn-eye-toggle" @click="toggleShowPassword">
                                                <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="hasField('password')" class="col-md-6">
                                        <label class="form-label-auth">Konfirmasi</label>
                                        <div class="input-inner">
                                            <i class="bx bx-lock-alt input-icon"></i>
                                            <input :type="showPasswordConfirmation ? 'text' : 'password'" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control input-auth" placeholder="Ulangi Password">
                                            <button type="button" class="btn-eye-toggle" @click="toggleShowPasswordConfirmation">
                                                <i :class="showPasswordConfirmation ? 'bx bx-show' : 'bx bx-hide'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Data Profil -->
                            <!-- <div class="auth-section mb-5">
                                <h6 class="section-label">Informasi Personal</h6>
                                <div class="row g-3">
                                    <div v-if="hasField('class_name')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('class_name') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-book input-icon"></i>
                                            <input type="text" v-model="form.class_name" :class="{ 'is-invalid': errors.class_name }" class="form-control input-auth" :placeholder="getFieldLabel('class_name')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('gender')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('gender') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-male-female input-icon"></i>
                                            <select v-model="form.gender" :class="{ 'is-invalid': errors.gender }" class="form-select input-auth">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="M">Laki-laki</option>
                                                <option value="F">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('phone_number')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('phone_number') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-phone input-icon"></i>
                                            <input type="text" v-model="form.phone_number" :class="{ 'is-invalid': errors.phone_number }" class="form-control input-auth" :placeholder="getFieldLabel('phone_number')">
                                        </div>
                                        <div v-if="$page.props.setting?.notification_type == 1" class="helper-text text-success small mt-2">
                                            <i class="bx bxl-whatsapp me-1"></i> Link aktivasi dikirim via WhatsApp.
                                        </div>
                                    </div>

                                    <div v-if="hasField('province_id')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('province_id') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-map-pin input-icon"></i>
                                            <select @change="cityData" v-model="form.province_id" :class="{ 'is-invalid': errors.province_id }" class="form-select input-auth">
                                                <option value="">Pilih Provinsi</option>
                                                <option v-for="province in provinces" :key="province.id" :value="province.id">{{ province.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('city_id')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('city_id') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-buildings input-icon"></i>
                                            <select @change="districtData" v-model="form.city_id" :class="{ 'is-invalid': errors.city_id }" class="form-select input-auth">
                                                <option value="">Pilih Kota/Kab</option>
                                                <option v-for="city in form.cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('district_id')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('district_id') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-map-alt input-icon"></i>
                                            <select @change="villageData" v-model="form.district_id" :class="{ 'is-invalid': errors.district_id }" class="form-select input-auth">
                                                <option value="">Pilih Kecamatan</option>
                                                <option v-for="district in form.districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('village_id')" class="col-md-6">
                                        <label class="form-label-auth">{{ getFieldLabel('village_id') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-home-alt input-icon"></i>
                                            <select v-model="form.village_id" :class="{ 'is-invalid': errors.village_id }" class="form-select input-auth">
                                                <option value="">Pilih Desa/Kel</option>
                                                <option v-for="village in form.villages" :key="village.id" :value="village.id">{{ village.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('address')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('address') }}</label>
                                        <div class="input-inner">
                                            <i class="bx bx-pencil input-icon mt-3 align-self-start"></i>
                                            <textarea v-model="form.address" :class="{ 'is-invalid': errors.address }" class="form-control input-auth h-auto py-3" rows="3" :placeholder="getFieldLabel('address')"></textarea>
                                        </div>
                                    </div>

                                    <div v-if="hasField('photo')" class="col-12">
                                        <label class="form-label-auth">{{ getFieldLabel('photo') }}</label>
                                        <div class="photo-uploader mt-2">
                                            <input type="file" @change="handlePhotoChange" id="photo-file" class="d-none">
                                            <label for="photo-file" class="uploader-body animate-hover-lift">
                                                <div v-if="!photoPreview" class="upload-indicator">
                                                    <div class="icon-circle mb-3"><i class="bx bx-cloud-upload text-primary animate-bounce-up"></i></div>
                                                    <span class="text-dark fw-semibold">Pilih file foto</span>
                                                    <span class="text-secondary small mt-1">PNG, JPG atau JPEG (Maks. 2MB)</span>
                                                </div>
                                                <div v-else class="preview-active">
                                                    <img :src="photoPreview" alt="Upload Preview">
                                                    <div class="change-overlay"><i class="bx bx-refresh"></i> Ubah Foto</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Section: Kategori Peminatan -->
                            <div v-if="$page.props.setting.category_access == 2" class="auth-section mb-5">
                                <h6 class="section-label">Peminatan Belajar</h6>
                                <div class="col-12">
                                    <multiselect
                                        v-model="form.category_ids"
                                        :options="categories"
                                        :multiple="true"
                                        label="name"
                                        track-by="id"
                                        placeholder="Pilih Kategori"
                                        class="modern-select"
                                    ></multiselect>
                                    <small class="text-secondary mt-3 d-block"><i class="bx bx-info-circle me-1"></i> Anda dapat memilih lebih dari satu kategori kursus.</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-auth-primary w-100 py-3 text-white" :disabled="form.processing">
                                <span v-if="!form.processing">Daftar</span>
                                <span v-else class="spinner-border spinner-border-sm me-2"></span>
                                <i v-if="!form.processing" class="bx bx-user-plus ms-2 fs-5"></i>
                            </button>
                        </form>

                        <div class="register-footer text-center mt-4">
                            <p class="text-secondary small mb-3">Sudah memiliki akun belajar?</p>
                            <Link href="/login" class="btn btn-outline-auth w-100 py-[12px]">Kembali ke Halaman Login</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Scoped Redesign Variables */
:root {
    --auth-accent: #4f46e5;
    --transition: cubic-bezier(0.4, 0, 0.2, 1);
}

.register-container {
    width: 100%;
    max-width: 1200px;
    height: 90vh;
    background: #fff;
    border-radius: 32px;
    box-shadow: 0 40px 100px -10px rgba(0, 0, 0, 0.1);
}

/* Left Section Styles */
.register-branding-side {
    flex: 0.8;
    background-image: url('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80');
    background-size: cover;
    background-position: center;
}

.branding-overlay {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.92) 0%, rgba(124, 58, 237, 0.92) 100%);
}

.brand-logo-img { height: 45px; filter: brightness(0) invert(1); }
.hero-img-register { max-width: 400px; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2)); }

/* Right Section Scrollable Form */
.register-form-side { flex: 1.2; position: relative; }
.form-scroll-outer { height: 100%; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
.form-scroll-outer::-webkit-scrollbar { width: 6px; }
.form-scroll-outer::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

.form-wrapper { max-width: 640px; margin: 0 auto; }
.fw-black { font-weight: 850; }
.small-text { font-size: 0.95rem; }

.section-label {
    font-size: 0.8rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #4f46e5;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.section-label::after { content: ""; flex: 1; height: 2px; background: #f1f5f9; }

/* Input Components (Shared with Login) */
.form-label-auth { font-size: 0.88rem; font-weight: 600; color: #475569; margin-bottom: 8px; }
.input-inner { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 18px; font-size: 1.3rem; color: #94a3b8; }
.input-auth {
    height: 56px;
    padding-left: 50px;
    border: 2px solid #f1f5f9;
    background-color: #f8fafc;
    border-radius: 14px;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s var(--transition);
}

.input-auth:focus { background-color: #fff; border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
.input-auth-sm { height: 48px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; }
.border-dashed { border: 2px dashed #e2e8f0 !important; }

.btn-eye-toggle { position: absolute; right: 18px; background: none; border: none; color: #94a3b8; font-size: 1.3rem; }

/* Photo Uploader Style */
.photo-uploader .uploader-body {
    display: block;
    width: 100%;
    min-height: 180px;
    border: 2px dashed #e2e8f0;
    background: #f8fafc;
    border-radius: 20px;
    cursor: pointer;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
}

.photo-uploader .uploader-body:hover { border-color: #4f46e5; background: #f0f4ff; }
.upload-indicator { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 180px; }
.icon-circle { width: 56px; height: 56px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
.icon-circle i { font-size: 1.8rem; }

.preview-active { height: 180px; width: 100%; position: relative; }
.preview-active img { width: 100%; height: 100%; object-fit: contain; background: #f1f5f9; }
.change-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px; opacity: 0; transition: 0.3s; font-weight: 600; }
.preview-active:hover .change-overlay { opacity: 1; }

/* Buttons & Links */
.btn-auth-primary {
    height: 60px;
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    border: none;
    border-radius: 16px;
    font-weight: 700;
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
}

.btn-outline-auth { border: 2px solid #e2e8f0; border-radius: 14px; color: #64748b; font-weight: 600; }

/* Alerts */
.alert-modern { padding: 16px 20px; border-radius: 16px; display: flex; align-items: flex-start; gap: 12px; font-size: 0.9rem; }
.alert-danger { background-color: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }
.alert-warning { background-color: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }

/* Animation Classes */
.animate-fade-in { animation: fadeIn 1s var(--transition); }
@keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
.animate-float { animation: float 4s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
@keyframes bounce-up { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
.animate-bounce-up { animation: bounce-up 1.5s infinite; }
.animate-hover-lift { transition: transform 0.3s; }
.photo-uploader .uploader-body:hover { transform: translateY(-3px); }

/* Mobile Logic */
@media (max-width: 991px) {
    .register-container { height: auto; border-radius: 0; min-height: 100vh; }
    .form-scroll-outer { overflow: visible; }
    .mobile-logo-img { height: 45px; }
}
</style>

<script>
    import LayoutAuth from '../../Layouts/Auth.vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { reactive, ref, onMounted } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import Multiselect from '@suadelabs/vue3-multiselect';
    import axios from 'axios';
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';

    export default {
        layout: LayoutAuth,
        components: { Head, Link, Multiselect },
        props: {
            errors: Object,
            session: Object,
            referrer: Object,
            categories: Object,
        },
        setup(props) {
            const provinces = ref([]);
            const photoPreview = ref(null);
            const showPassword = ref(false);
            const showPasswordConfirmation = ref(false);

            const form = reactive({
                code: '', name: '', email: '', username: '',
                password: '', password_confirmation: '',
                province_id: '', city_id: '', district_id: '', village_id: '',
                address: '', photo: '', class_name: '', phone_number: '',
                gender: '', category_ids: [],
                cities: [], districts: [], villages: [],
                processing: false
            });

            onMounted(() => {
                axios.get('region/province').then(res => { provinces.value = res.data; });
            });

            const cityData = () => {
                form.city_id = ''; form.district_id = ''; form.village_id = '';
                axios.get(`/region/city/${form.province_id}`).then(res => { form.cities = res.data; });
            };

            const districtData = () => {
                form.district_id = ''; form.village_id = '';
                axios.get(`/region/district/${form.city_id}`).then(res => { form.districts = res.data; });
            };

            const villageData = () => {
                form.village_id = '';
                axios.get(`/region/village/${form.district_id}`).then(res => { form.villages = res.data; });
            };

            const submit = () => {
                form.processing = true;
                Inertia.post('/register', {
                    ...form,
                    forceFormData: true
                }, {
                    onFinish: () => form.processing = false
                });
            };

            const toggleShowPassword = () => showPassword.value = !showPassword.value;
            const toggleShowPasswordConfirmation = () => showPasswordConfirmation.value = !showPasswordConfirmation.value;

            return {
                form, provinces, photoPreview, showPassword, showPasswordConfirmation,
                cityData, districtData, villageData, submit,
                toggleShowPassword, toggleShowPasswordConfirmation
            };
        },
        methods: {
            handlePhotoChange(event) {
                const file = event.target.files[0];
                this.form.photo = file;
                this.photoPreview = file ? URL.createObjectURL(file) : null;
            },
            hasField(fieldName) {
                return this.$page.props.setting?.authentication_field?.some(f => f.name === fieldName && f.is_active == '1');
            },
            getFieldLabel(fieldName) {
                return this.$page.props.setting?.authentication_field?.find(f => f.name === fieldName)?.translate || fieldName;
            },
            isOptional(fieldName) {
                return this.$page.props.setting?.authentication_field?.some(f => f.name === fieldName && f.is_required == '0');
            }
        }
    }
</script>


<!-- <style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style> -->
