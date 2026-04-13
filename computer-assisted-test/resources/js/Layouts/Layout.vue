<template>
    <div class="wrapper" :class="{ toggled : isToggleActive }">
        <!-- sidebar -->
        <Sidebar :isCollapsed="isToggleActive"/>
        <!-- header -->
        <Header/>
        <!-- content -->
        <slot />

        <!-- backdrop for mobile -->
        <div class="sidebar-overlay d-lg-none" :class="{ show: isToggleActive }" @click="toggleMenu"></div>

        <Footer/>
    </div>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    // import navbar
    import Sidebar from '../Components/LayoutAdmin/Sidebar.vue';
    // import header
    import Header from '../Components/LayoutAdmin/Header.vue';
    // import footer
    import Footer from '../Components/LayoutAdmin/Footer.vue';

    export default {
        // register components
        components: {
            Link,
            Sidebar,
            Header,
            Footer
        },
        data() {
            return {
                isToggleActive: false
            }
        },
        methods: {
            toggleMenu() {
                this.isToggleActive = !this.isToggleActive;
                if (this.isToggleActive) {
                    $(".wrapper").addClass("toggled");
                } else {
                    $(".wrapper").removeClass("toggled");
                }
            }
        },
        mounted() {
            const self = this;
            $("#menu").metisMenu();

            $(".mobile-toggle-menu").on("click", function() {
                self.toggleMenu();
            });

            $(".menu-clicked").on("click", function() {
                if (window.innerWidth < 1025) {
                    self.isToggleActive = false;
                    $(".wrapper").removeClass("toggled");
                }
            });

            $(".toggle-icon").on("click", function() {
                self.toggleMenu();
            });
        }
    }
</script>

<style>
::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(200,200,200,1);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color:#fff;
        -webkit-box-shadow: inset 0 0 6px rgba(90,90,90,0.7);
    }
</style>


