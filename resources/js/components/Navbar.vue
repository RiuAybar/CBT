<template>
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle" @click="collapseMenu">
            <i class="hamburger align-self-center"></i>
        </a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <v-select name="año" input-id="año" :options="years" :modelValue="selectedYear" @update:modelValue="changeYear" label="Año"
                        placeholder="Selecciona un año" />
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                        <img :src="getUser?.avatar_url || `/storage/avatars/Def/avatar.jpg`"
                            class="avatar img-fluid rounded me-1" :alt="getUser?.name" />
                    </a>

                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" @click.prevent="toggleUserDropdown">
                        <img :src="getUser?.avatar_url || `/storage/avatars/Def/avatar.jpg`"
                            class="avatar img-fluid rounded me-1" :alt="getUser?.name" />
                        <span class="text-dark">{{ getUser?.user?.name || getUser?.name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" :class="{ 'show': isUserDropdownOpen }">
                        <router-link class="dropdown-item" to="/perfil" @click="toggleUserDropdown">
                            <i class="align-middle me-1" data-feather="user"></i>
                            Perfil
                        </router-link>
                        <router-link class="dropdown-item" to="/escuela" @click="toggleUserDropdown">
                            <i class="align-middle me-1" data-feather="user"></i>
                            Escuela
                        </router-link>
                        <div class="dropdown-divider"></div>
                        <button @click="logout" class="dropdown-item"> <i class="bi bi-box-arrow-right"></i> Cerrar
                            sesión</button>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import { mapGetters, mapActions } from 'vuex';
import api, { setAuthToken } from '../services/api';

export default {
    name: 'Navbar',
    components: {
        vSelect
    },
    data() {
        const currentYear = new Date().getFullYear();
        return {
            isCollapsed: localStorage.getItem('isCollapsed') === 'true',
            user: {},
            years: [],
            isUserDropdownOpen: false,
        };
    },
    watch: {
        getUser: {
            handler(newVal) {
                if (newVal) {
                    this.user = { ...newVal };
                }
            },
            immediate: true,
        },
        selectedYear: {
            immediate: true,
            handler(newYear) {
                this.years = this.generateSurroundingYears(Number(newYear));
            }
        }
    },
    methods: {
        async logout() {
            await api.post('/logout');
            localStorage.removeItem('token');   // 🧹 Borra el token
            setAuthToken(null);                 // ❌ Quita el token de los headers
            this.$router.push({ name: 'login' }); // 🔁 Redirige a login
        },
        ...mapActions(['toggleCollapsed', 'setSelectedYear']),
        collapseMenu() {
            this.toggleCollapsed();
        },
        toggleUserDropdown() {
            this.isUserDropdownOpen = !this.isUserDropdownOpen;
        },
        generateSurroundingYears(centerYear) {
            const years = [];
            for (let i = centerYear - 2; i <= centerYear + 2; i++) {
                years.push(i);
            }
            return years;
        },
        changeYear(year) {
            const validYear = typeof year === 'number' && !isNaN(year) ? year : new Date().getFullYear();
            this.setSelectedYear(validYear);
        }
    },
    computed: {
        ...mapGetters('auth', ['isAuthenticated', 'getUser','hasPermission']),
        ...mapGetters(['selectedYear'])
    },
}
</script>