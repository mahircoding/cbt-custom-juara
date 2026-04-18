<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - {{ t.success.pageTitle }}</title>
    </Head>

    <div class="page-wrapper success-page">
        <div class="page-content">
            <div class="success-shell card border-0 shadow-sm overflow-hidden">
                <div class="row g-0 align-items-center">
                    <div class="col-12 col-xl-6 success-illustration-wrap">
                        <img src="/assets/images/payment_success.gif" class="img-fluid success-illustration" alt="Payment Success">
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="card-body p-4 p-md-5">
                            <span class="status-chip mb-3"><i class="bx bx-check-circle me-1"></i> {{ t.success.pageTitle }}</span>
                            <h1 class="success-title mb-2">{{ t.success.title }}</h1>
                            <p class="success-description mb-4">{{ t.success.description }}</p>

                            <div class="d-flex flex-wrap gap-2">
                                <Link href="/user/dashboard" class="btn btn-primary rounded-pill px-4">
                                    {{ t.success.backDashboard }}
                                </Link>
                                <Link href="/user/transactions" class="btn btn-outline-primary rounded-pill px-4">
                                    {{ t.success.viewHistory }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Head } from '@inertiajs/inertia-vue3';
import { transactionText } from '../../../../lang/id/transaction';

export default {
    layout: LayoutUser,

    components: {
        Link,
        Head,
    },

    data() {
        return {
            t: transactionText,
        };
    },
};
</script>

<style scoped>
.success-page {
    background: radial-gradient(1200px 300px at 20% -15%, rgba(0, 140, 255, 0.08) 0%, rgba(0, 140, 255, 0) 60%), #f8fbff;
}

.success-shell {
    border-radius: 20px;
}

.success-illustration-wrap {
    background: linear-gradient(145deg, rgba(0, 140, 255, 0.14), rgba(0, 140, 255, 0.03));
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
}

.success-illustration {
    max-width: 460px;
}

.status-chip {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.3rem 0.7rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #008cff;
    background: rgba(0, 140, 255, 0.12);
}

.success-title {
    font-weight: 800;
    color: #1f2430;
}

.success-description {
    color: #667085;
    white-space: pre-line;
}

@media (max-width: 1199.98px) {
    .success-illustration {
        max-width: 320px;
    }
}
</style>
