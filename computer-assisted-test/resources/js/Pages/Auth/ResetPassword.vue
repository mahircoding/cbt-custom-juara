<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Atur Ulang {{ passwordTitle }}</title>
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
                        <img src="/assets/images/bg-juaracademy-cover.png" class="hero-img-login" alt="Hero" />
                    </div>

                    <div class="brand-bottom px-4">
                        <h2 class="fw-bold mb-3 display-6 text-white">Satu Langkah Terakhir!</h2>
                        <p class="opacity-75 lead fs-6">Amankan akun Anda dengan membuat {{ passwordTitle }} baru yang kuat dan mudah diingat.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Reset Form -->
            <div class="login-form-side bg-white p-4 p-lg-5 d-flex align-items-center">
                <div class="form-wrapper w-100 animate-fade-in">
                    <div class="mobile-brand d-lg-none text-center mb-4">
                        <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="mobile-logo-img" alt="Logo" />
                    </div>

                    <div class="text-center text-lg-start mb-4">
                        <h1 class="fw-black text-dark mb-2">Atur Ulang {{ passwordTitle }}</h1>
                        <p class="text-secondary small-text">Silakan masukkan {{ passwordTitle }} baru untuk akun Anda.</p>
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
                        <!-- Email Input (Readonly) -->
                        <div class="input-group-modern mb-4 opacity-75">
                            <label class="form-label-auth">Email Terdaftar</label>
                            <div class="input-inner">
                                <i class="bx bx-envelope input-icon"></i>
                                <input type="email" v-model="form.email" class="form-control input-auth" readonly>
                            </div>
                        </div>

                        <!-- New Password Input -->
                        <div class="input-group-modern mb-4">
                            <label class="form-label-auth">{{ passwordTitle }} Baru</label>
                            <div class="input-inner">
                                <i class="bx bx-lock-alt input-icon"></i>
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': errors.password }"
                                       class="form-control input-auth" :placeholder="'Masukkan ' + passwordTitle + ' baru'">
                                <button type="button" class="btn-eye-toggle" @click="toggleShowPassword">
                                    <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                                </button>
                            </div>
                            <div v-if="errors.password" class="error-msg">{{ errors.password }}</div>
                        </div>

                        <!-- Password Confirmation Input -->
                        <div class="input-group-modern mb-4">
                            <label class="form-label-auth">Konfirmasi {{ passwordTitle }}</label>
                            <div class="input-inner">
                                <i class="bx bx-check-shield input-icon"></i>
                                <input :type="showPasswordConfirmation ? 'text' : 'password'" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }"
                                       class="form-control input-auth" :placeholder="'Ulangi ' + passwordTitle">
                                <button type="button" class="btn-eye-toggle" @click="toggleShowPasswordConfirmation">
                                    <i :class="showPasswordConfirmation ? 'bx bx-show' : 'bx bx-hide'"></i>
                                </button>
                            </div>
                            <div v-if="errors.password_confirmation" class="error-msg">{{ errors.password_confirmation }}</div>
                        </div>

                        <button type="submit" class="btn btn-auth-primary w-100 mt-2" :disabled="form.processing">
                            <span v-if="!form.processing">Simpan {{ passwordTitle }} Baru</span>
                            <span v-else class="spinner-border spinner-border-sm me-2"></span>
                            <i v-if="!form.processing" class="bx bx-save ms-2"></i>
                        </button>

                        <div class="register-cta text-center mt-5">
                            <Link href="/login" class="btn btn-outline-auth w-100">
                                <i class="bx bx-arrow-back me-2"></i> Kembali ke Login
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Redefined Variables */
:root {
    --auth-accent: #4f46e5;
    --auth-accent-hover: #4338ca;
    --transition: cubic-bezier(0.4, 0, 0.2, 1);
}

.login-wrapper {
    background-color: #f8fafc;
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
    background-image: url('https://images.unsplash.com/photo-1434031211128-095490e7e78b?auto=format&fit=crop&q=80');
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

/* Animation */
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
    height: 52px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
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

.error-msg {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 6px;
    margin-left: 4px;
    font-weight: 500;
}

/* Animations */
.animate-fade-in { animation: fadeIn 0.8s var(--transition); }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; }
@keyframes shake {
    10%, 90% { transform: translate3d(-1px, 0, 0); }
    20%, 80% { transform: translate3d(2px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
    40%, 60% { transform: translate3d(4px, 0, 0); }
}

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
            session: Object,
            setting: Object
        },
        setup() {
            const form = reactive({
                token: (new URL(document.location)).searchParams.get('token') || '',
                email: (new URL(document.location)).searchParams.get('email') || '',
                password: '',
                password_confirmation: '',
                processing: false
            });

            const showPassword = ref(false);
            const showPasswordConfirmation = ref(false);

            const toggleShowPassword = () => {
                showPassword.value = !showPassword.value;
            };

            const toggleShowPasswordConfirmation = () => {
                showPasswordConfirmation.value = !showPasswordConfirmation.value;
            };

            const submit = () => {
                form.processing = true;
                Inertia.post('/user/reset-password', {
                    email: form.email,
                    token: form.token,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                }, {
                    onFinish: () => form.processing = false
                });
            }

            return {
                form,
                showPassword,
                showPasswordConfirmation,
                toggleShowPassword,
                toggleShowPasswordConfirmation,
                submit,
            }
        },
        computed: {
            appSetting() {
                return this.$page.props.setting;
            },
            passwordTitle() {
                if (!this.appSetting?.authentication_field) return "Password";
                return this.appSetting.authentication_field.find(f => f.name === 'password')?.translate || "Password";
            }
        }
    }
</script>
