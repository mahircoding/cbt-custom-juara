<template>
    <Head>
        <title>{{ $page.props.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
    </Head>

    <div class="login-wrapper bg-red-800">
        <div class="login-container">
            <!-- Left Side: Branding/Image -->
            <div class="login-image-side d-none d-lg-flex">
                <div class="image-content">
                    <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="brand-logo" alt="Logo" />
                    <img src="https://casn.privatalfaiz.id/assets/images/alfaiz/login-img.png" class="hero-image" alt="Login Graphic" />
                    <div class="welcome-text">
                        <h2>Selamat Datang di {{ $page.props.setting?.app_name ?? 'Aplikasi Kami' }}</h2>
                        <p>Persiapkan diri Anda untuk meraih kesuksesan bersama kami.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="login-form-side">
                <div class="form-content">
                    <div class="form-header text-center text-lg-start">
                        <img v-if="$page.props.setting?.logo" :src="'/storage/upload_files/settings/' + $page.props.setting.logo" class="mobile-logo d-lg-none mx-auto mb-4" alt="Logo" />
                        <h1>Login</h1>
                        <p class="text-muted">Silakan masukkan {{ loginTitle }} untuk masuk ke akun Anda.</p>
                    </div>

                    <div v-if="$page.props.session.error" class="alert custom-alert alert-danger border-0">
                        <i class="bx bx-error-circle"></i> <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.warning" class="alert custom-alert alert-warning border-0">
                        <i class="bx bx-error"></i> <div v-html="$page.props.session.warning"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert custom-alert alert-success border-0">
                        <i class="bx bx-check-circle"></i> <div v-html="$page.props.session.success"></div>
                    </div>

                    <form @submit.prevent="submit" class="mt-4">
                        <div class="form-group mb-4">
                            <label class="form-label">{{ loginTitle }}</label>
                            <div class="input-wrapper">
                                <i class="bx bx-user-circle input-icon"></i>
                                <input type="text" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control custom-input" :placeholder="loginPlaceholder">
                            </div>
                            <div v-if="errors.email" class="error-text text-danger">{{ errors.email }}</div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">{{ passwordTitle }}</label>
                                <Link href="/user/forgot-password" class="forgot-link">Lupa {{ passwordTitle }}?</Link>
                            </div>
                            <div class="input-wrapper">
                                <i class="bx bx-lock-alt input-icon"></i>
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control custom-input" :placeholder="passwordPlaceholder">
                                <button type="button" class="btn-toggle-password" @click="toggleShow">
                                    <i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i>
                                </button>
                            </div>
                            <div v-if="errors.password" class="error-text text-danger">{{ errors.password }}</div>
                        </div>

                        <div class="form-actions mt-5">
                            <button type="submit" class="btn btn-primary btn-login w-100" :disabled="form.processing">
                                <span>Masuk Sekarang</span>
                                <i class="bx bx-right-arrow-alt ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <div class="form-footer mt-5 text-center">
                        <div v-if="$page.props.setting?.add_user_registration == 1" class="register-hint">
                            <p class="mb-2">Belum punya akun?</p>
                            <Link href="/register" class="btn btn-outline-primary btn-register w-100">Daftar Akun Baru</Link>
                        </div>
                        
                        <a :href="`https://wa.me/${$page.props.setting?.whatsapp_number}?text=${encodeURI('Hallo, Admin. saya ingin bertanya....')}`" 
                           target="_blank" class="wa-float-btn mt-4">
                            <i class="bx bxl-whatsapp"></i>
                            <span>Butuh bantuan? Chat Admin</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Modern CSS Variable Palette */
:root {
    --primary-color: #4e73df;
    --primary-hover: #2e59d9;
    --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --text-main: #2d3436;
    --text-muted: #636e72;
    --input-bg: #f8f9fa;
}

.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.login-container {
    width: 100%;
    max-width: 1100px;
    display: flex;
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

/* Left Side Styles */
.login-image-side {
    flex: 1.1;
    background: var(--bg-gradient);
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    position: relative;
}

.image-content {
    text-align: center;
    max-width: 480px;
    z-index: 2;
}

.brand-logo {
    max-width: 160px;
    margin-bottom: 40px;
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.1));
}

.hero-image {
    width: 100%;
    margin-bottom: 40px;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

.welcome-text h2 {
    font-weight: 700;
    margin-bottom: 15px;
}

.welcome-text p {
    opacity: 0.9;
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Right Side Styles */
.login-form-side {
    flex: 1;
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form-content {
    max-width: 420px;
    margin: 0 auto;
    width: 100%;
}

.mobile-logo {
    height: 60px;
}

.form-header h1 {
    font-weight: 800;
    font-size: 2.5rem;
    color: var(--text-main);
    margin-bottom: 10px;
}

/* Custom Input Styles */
.form-label {
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-main);
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 20px;
    font-size: 1.4rem;
    color: #bfc5d1;
    transition: color 0.3s;
}

.custom-input {
    background-color: #f8fafc;
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 14px 20px 14px 55px;
    height: 60px;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-input:focus {
    background-color: #fff;
    border-color: #4e73df;
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
}

.custom-input:focus + .input-icon {
    color: #4e73df;
}

.btn-toggle-password {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.4rem;
    color: #94a3b8;
    cursor: pointer;
    transition: color 0.2s;
    display: flex;
    align-items: center;
}

.btn-toggle-password:hover {
    color: #4e73df;
}

/* Button & Link Styles */
.forgot-link {
    font-size: 0.9rem;
    color: #4e73df;
    text-decoration: none;
    font-weight: 600;
}

.btn-login {
    height: 60px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-gradient);
    border: none;
    box-shadow: 0 10px 20px rgba(78, 115, 223, 0.2);
    transition: all 0.3s;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(78, 115, 223, 0.3);
}

.btn-register {
    height: 55px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
}

.wa-float-btn {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    color: #075e54;
    font-weight: 600;
    background: #e7f5f4;
    padding: 10px 20px;
    border-radius: 50px;
    transition: all 0.3s;
}

.wa-float-btn i {
    font-size: 1.6rem;
    margin-right: 8px;
}

.wa-float-btn:hover {
    background: #075e54;
    color: #fff;
}

.custom-alert {
    border-radius: 12px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
}

.custom-alert i {
    font-size: 1.4rem;
    margin-right: 12px;
}

.tesssss-fdsfdsf{
    background-color: #075e54;
}

/* Responsive Overrides */
@media (max-width: 991px) {
    .login-wrapper {
        padding: 0;
    }
    .login-container {
        max-width: 100%;
        border-radius: 0;
        min-height: 100vh;
    }
    .login-form-side {
        padding: 30px;
    }
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
                if (!this.setting?.login_type || !this.setting?.authentication_field) return "Email";
                
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

