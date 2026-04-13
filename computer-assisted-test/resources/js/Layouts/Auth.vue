<template>
    <div class="auth-layout min-vh-100 w-100">
		<slot/>
	</div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap');

:root {
    --auth-bg: #f8fafc;
    --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    --glass-bg: rgba(255, 255, 255, 0.95);
}

.auth-layout {
    font-family: 'Inter', 'Outfit', sans-serif;
    background-color: var(--auth-bg);
    background-image: 
        radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.05) 0px, transparent 50%);
}
</style>

<script>
    export default {
    }
</script>
