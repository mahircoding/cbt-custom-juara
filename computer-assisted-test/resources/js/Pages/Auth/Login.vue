<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
    </Head>

    <div class="login-wrapper bg-auth-layout min-vh-100 d-flex align-items-center justify-content-center">
        <div class="login-container shadow-premium overflow-hidden d-flex">
            <!-- Left Side: Branding/Image (60% wide on desktop) -->
            <div class="login-branding-side d-none d-lg-flex position-relative">
                <div class="branding-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                <div class="branding-content position-relative z-1 text-white p-5 d-flex flex-column justify-content-between h-100">
                    <div class="brand-top">
                        <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="brand-logo-img mb-3" alt="Logo" />
                    </div>
                    
                    <div class="brand-hero text-center my-4 animate-float">
                        <img src="https://casn.privatalfaiz.id/assets/images/alfaiz/login-img.png" class="hero-img-login" alt="Hero" />
                    </div>

                    <div class="brand-bottom px-4">
                        <h2 class="fw-bold mb-3 display-6 text-white">Siap Meraih Kesuksesan Bersama Kami?</h2>
                        <p class="opacity-75 lead fs-6">Satu langkah lebih dekat menuju masa depan impian Anda. Masuk ke dashboard untuk memulai perjalanan.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form (40% wide on desktop) -->
            <div class="login-form-side bg-white p-4 p-lg-5 d-flex align-items-center">
                <div class="form-wrapper w-100 animate-fade-in">
                    <div class="mobile-brand d-lg-none text-center mb-4">
                        <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="mobile-logo-img" alt="Logo" />
                    </div>

                    <div class="text-center text-lg-start mb-5">
                        <h1 class="fw-black text-dark mb-2">Selamat Datang</h1>
                        <p class="text-secondary small-text">Gunakan {{ loginTitle }} Anda untuk mengakses kursus dan materi belajar.</p>
                    </div>

                    <!-- Alert Sessions -->
                    <div v-if="$page.props.session.error" class="alert-modern alert-danger animate-shake mb-4">
                        <i class="bx bx-error-circle fs-5"></i>
                        <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert-modern alert-success animate-fade-in mb-4">
                        <i class="bx bx-check-circle fs-5"></i>
                        <div v-html="$page.props.session.success"></div>
                    </div>
                    <div v-if="$page.props.session.warning" class="alert-modern alert-warning animate-fade-in mb-4">
                        <i class="bx bx-info-circle fs-5"></i>
                        <div v-html="$page.props.session.warning"></div>
                    </div>

                    <form @submit.prevent="submit" class="auth-form mt-2">
                        <!-- Email Input -->
                        <div class="input-group-modern mb-4">
                            <label class="form-label-auth">{{ loginTitle }}</label>
                            <div class="input-inner">
                                <i class="bx bx-envelope input-icon"></i>
                                <input type="text" v-model="form.email" :class="{ 'is-invalid': errors.email }" 
                                       class="form-control input-auth" :placeholder="loginPlaceholder">
                            </div>
                            <div v-if="errors.email" class="error-msg">{{ errors.email }}</div>
                        </div>

                        <!-- Password Input -->
                        <div class="input-group-modern mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label-auth">{{ passwordTitle }}</label>
                                <Link href="/user/forgot-password" class="forgot-pwd-link">Lupa {{ passwordTitle }}?</Link>
                            </div>
                            <div class="input-inner">
                                <i class="bx bx-lock-alt input-icon"></i>
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': errors.password }" 
                                       class="form-control input-auth" :placeholder="passwordPlaceholder">
                                <button type="button" class="btn-eye-toggle" @click="toggleShow">
                                    <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                                </button>
                            </div>
                            <div v-if="errors.password" class="error-msg">{{ errors.password }}</div>
                        </div>

                        <button type="submit" class="btn btn-auth-primary w-100 mt-4" :disabled="form.processing">
                            <span v-if="!form.processing">Masuk Dashboard</span>
                            <span v-else class="spinner-border spinner-border-sm me-2"></span>
                            <i v-if="!form.processing" class="bx bx-chevron-right ms-2 animate-bounce-right"></i>
                        </button>

                        <div v-if="$page.props.setting?.add_user_registration == 1" class="register-cta text-center mt-5">
                            <p class="text-secondary small mb-3">Belum memiliki akun?</p>
                            <Link href="/register" class="btn btn-outline-auth w-100">Registrasi Akun Baru</Link>
                        </div>
                    </form>

                    <div class="contact-admin text-center mt-4">
                        <a :href="`https://wa.me/${$page.props.setting?.whatsapp_number}?text=${encodeURI('Hallo, Admin. saya memiliki kendala saat login....')}`" 
                           target="_blank" class="wa-admin-link">
                            <i class="bx bxl-whatsapp"></i>
                            Bantuan Chat Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Redefined Variables for Login context */
:root {
    --auth-accent: #4f46e5;
    --auth-accent-hover: #4338ca;
    --transition: cubic-bezier(0.4, 0, 0.2, 1);
}

.login-container {
    width: 100%;
    max-width: 1100px;
    height: auto;
    min-height: 700px;
    background: #fff;
    border-radius: 28px;
    box-shadow: 0 40px 100px -10px rgba(0, 0, 0, 0.1), 0 20px 50px -10px rgba(0, 0, 0, 0.05);
}

/* Left Section Styles */
.login-branding-side {
    flex: 1.2;
    background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80');
    background-size: cover;
    background-position: center;
}

.branding-overlay {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.9) 0%, rgba(124, 58, 237, 0.9) 100%);
}

.brand-logo-img {
    height: 50px;
    filter: brightness(0) invert(1);
}

.hero-img-login {
    max-width: 320px;
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2));
}

/* Animation context */
.animate-float {
    animation: floating 4s ease-in-out infinite;
}

@keyframes floating {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

/* Right Section Form Styles */
.login-form-side {
    flex: 1;
}

.fw-black { font-weight: 850; }
.small-text { font-size: 0.92rem; }

.form-wrapper {
    max-width: 420px;
    margin: 0 auto;
}

/* Modern Input System */
.form-label-auth {
    font-size: 0.88rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
}

.input-inner {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 18px;
    font-size: 1.35rem;
    color: #94a3b8;
    transition: var(--transition);
}

.input-auth {
    height: 58px;
    padding-left: 52px;
    padding-right: 20px;
    border: 2px solid #f1f5f9;
    background-color: #f8fafc;
    border-radius: 16px;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s var(--transition);
}

.input-auth:focus {
    background-color: #fff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    color: #1e293b;
}

.input-auth:focus + .input-icon {
    color: #4f46e5;
}

.btn-eye-toggle {
    position: absolute;
    right: 18px;
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.35rem;
    cursor: pointer;
    display: flex;
    align-items: center;
}

/* Buttons */
.btn-auth-primary {
    height: 60px;
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    color: #fff;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
    transition: all 0.3s var(--transition);
}

.btn-auth-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 35px -10px rgba(79, 70, 229, 0.5);
    background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
    color: #fff;
}

.btn-outline-auth {
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    color: #64748b;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-outline-auth:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
}

/* Modern Alerts */
.alert-modern {
    padding: 16px 20px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.9rem;
    font-weight: 500;
}

.alert-danger { background-color: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }
.alert-success { background-color: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e; }
.alert-warning { background-color: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }

.forgot-pwd-link {
    font-size: 0.85rem;
    font-weight: 600;
    color: #4f46e5;
    text-decoration: none;
}

.wa-admin-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #15803d;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 10px 20px;
    background-color: #f0fdf4;
    border-radius: 50px;
    transition: all 0.2s;
}

.wa-admin-link:hover {
    background-color: #15803d;
    color: #fff;
}

/* Animations */
.animate-fade-in { animation: fadeIn 0.8s var(--transition); }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bounce-right {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(4px); }
}
.animate-bounce-right { animation: bounce-right 1s infinite; }

/* Mobile Logic */
@media (max-width: 991px) {
    .login-container {
        border-radius: 0;
        box-shadow: none;
        min-height: 100vh;
        width: 100%;
    }
    .mobile-logo-img { height: 50px; }
}
</style>

<script>
    import LayoutAuth from '../../Layouts/Auth.vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { reactive, ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia';

    export default {
        layout: LayoutAuth,
        components: {
            Head,
            Link
        },
        props: {
            errors: Object,
            session: Object
        },
        setup() {
            const form = reactive({
                email: '',
                password: '',
                processing: false
            });

            const showPassword = ref(false);
            
            const submit = () => {
                form.processing = true;
                Inertia.post('/login', {
                    email: form.email,
                    password: form.password,
                }, {
                    onFinish: () => form.processing = false
                });
            }

            const toggleShow = () => {
                showPassword.value = !showPassword.value;
            };

            return {
                form,
                showPassword,
                toggleShow, 
                submit,
            }
        },
        computed: {
            setting() {
                return this.$page.props.setting;
            },
            loginTitle() {
                if (!this.setting?.login_type || !this.setting?.authentication_field) return "Email / Username";
                
                const loginTypes = this.setting.login_type;
                const fields = this.setting.authentication_field;

                const getTranslation = (type) => {
                    const field = fields.find(f => f.name === type);
                    return field ? field.translate : type;
                };

                return loginTypes.map(t => getTranslation(t.type)).join(' / ');
            },
            loginPlaceholder() {
                return String(this.loginTitle);
            },
            passwordTitle() {
                if (!this.setting?.authentication_field) return "Password";
                return this.setting.authentication_field.find(f => f.name === 'password')?.translate || "Password";
            },
            passwordPlaceholder() {
                return String(this.passwordTitle);
            }
        }
    }
</script>

