<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.buy.pageTitle }} {{ typeTranslation }}</title>
    </Head>

    <div class="page-wrapper checkout-page">
        <div class="page-content">
            <div class="checkout-hero mb-4">
                <h2 class="hero-title mb-1">{{ t.buy.pageTitle }} {{ typeTranslation }}</h2>
                <p class="hero-subtitle mb-0">Lengkapi metode pembayaran dan cek ringkasan pesanan sebelum lanjut.</p>
            </div>

            <div v-if="$page.props.session.error" class="alert alert-danger border-0 shadow-sm">
                <div v-html="$page.props.session.error"></div>
            </div>
            <div v-if="$page.props.session.success" class="alert alert-success border-0 shadow-sm">
                <div v-html="$page.props.session.success"></div>
            </div>
            <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 shadow-sm mb-4">
                <strong>{{ t.buy.warning }}</strong>
                <ul class="mt-2 mb-0">
                    <li v-for="(error, idx) in errors" :key="idx">{{ error }}</li>
                </ul>
            </div>

            <form @submit.prevent="submit">
                <div class="card border-0 shadow-sm checkout-card">
                    <div class="card-header border-0 bg-transparent p-4 pb-2 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">{{ t.buy.summaryTitle }} {{ typeTranslation }}</h6>
                        <Link :href="urlBack" class="btn btn-outline-primary btn-sm rounded-pill px-3">{{ t.common.back }}</Link>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="row g-3">
                            <div class="col-12" v-if="type != 'topupBalance'">
                                <label class="form-label form-label-modern">{{ t.common.category }}</label>
                                <input type="text" class="form-control form-control-modern readonly-control" :value="purchase.category.name" disabled>
                            </div>

                            <div class="col-12">
                                <label class="form-label form-label-modern">{{ type == 'topupBalance' ? t.buy.purchaseDescription : t.buy.productLabel }}</label>
                                <input type="text" class="form-control form-control-modern readonly-control" :value="purchase.title" disabled>
                            </div>

                            <div class="col-12 col-lg-6" v-if="type == 'voucher'">
                                <label class="form-label form-label-modern">{{ t.buy.activePeriod }}</label>
                                <input
                                    type="text"
                                    class="form-control form-control-modern readonly-control"
                                    :value="purchase.active_period + (purchase.period_type == 'day' ? ' Hari' : ' Bulan')"
                                    disabled
                                >
                            </div>

                            <div :class="type == 'voucher' ? 'col-12 col-lg-6' : 'col-12'">
                                <label class="form-label form-label-modern">{{ type == 'topupBalance' ? t.buy.nominalLabel : t.buy.priceLabel }} {{ typeTranslation }}</label>
                                <input type="text" class="form-control form-control-modern readonly-control fw-bold text-primary" :value="formatPrice(purchase.price_after_discount)" disabled>
                            </div>

                            <div class="col-12" v-if="type != 'topupBalance'">
                                <label class="form-label form-label-modern">{{ t.buy.memberCategory }}</label>
                                <div class="member-wrap">
                                    <div v-if="purchase.member_categories.length">
                                        <span v-for="(member_category, index) in purchase.member_categories" :key="index" class="badge bg-success me-1 mb-1">
                                            {{ member_category.name }}
                                        </span>
                                    </div>
                                    <span v-else class="badge bg-success">{{ t.buy.memberCategoryAll }}</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label form-label-modern">{{ t.buy.paymentMethodLabel }}</label>
                                <select v-model="form.payment_method" :class="{ 'is-invalid': errors.payment_method }" class="form-select form-select-modern">
                                    <option value="">{{ t.buy.paymentMethodPlaceholder }}</option>
                                    <option v-for="paymentMethod in filteredPaymentMethods" :key="paymentMethod.code" :value="paymentMethod.code">
                                        {{ paymentMethod.name }}
                                    </option>
                                </select>
                                <div v-if="errors.payment_method" class="invalid-feedback">{{ errors.payment_method }}</div>
                            </div>

                            <div class="col-12" v-if="paymentMethods.find(method => method.code === form.payment_method) && paymentMethods.find(method => method.code === form.payment_method).show_description == 1">
                                <div class="alert alert-success border-0 shadow-sm mb-0 py-2">
                                    {{ paymentMethods.find(method => method.code === form.payment_method).description }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button class="btn btn-primary rounded-pill px-4">{{ t.buy.continuePayment }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import { transactionText } from '../../../../lang/id/transaction';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
    },

    props: {
        errors: Object,
        purchase: Object,
        type: Object,
        typeTranslation: Object,
        urlBack: Object,
        paymentMethods: Object,
    },

    setup(props) {
        const form = reactive({
            payment_method: '',
            price: '',
        });

        const submit = () => {
            Swal.fire({
                title: transactionText.buy.confirmTitle,
                text: transactionText.buy.confirmText,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#008cff',
                cancelButtonColor: '#fd3550',
                confirmButtonText: transactionText.buy.confirmYes,
                cancelButtonText: transactionText.buy.confirmNo
            }).then((result) => {
                if (result.isConfirmed) {
                    Inertia.post(`/user/transactions/${props.type}/${props.purchase.id}/buy`, {
                        payment_method: form.payment_method,
                        price: props.purchase.price_after_discount,
                    });
                }
            });
        };

        return {
            form,
            submit,
            t: transactionText,
        };
    },

    methods: {
        formatPrice(value) {
            const val = (value / 1).toFixed(2).replace('.', ',');
            return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    },

    computed: {
        filteredPaymentMethods() {
            return this.$page.props.setting.payment_methods.filter(paymentMethod => {
                if (this.type === 'topupBalance' && paymentMethod.code === 'account_balance') {
                    return false;
                }
                return true;
            });
        }
    }
};
</script>

<style scoped>
.checkout-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.hero-title {
    color: #1f2430;
    font-weight: 700;
}

.hero-subtitle {
    color: #667085;
}

.checkout-card {
    border-radius: 16px;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 600;
    color: #454b5a;
    margin-bottom: 0.45rem;
}

.form-select-modern,
.form-control-modern {
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    min-height: 44px;
    font-size: 0.92rem;
}

.form-select-modern:focus,
.form-control-modern:focus {
    border-color: rgba(0, 140, 255, 0.45);
    box-shadow: 0 0 0 0.2rem rgba(0, 140, 255, 0.15);
}

.readonly-control {
    background: #fff;
    opacity: 1;
}

.member-wrap {
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    min-height: 44px;
    display: flex;
    align-items: center;
    padding: 0.55rem 0.75rem;
    background: #fff;
}
</style>
