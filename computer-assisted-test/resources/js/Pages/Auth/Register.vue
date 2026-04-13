<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Registrasi</title>
    </Head>

    <div class="register-wrapper">
        <div class="register-container">
            <!-- Left Side: Branding/Image (Consistent with Login) -->
            <div class="register-image-side d-none d-lg-flex">
                <div class="image-content">
                    <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="brand-logo" alt="Logo" />
                    <img src="https://casn.privatalfaiz.id/assets/images/alfaiz/login-img.png" class="hero-image" alt="Register Graphic" />
                    <div class="welcome-text">
                        <h2>Bergabunglah Bersama Kami</h2>
                        <p>Dapatkan akses penuh ke seluruh fitur dan mulai perjalanan sukses Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Register Form -->
            <div class="register-form-side">
                <div class="form-scroll-container">
                    <div class="form-content">
                        <div class="form-header text-center text-lg-start">
                            <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="mobile-logo d-lg-none mx-auto mb-4" alt="Logo" />
                            <h1>Registrasi</h1>
                            <p class="text-muted">Lengkapi data di bawah ini untuk membuat akun baru.</p>
                        </div>

                        <!-- Error Alerts -->
                        <div v-if="Object.keys(errors).length > 0" class="alert custom-alert alert-danger border-0 mb-4">
                            <i class="bx bx-error-circle"></i>
                            <div>
                                <strong>Mohon periksa kembali inputan Anda:</strong>
                                <ul class="mb-0 mt-2 list-unstyled">
                                    <li v-for="error in errors" :key="error" class="small">• {{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div v-if="$page.props.session.error" class="alert custom-alert alert-danger border-0 mb-4">
                            <i class="bx bx-error-circle"></i> <div v-html="$page.props.session.error"></div>
                        </div>

                        <form @submit.prevent="submit" class="mt-4">
                            <!-- Section: Afiliasi (If enabled) -->
                            <div v-if="$page.props.setting.enable_affiliate_feature == 1 && referrer" class="form-section mb-4">
                                <h6 class="section-title">Informasi Afiliasi</h6>
                                <div class="row g-3">
                                    <div class="col-md-6" v-if="referrer.referral_link">
                                        <label class="form-label small">Kode Referral</label>
                                        <input type="text" class="form-control custom-input readonly-input" :value="referrer.referral_link.referral_code" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Nama Pengundang</label>
                                        <input type="text" class="form-control custom-input readonly-input" :value="referrer.name" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Data Akun -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">Informasi Akun</h6>
                                <div class="row g-3">
                                    <!-- Dynamic Fields based on settings -->
                                    <div v-if="hasField('code')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('code') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-qr-scan input-icon"></i>
                                            <input type="text" v-model="form.code" :class="{ 'is-invalid': errors.code }" class="form-control custom-input" :placeholder="getFieldLabel('code')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('name')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('name') }} <small v-if="isOptional('name')" class="text-muted">(Opsional)</small></label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-user input-icon"></i>
                                            <input type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control custom-input" :placeholder="getFieldLabel('name')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('email')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('email') }} <small v-if="isOptional('email')" class="text-muted">(Opsional)</small></label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-envelope input-icon"></i>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control custom-input" :placeholder="getFieldLabel('email')">
                                        </div>
                                        <small v-if="$page.props.setting?.notification_type == 2" class="text-info mt-1 d-block small">
                                            <i class="bx bx-info-circle me-1"></i> Link aktivasi akan dikirim ke email ini.
                                        </small>
                                    </div>

                                    <div v-if="hasField('username')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('username') }} <small v-if="isOptional('username')" class="text-muted">(Opsional)</small></label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-id-card input-icon"></i>
                                            <input type="text" v-model="form.username" :class="{ 'is-invalid': errors.username }" class="form-control custom-input" :placeholder="getFieldLabel('username')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('password')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('password') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-lock-alt input-icon"></i>
                                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control custom-input" :placeholder="getFieldLabel('password')">
                                            <button type="button" class="btn-toggle-password" @click="toggleShowPassword">
                                                <i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="hasField('password')" class="col-md-6">
                                        <label class="form-label">Konfirmasi {{ getFieldLabel('password') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-check-double input-icon"></i>
                                            <input :type="showPasswordConfirmation ? 'text' : 'password'" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control custom-input" :placeholder="'Ulangi ' + getFieldLabel('password')">
                                            <button type="button" class="btn-toggle-password" @click="toggleShowPasswordConfirmation">
                                                <i :class="{ 'bx bx-show': showPasswordConfirmation, 'bx bx-hide': !showPasswordConfirmation }"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Data Profil -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">Detail Profil</h6>
                                <div class="row g-3">
                                    <div v-if="hasField('class_name')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('class_name') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-book input-icon"></i>
                                            <input type="text" v-model="form.class_name" :class="{ 'is-invalid': errors.class_name }" class="form-control custom-input" :placeholder="getFieldLabel('class_name')">
                                        </div>
                                    </div>

                                    <div v-if="hasField('gender')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('gender') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-male-female input-icon"></i>
                                            <select v-model="form.gender" :class="{ 'is-invalid': errors.gender }" class="form-select custom-select">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="M">Laki-laki</option>
                                                <option value="F">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('phone_number')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('phone_number') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-phone input-icon"></i>
                                            <input type="text" v-model="form.phone_number" :class="{ 'is-invalid': errors.phone_number }" class="form-control custom-input" :placeholder="getFieldLabel('phone_number')">
                                        </div>
                                        <small v-if="$page.props.setting?.notification_type == 1" class="text-info mt-1 d-block small">
                                            <i class="bx bx-info-circle me-1"></i> Link aktivasi dikirim via WhatsApp.
                                        </small>
                                    </div>

                                    <!-- Location Dropdowns -->
                                    <div v-if="hasField('province_id')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('province_id') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-map-pin input-icon"></i>
                                            <select @change="cityData" v-model="form.province_id" :class="{ 'is-invalid': errors.province_id }" class="form-select custom-select">
                                                <option value="">Pilih Provinsi</option>
                                                <option v-for="province in provinces" :key="province.id" :value="province.id">{{ province.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('city_id')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('city_id') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-buildings input-icon"></i>
                                            <select @change="districtData" v-model="form.city_id" :class="{ 'is-invalid': errors.city_id }" class="form-select custom-select">
                                                <option value="">Pilih Kota/Kab</option>
                                                <option v-for="city in form.cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('district_id')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('district_id') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-map-alt input-icon"></i>
                                            <select @change="villageData" v-model="form.district_id" :class="{ 'is-invalid': errors.district_id }" class="form-select custom-select">
                                                <option value="">Pilih Kecamatan</option>
                                                <option v-for="district in form.districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('village_id')" class="col-md-6">
                                        <label class="form-label">{{ getFieldLabel('village_id') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-home-alt input-icon"></i>
                                            <select v-model="form.village_id" :class="{ 'is-invalid': errors.village_id }" class="form-select custom-select">
                                                <option value="">Pilih Desa/Kel</option>
                                                <option v-for="village in form.villages" :key="village.id" :value="village.id">{{ village.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="hasField('address')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('address') }}</label>
                                        <div class="input-wrapper">
                                            <i class="bx bx-pencil input-icon align-self-start mt-3"></i>
                                            <textarea v-model="form.address" :class="{ 'is-invalid': errors.address }" class="form-control custom-textarea" rows="3" :placeholder="getFieldLabel('address')"></textarea>
                                        </div>
                                    </div>

                                    <div v-if="hasField('photo')" class="col-12">
                                        <label class="form-label">{{ getFieldLabel('photo') }}</label>
                                        <div class="photo-upload-container">
                                            <input type="file" @change="handlePhotoChange" id="photo-input" class="d-none">
                                            <label for="photo-input" class="photo-upload-label">
                                                <div v-if="!photoPreview" class="upload-placeholder">
                                                    <i class="bx bx-cloud-upload"></i>
                                                    <span>Klik untuk unggah foto</span>
                                                </div>
                                                <img v-else :src="photoPreview" class="photo-preview-img" alt="Preview" />
                                            </label>
                                            <div v-if="errors.photo" class="error-text text-danger mt-1 small">{{ errors.photo }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Kategori Peminatan (If multi-choice enabled) -->
                            <div v-if="$page.props.setting.category_access == 2" class="form-section mb-4">
                                <h6 class="section-title">Kategori Peminatan</h6>
                                <div class="col-12">
                                    <multiselect
                                        v-model="form.category_ids"
                                        :options="categories"
                                        :multiple="true"
                                        label="name"
                                        track-by="id"
                                        placeholder="Pilih Kategori"
                                        class="custom-multiselect"
                                    ></multiselect>
                                    <small class="text-muted mt-2 d-block small">Peserta dapat memilih lebih dari 1 kategori.</small>
                                </div>
                            </div>

                            <div class="form-actions mt-5">
                                <button type="submit" class="btn btn-primary btn-register-submit w-100" :disabled="form.processing">
                                    <span>Daftar Sekarang</span>
                                    <i class="bx bx-user-plus ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="form-footer mt-5 text-center px-4 mb-5">
                            <p class="mb-2">Sudah memiliki akun?</p>
                            <Link href="/login" class="btn btn-outline-primary btn-login-link w-100">Kembali ke Login</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Reuse Shared Logic while optimizing for Register Page */
.register-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f2f5;
    padding: 20px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.register-container {
    width: 100%;
    max-width: 1200px;
    display: flex;
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    height: 90vh;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

/* Left Side (Image) - Same as Login */
.register-image-side {
    flex: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
}

.brand-logo { max-width: 160px; margin-bottom: 40px; }
.hero-image { width: 100%; margin-bottom: 40px; }

/* Right Side (Form) - Scrollable */
.register-form-side {
    flex: 1.2;
    background: #fff;
    position: relative;
    overflow: hidden;
}

.form-scroll-container {
    height: 100%;
    overflow-y: auto;
    padding: 60px;
    scrollbar-width: thin;
    scrollbar-color: #e2e8f0 transparent;
}

.form-scroll-container::-webkit-scrollbar { width: 6px; }
.form-scroll-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

.form-content { max-width: 580px; margin: 0 auto; width: 100%; }

.form-header h1 { font-weight: 800; font-size: 2.5rem; color: #2d3436; margin-bottom: 8px; }
.section-title {
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #4e73df;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 20px;
}

/* Input Styles - Shared */
.custom-input, .custom-select, .custom-textarea {
    background-color: #f8fafc;
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 12px 15px 12px 50px;
    height: 55px;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-textarea { height: auto; padding-left: 50px; }
.readonly-input { background-color: #f1f5f9 !important; color: #64748b; }

.custom-input:focus, .custom-select:focus, .custom-textarea:focus {
    background-color: #fff;
    border-color: #4e73df;
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
}

.input-wrapper { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 18px; font-size: 1.3rem; color: #bfc5d1; }

.btn-toggle-password {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.3rem;
    color: #94a3b8;
}

/* Photo Upload UI */
.photo-upload-label {
    display: block;
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f8fafc;
}

.photo-upload-label:hover { border-color: #4e73df; background: #f1f5f9; }

.upload-placeholder { display: flex; flex-direction: column; align-items: center; color: #94a3b8; }
.upload-placeholder i { font-size: 2.5rem; margin-bottom: 10px; }
.photo-preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 3px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

/* Buttons */
.btn-register-submit {
    height: 60px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
}

.btn-login-link { height: 50px; border-radius: 12px; font-weight: 600; }

.custom-alert { border-radius: 14px; padding: 18px; display: flex; align-items: flex-start; }
.custom-alert i { font-size: 1.5rem; margin-right: 15px; }

/* Responsive */
@media (max-width: 991px) {
    .register-container { height: auto; border-radius: 0; min-height: 100vh; }
    .form-scroll-container { height: auto; padding: 30px; overflow: visible; }
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