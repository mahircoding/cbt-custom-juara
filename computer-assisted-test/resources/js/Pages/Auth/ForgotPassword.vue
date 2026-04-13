<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Lupa {{ passwordTitle }}</title>
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
                        <h2 class="fw-bold mb-3 display-6 text-white">Lupa Akses Akun Anda?</h2>
                        <p class="opacity-75 lead fs-6">Tenang, kami akan membantu memulihkan akses agar Anda bisa kembali belajar dengan nyaman.</p>
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
                        <h1 class="fw-black text-dark mb-2">Reset {{ passwordTitle }}</h1>
                        <p class="text-secondary small-text">Masukkan kontak terdaftar Anda untuk menerima instruksi pemulihan.</p>
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
                        <!-- Contact Input (Email or Phone) -->
                        <div class="input-group-modern mb-4">
                            <label class="form-label-auth">{{ contactTitle }}</label>
                            <div class="input-inner">
                                <i :class="contactIcon" class="input-icon"></i>
                                <input type="text" v-model="form.contact" :class="{ 'is-invalid': errors.contact }" 
                                       class="form-control input-auth" :placeholder="contactPlaceholder">
                            </div>
                            <div v-if="errors.contact" class="error-msg">{{ errors.contact }}</div>
                        </div>

                        <button type="submit" class="btn btn-auth-primary w-100 mt-2" :disabled="form.processing">
                            <span v-if="!form.processing">Kirim Perubahan {{ passwordTitle }}</span>
                            <span v-else class="spinner-border spinner-border-sm me-2"></span>
                            <i v-if="!form.processing" class="bx bx-paper-plane ms-2"></i>
                        </button>

                        <div class="register-cta text-center mt-5">
                            <p class="text-secondary small mb-3">Tiba-tiba ingat password?</p>
                            <Link href="/login" class="btn btn-outline-auth w-100">
                                <i class="bx bx-arrow-back me-2"></i> Kembali ke Login
                            </Link>
                        </div>
                    </form>

                    <div class="contact-admin text-center mt-4">
                        <p class="text-secondary small mb-3">Kontak tidak terdaftar?</p>
                        <a :href="waHelpLink" target="_blank" class="wa-admin-link">
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
    background-image: url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80');
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
    import { reactive } from 'vue';
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
                contact: '',
                processing: false
            });

            const submit = () => {
                form.processing = true;
                Inertia.post('/user/forgot-password', {
                    contact: form.contact,
                }, {
                    onFinish: () => form.processing = false
                });
            }

            return {
                form,
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
            },
            contactTitle() {
                if (!this.appSetting?.authentication_field) return "Kontak";
                const fieldName = this.appSetting.notification_type == 1 ? 'phone_number' : 'email';
                return this.appSetting.authentication_field.find(f => f.name === fieldName)?.translate || "Kontak";
            },
            contactPlaceholder() {
                return "Contoh: " + (this.appSetting?.notification_type == 1 ? '6281212126043' : 'user@example.com');
            },
            contactIcon() {
                return this.appSetting?.notification_type == 1 ? 'bx bx-phone' : 'bx bx-envelope';
            },
            waHelpLink() {
                const appName = this.appSetting?.app_name || 'Aplikasi';
                const message = `*[FORGOT PASSWORD - ${appName}]*\n\nHallo, Admin saya sudah daftar dan ingin reset password, ketika konfirmasi kontak tidak terdaftar, berikut data saya untuk perbaikan:\n\nNama:\nEmail:\nNomor Handphone Aktif:\n\n terimakasih.`;
                return `https://wa.me/${this.appSetting?.whatsapp_number}?text=${encodeURIComponent(message)}`;
            }
        }
    }
</script>
